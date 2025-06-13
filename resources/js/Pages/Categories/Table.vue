<template>
    <div class="rounded-lg border border-gray-200 overflow-hidden">
      <div class="overflow-y-auto max-h-[500px]">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-100 sticky top-0 z-10">
            <tr>
              <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">ID</th>
              <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Price</th>
              <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Category</th>
              <th class="px-6 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ product.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ product.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-700">RM {{ product.price }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                {{ product.category_name || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <div class="flex justify-center gap-3">
                  <button class="text-indigo-600 hover:text-indigo-900" title="Edit">
                    <Pencil class="w-4 h-4" />
                  </button>
                  <button class="text-red-600 hover:text-red-900" title="Delete">
                    <Trash class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  