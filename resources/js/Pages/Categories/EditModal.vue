<template>
  <div v-if="visible" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
      <h2 class="text-xl font-semibold mb-4">
        {{ form.id ? 'Edit Category' : 'Add Category' }}
      </h2>

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Name</label>
          <input v-model="form.name" class="w-full border rounded p-2" />
        </div>

        <div class="flex justify-end gap-3 pt-2">
          <button @click="$emit('close')" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
          <button @click="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            {{ form.id ? 'Update' : 'Create' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  visible: Boolean,
  category: Object // null for add mode
})

const emit = defineEmits(['close', 'updated'])

const form = reactive({
  id: null,
  name: '',
})

watch(() => props.category, (c) => {
  if (c) {
    form.id = c.id
    form.name = c.name
  } else {
    form.id = null
    form.name = ''
  }
}, { immediate: true })

const submit = async () => {
  try {
    if (form.id) {
      await axios.put(`/admin/categories/${form.id}`, form)
    } else {
      await axios.post('/admin/categories', form)
    }

    emit('updated')
    emit('close')
  } catch (e) {
    alert('Failed to save. Check console.')
    console.error(e)
  }
}
</script>
