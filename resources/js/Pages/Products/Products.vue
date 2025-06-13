<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { Pencil, Trash } from 'lucide-vue-next'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import EditModal from './EditModal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';

const products = ref([])
const meta = ref({ links: [] })
const showModal = ref(false)
const editingProduct = ref(null)
const currentPage = ref(1)
const selectedIds = ref([])
const filterStatus = ref('')


const fetchProducts = async (page = 1) => {
  try {
    const params = { page }
    if (filterStatus.value !== '') {
      params.enabled = filterStatus.value
    }

    const res = await axios.get('/admin/products', { params })
    products.value = res.data.data
    meta.value = res.data.meta
    currentPage.value = res.data.meta.current_page
  } catch (e) {
    console.error('Failed to fetch products', e)
  }
}


const editProduct = (product) => {
  editingProduct.value = { ...product }
  showModal.value = true
}

const deleteProduct = async (id) => {
  if (!confirm('Delete?')) return
  await axios.delete(`/admin/products/${id}`)
  fetchProducts()
}

const bulkDelete = async () => {
  if (!confirm('Are you sure you want to delete the selected products?')) return

  try {
    await axios.post('/admin/products/bulk-delete', {
      ids: selectedIds.value
    })

    await fetchProducts(currentPage.value)
    selectedIds.value = []
  } catch (e) {
    alert('Bulk delete failed.')
    console.error(e)
  }
}

const allSelected = computed(() => {
  return products.value.length > 0 &&
    products.value.every(p => selectedIds.value.includes(p.id))
})

const toggleSelectAll = () => {
  if (allSelected.value) {
    selectedIds.value = []
  } else {
    selectedIds.value = products.value.map(p => p.id)
  }
}

const addProduct = () => {
  editingProduct.value = null // clear existing product
  showModal.value = true
}

const getPageFromUrl = (url) => {
  if (!url) return currentPage.value
  const params = new URL(url).searchParams
  return parseInt(params.get('page') || '1')
}

const changePage = (page) => {
  if (page !== currentPage.value) {
    fetchProducts(page)
  }
}

const downloadExcel = async () => {
  try {
    const response = await axios.get('/admin/export/products', {
      responseType: 'blob'
    })

    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', 'products.xlsx')
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  } catch (error) {
    alert('Download failed')
    console.error(error)
  }
}

const toggleEnabled = async (product) => {
  try {
    await axios.put(`/admin/products/${product.id}`, {
      name: product.name,
      price: product.price,
      category_id: product.category_id,
      stock: product.stock,
      enabled: !product.enabled,
    })
    product.enabled = !product.enabled
  } catch (error) {
    alert('Failed to update status')
    console.error(error)
  }
}


onMounted(fetchProducts)
</script>




<template>

  <Head title="Products" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Products</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <SecondaryButton class="mb-4 mr-2 bg-green-600 text-white hover:bg-green-700" @click="addProduct">
              Add Product
            </SecondaryButton>
            <SecondaryButton class="mb-4 bg-red-500 text-white hover:bg-red-600" @click="bulkDelete"
              :disabled="selectedIds.length === 0">
              Bulk Delete
            </SecondaryButton>
            <SecondaryButton class="mb-4 ml-2 bg-blue-600 text-white hover:bg-blue-700" @click="downloadExcel">
              Download Excel
            </SecondaryButton>
            <div class="mb-4 flex items-center gap-3">
              <label class="text-sm font-medium text-gray-700">Filter by Status:</label>
              <select v-model="filterStatus" @change="fetchProducts()" class="border rounded px-3 py-1 text-sm">
                <option value="">All</option>
                <option value="1">Enabled</option>
                <option value="0">Disabled</option>
              </select>
            </div>



            <div class="bg-white shadow-md rounded-lg overflow-y-auto">
              <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 sticky top-0 z-10">
                  <tr>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                      <input type="checkbox" @change="toggleSelectAll" :checked="allSelected" />
                    </th>

                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Enabled</th>
                    <th class="px-6 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
                    <td class="px-4 py-4 whitespace-nowrap text-center">
                      <input type="checkbox" :value="product.id" v-model="selectedIds" />
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ product.id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ product.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">RM {{ product.price }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                      {{ product.category_name || '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ product.stock }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" :checked="product.enabled" @change="toggleEnabled(product)"
                          class="sr-only peer">
                        <div
                          class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-blue-600 relative after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white">
                        </div>
                      </label>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <div class="flex justify-center gap-3">
                        <button @click="editProduct(product)" class="text-indigo-600 hover:text-indigo-900">
                          <Pencil class="w-4 h-4" />
                        </button>
                        <button @click="deleteProduct(product.id)" class="text-red-600 hover:text-red-900"
                          title="Delete">
                          <Trash class="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-if="meta.links?.length" class="mt-6 flex justify-center gap-1">
              <button v-for="(link, index) in meta.links" :key="index" v-html="link.label"
                @click="link.url && changePage(getPageFromUrl(link.url))" :disabled="!link.url"
                class="px-3 py-1 text-sm border rounded" :class="{
                  'bg-blue-600 text-white': link.active,
                  'text-gray-600 bg-white hover:bg-gray-100': !link.active,
                  'cursor-not-allowed opacity-50': !link.url
                }" />
            </div>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
  <EditModal :visible="showModal" :product="editingProduct" @close="showModal = false"
    @updated="fetchProducts(currentPage)" />

</template>
