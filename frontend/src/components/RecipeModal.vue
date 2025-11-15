<template>
  <div v-if="modelValue" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center  min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0  bg-opacity-75 transition-opacity" aria-hidden="true"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-gray-900">{{ recipe?.name }}</h3>
            <button @click="$emit('update:modelValue', false)" class="text-gray-400  hover:text-gray-500">
              <span class="sr-only">Close</span>
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Product Details -->
          <div class="mb-6">
            <h4 class="text-lg font-semibold mb-2">Product Details</h4>
            <div class="bg-gray-500 p-4 rounded-lg">
              <p><span class="font-medium">Name:</span> {{ recipe?.product?.name }}</p>
              <p><span class="font-medium">Type:</span> {{ recipe?.product?.type }}</p>
              <p><span class="font-medium">Unit Price:</span> {{ formatPrice(recipe?.product?.unit_price) }}</p>
            </div>
          </div>

          <!-- Instructions -->
          <div class="mb-6">
            <h4 class="text-lg font-semibold mb-2">Instructions</h4>
            <div class="bg-gray-500 p-4 rounded-lg">
              <p>{{ recipe?.instruction }}</p>
            </div>
          </div>

          <!-- Ingredients -->
          <div class="mb-6">
            <h4 class="text-lg font-semibold mb-2">Ingredients</h4>
            <div class="bg-gray-500 rounded-lg overflow-hidden">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-500">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                  </tr>
                </thead>
                <tbody class="bg-gray-500 divide-y divide-gray-200">
                  <tr v-for="item in recipe?.inventory_items" :key="item.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ item.item_name }}
                      <span class="text-gray-500 text-xs block">Batch #{{ item.batch_number }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ item.pivot.quantity }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ findUnit(item.pivot.unit_id)?.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ formatPrice(item.price) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Standard Batch Variety -->
          <div v-if="recipe?.standard_batch_variety?.length" class="mb-6">
            <h4 class="text-lg font-semibold mb-2">Standard Batch Varieties</h4>
            <div class="bg-gray-50 p-4 rounded-lg">
              <div class="flex flex-wrap gap-2">
                <span v-for="variety in recipe.standard_batch_variety" :key="variety.id"
                      class="px-3 py-1 bg-white rounded-full text-sm">
                  {{ variety.name }}
                </span>
              </div>
            </div>
          </div>

          <!-- Units -->
          <div v-if="recipe?.unit?.length" class="mb-6">
            <h4 class="text-lg font-semibold mb-2">Available Units</h4>
            <div class="bg-gray-50 p-4 rounded-lg">
              <div class="flex flex-wrap gap-2">
                <span v-for="unit in recipe.unit" :key="unit.id"
                      class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                  {{ unit.name }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button @click="$emit('update:modelValue', false)"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true
  },
  recipe: {
    type: Object,
    required: true
  }
})

defineEmits(['update:modelValue'])

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(price || 0)
}

const findUnit = (unitId) => {
  return props.recipe?.unit?.find(u => u.id === unitId)
}
</script>
