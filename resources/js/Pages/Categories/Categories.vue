<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { Pencil, Trash } from 'lucide-vue-next'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import EditModal from './EditModal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';

const categories = ref([])
const meta = ref({ links: [] })
const showModal = ref(false)
const editingCategory = ref(null)
const currentPage = ref(1)
const selectedIds = ref([])

const fetchCategories = async (page = 1) => {
  const res = await axios.get(`/admin/categories?page=${page}`)
  categories.value = res.data.data
  meta.value = res.data.meta
  currentPage.value = res.data.meta.current_page
}

const editCategory = (category) => {
  editingCategory.value = { ...category }
  showModal.value = true
}

const deleteCategory = async (id) => {
  if (!confirm('Delete?')) return
  await axios.delete(`/admin/categories/${id}`)
  fetchCategories()
}

const bulkDelete = async () => {
  if (!confirm('Are you sure you want to delete the selected categories?')) return

  try {
    await axios.post('/admin/categories/bulk-delete', {
      ids: selectedIds.value
    })

    await fetchCategories(currentPage.value)
    selectedIds.value = []
  } catch (e) {
    alert('Bulk delete failed.')
    console.error(e)
  }
}

const allSelected = computed(() => {
  return categories.value.length > 0 &&
    categories.value.every(c => selectedIds.value.includes(c.id))
})

const toggleSelectAll = () => {
  if (allSelected.value) {
    selectedIds.value = []
  } else {
    selectedIds.value = categories.value.map(c => c.id)
  }
}

const addCategory = () => {
  editingCategory.value = null
  showModal.value = true
}

const getPageFromUrl = (url) => {
  if (!url) return currentPage.value
  const params = new URL(url).searchParams
  return parseInt(params.get('page') || '1')
}

const changePage = (page) => {
  if (page !== currentPage.value) {
    fetchCategories(page)
  }
}

onMounted(fetchCategories)
</script>




<template>

  <Head title="Categories" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Categories</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <SecondaryButton class="mb-4 mr-2 bg-green-600 text-white hover:bg-green-700" @click="addCategory">
              Add Category
            </SecondaryButton>

            <SecondaryButton class="mb-4 bg-red-500 text-white hover:bg-red-600" @click="bulkDelete"
              :disabled="selectedIds.length === 0">
              Bulk Delete
            </SecondaryButton>

            <div class="bg-white shadow-md rounded-lg overflow-y-auto">
              <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 sticky top-0 z-10">
                  <tr>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                      <input type="checkbox" @change="toggleSelectAll" :checked="allSelected" />
                    </th>

                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-50">
                    <td class="px-4 py-4 whitespace-nowrap text-center">
                      <input type="checkbox" :value="category.id" v-model="selectedIds" />
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ category.id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ category.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <div class="flex justify-center gap-3">
                        <button @click="editCategory(category)" class="text-indigo-600 hover:text-indigo-900">
                          <Pencil class="w-4 h-4" />
                        </button>
                        <button @click="deleteCategory(category.id)" class="text-red-600 hover:text-red-900"
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
  <EditModal :visible="showModal" :category="editingCategory" @close="showModal = false"
    @updated="fetchCategories(currentPage)" />

</template>
