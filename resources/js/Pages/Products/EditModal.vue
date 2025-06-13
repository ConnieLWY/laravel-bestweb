<template>
    <div v-if="visible" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
        <h2 class="text-xl font-semibold mb-4">
          {{ form.id ? 'Edit Product' : 'Add Product' }}
        </h2>
  
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input v-model="form.name" class="w-full border rounded p-2" />
          </div>
  
          <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea v-model="form.description" class="w-full border rounded p-2" />
          </div>
  
          <div>
            <label class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" v-model="form.price" class="w-full border rounded p-2" />
          </div>
  
          <div>
            <label class="block text-sm font-medium text-gray-700">Stock</label>
            <input type="number" v-model="form.stock" class="w-full border rounded p-2" />
          </div>
  
          <div>
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <select v-model="form.category_id" class="w-full border rounded p-2">
              <option value="" disabled>Select Category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
          </div>
  
          <div class="flex justify-end gap-3 pt-2">
            <button @click="$emit('close')" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
            <button @click="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
              {{ form.id ? 'Save' : 'Add' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { reactive, watch, onMounted, ref } from 'vue'
  import axios from 'axios'
  
  const props = defineProps({
    visible: Boolean,
    product: Object
  })
  
  const emit = defineEmits(['close', 'updated'])
  
  const form = reactive({
    id: null,
    name: '',
    description: '',
    price: '',
    stock: '',
    category_id: null
  })
  
  const categories = ref([])
  
  const loadCategories = async () => {
    try {
      const res = await axios.get('/admin/categories')
      categories.value = res.data.data || res.data
    } catch (e) {
      console.error('Failed to load categories', e)
    }
  }
  
  watch(() => props.product, (p) => {
    if (p) {
      Object.assign(form, p)
    } else {
      form.id = null
      form.name = ''
      form.description = ''
      form.price = ''
      form.stock = ''
      form.category_id = null
    }
  }, { immediate: true })
  
  const submit = async () => {
    try {
      if (form.id) {
        await axios.put(`/admin/products/${form.id}`, form)
      } else {
        await axios.post(`/admin/products`, form)
      }
      emit('updated')
      emit('close')
    } catch (e) {
      alert('Operation failed. Check console.')
      console.error(e)
    }
  }
  
  onMounted(loadCategories)
  </script>
  