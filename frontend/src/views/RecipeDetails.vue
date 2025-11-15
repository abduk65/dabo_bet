<template>
  <CardBox class="mt-6">
    <div class="flex justify-between items-center mb-4">
      <div>
        <h2 class="text-2xl font-bold">{{ recipe.name }}</h2>
        <p class="text-gray-600 text-sm">Last updated: {{ formatDate(recipe.updated_at) }}</p>
      </div>
      <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
        <span class="sr-only">Close</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Product Information -->
    <div class="mb-6 bg-white rounded-lg shadow">
      <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium text-gray-900">Product Details</h3>
        <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <span class="block text-sm font-medium text-gray-500">Product Name</span>
            <span class="block mt-1">{{ recipe.product?.name }}</span>
          </div>
          <div>
            <span class="block text-sm font-medium text-gray-500">Type</span>
            <span class="block mt-1">{{ recipe.product?.type }}</span>
          </div>
          <div>
            <span class="block text-sm font-medium text-gray-500">Unit Price</span>
            <span class="block mt-1">{{ formatPrice(recipe.product?.unit_price) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Instructions -->
    <div class="mb-6 bg-white rounded-lg shadow">
      <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium text-gray-900">Instructions</h3>
        <div class="mt-3 prose prose-sm max-w-none">
          {{ recipe.instruction }}
        </div>
      </div>
    </div>

    <!-- Ingredients -->
    <div class="mb-6 bg-white rounded-lg shadow overflow-hidden">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg font-medium text-gray-900">Ingredients</h3>
        <p class="mt-1 text-sm text-gray-500">Detailed list of required ingredients with quantities</p>
      </div>
      <div class="border-t border-gray-200">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price/Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="item in recipe.inventory_items" :key="item.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div>
                      <div class="text-sm font-medium text-gray-900">{{ item.item_name }}</div>
                      <div class="text-sm text-gray-500">Batch #{{ item.batch_number }}</div>
                    </div>
                  </div>
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatPrice(calculateTotalPrice(item)) }}
                </td>
              </tr>
            </tbody>
            <tfoot class="bg-gray-50">
              <tr>
                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                  Total Cost:
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ formatPrice(calculateGrandTotal()) }}
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>

    <!-- Standard Batch Variety -->
    <div v-if="recipe.standard_batch_variety?.length" class="mb-6 bg-white rounded-lg shadow">
      <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium text-gray-900">Standard Batch Varieties</h3>
        <div class="mt-4 space-y-4">
          <div v-for="variety in recipe.standard_batch_variety" :key="variety.id" 
               class="flex items-center p-3 bg-gray-50 rounded-md">
            <span class="text-sm font-medium text-gray-900">{{ variety.name }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Units -->
    <div v-if="recipe.unit?.length" class="mb-6 bg-white rounded-lg shadow">
      <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium text-gray-900">Available Units</h3>
        <div class="mt-4">
          <div class="flex flex-wrap gap-2">
            <span v-for="unit in recipe.unit" :key="unit.id"
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
              {{ unit.name }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </CardBox>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'
import CardBox from '@/components/cardbox/CardBox.vue'

const props = defineProps({
  recipe: {
    type: Object,
    required: true
  }
})

defineEmits(['close'])

const findUnit = (unitId) => {
  return props.recipe.unit?.find(u => u.id === unitId)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(price || 0)
}

const calculateTotalPrice = (item) => {
  return (item.price || 0) * (item.pivot.quantity || 0)
}

const calculateGrandTotal = () => {
  return props.recipe.inventory_items?.reduce((total, item) => {
    return total + calculateTotalPrice(item)
  }, 0) || 0
}
</script>

<style scoped>
.overflow-x-auto {
  -webkit-overflow-scrolling: touch;
}

.prose {
  max-width: none;
}
</style>
