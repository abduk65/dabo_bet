<template>
  <div class="product-performance-list">
    <!-- Loading state -->
    <div v-if="loading" class="flex items-center justify-center py-8">
      <ArrowPathIcon class="h-6 w-6 text-primary-600 animate-spin" />
    </div>

    <!-- Empty state -->
    <div v-else-if="!products || products.length === 0" class="flex flex-col items-center justify-center py-8 text-gray-500">
      <CubeIcon class="h-10 w-10 text-gray-400 mb-2" />
      <p class="text-sm">No products to display</p>
    </div>

    <!-- Product list -->
    <div v-else class="space-y-3">
      <div
        v-for="(product, index) in products"
        :key="product.id"
        :class="[
          'flex items-center gap-3 p-3 rounded-lg transition-colors',
          'hover:bg-gray-50 cursor-pointer'
        ]"
        @click="emit('product-click', product)"
      >
        <!-- Rank badge -->
        <div
          :class="[
            'flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center text-sm font-bold',
            index === 0 && type === 'top' && 'bg-yellow-100 text-yellow-800',
            index === 1 && type === 'top' && 'bg-gray-100 text-gray-600',
            index === 2 && type === 'top' && 'bg-orange-100 text-orange-600',
            index > 2 && 'bg-gray-50 text-gray-500'
          ]"
        >
          {{ index + 1 }}
        </div>

        <!-- Product image -->
        <div class="flex-shrink-0">
          <div v-if="product.image" class="h-10 w-10 rounded overflow-hidden">
            <img :src="product.image" :alt="product.name" class="h-full w-full object-cover" />
          </div>
          <div v-else class="h-10 w-10 rounded bg-gray-100 flex items-center justify-center">
            <CubeIcon class="h-5 w-5 text-gray-400" />
          </div>
        </div>

        <!-- Product info -->
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-gray-900 truncate">
            {{ product.name }}
          </p>
          <div class="flex items-center gap-3 mt-1">
            <span class="text-xs text-gray-500">
              {{ formatNumber(product.units_sold) }} sold
            </span>
            <span
              :class="[
                'text-xs font-medium',
                product.margin >= 25 ? 'text-green-600' :
                product.margin >= 15 ? 'text-yellow-600' :
                'text-red-600'
              ]"
            >
              {{ product.margin.toFixed(1) }}% margin
            </span>
          </div>
        </div>

        <!-- Metrics -->
        <div class="flex-shrink-0 text-right">
          <p class="text-sm font-bold text-gray-900">
            {{ formatCurrency(product.profit) }}
          </p>
          <div class="flex items-center justify-end gap-1 mt-1">
            <component
              :is="getTrendIcon(product.trend)"
              :class="['h-3 w-3', getTrendColor(product.trend)]"
            />
            <span :class="['text-xs font-medium', getTrendColor(product.trend)]">
              {{ formatTrend(product.trend) }}
            </span>
          </div>
        </div>

        <!-- Warning indicators for bottom performers -->
        <div v-if="type === 'bottom'" class="flex-shrink-0">
          <div class="flex flex-col gap-1">
            <ExclamationTriangleIcon
              v-if="product.stock_days_remaining !== undefined && product.stock_days_remaining < 7"
              class="h-5 w-5 text-yellow-500"
              title="Low stock turnover"
            />
            <ClockIcon
              v-if="product.days_since_last_sale !== undefined && product.days_since_last_sale > 14"
              class="h-5 w-5 text-red-500"
              title="Slow moving"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- View all link -->
    <div v-if="products.length >= 5" class="mt-4 text-center">
      <button
        @click="emit('view-all', type)"
        class="text-sm font-medium text-primary-600 hover:text-primary-700"
      >
        View all {{ type === 'top' ? 'performers' : 'products' }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ArrowUpIcon, ArrowDownIcon, CubeIcon, ExclamationTriangleIcon, ClockIcon, ArrowPathIcon } from '@heroicons/vue/24/outline'
import { formatCurrency, formatNumber } from '@/utils/formatters'

interface Product {
  id: number
  name: string
  image?: string
  units_sold: number
  margin: number
  profit: number
  trend: number
  stock_days_remaining?: number
  days_since_last_sale?: number
}

interface Props {
  products: Product[]
  type: 'top' | 'bottom'
  loading?: boolean
}

interface Emits {
  (e: 'product-click', product: Product): void
  (e: 'view-all', type: 'top' | 'bottom'): void
}

const props = withDefaults(defineProps<Props>(), {
  loading: false
})

const emit = defineEmits<Emits>()

// Methods
function getTrendIcon(trend: number) {
  return trend >= 0 ? ArrowUpIcon : ArrowDownIcon
}

function getTrendColor(trend: number): string {
  return trend >= 0 ? 'text-green-600' : 'text-red-600'
}

function formatTrend(value: number): string {
  const sign = value > 0 ? '+' : ''
  return `${sign}${value.toFixed(1)}%`
}
</script>

<style scoped>
.product-performance-list {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Smooth hover transitions */
.product-performance-list > div > div {
  transition: background-color 150ms ease-in-out;
}
</style>
