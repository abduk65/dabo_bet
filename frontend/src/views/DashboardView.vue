<template>
  <div>
    <div class="mb-8">
      <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
      <p class="mt-2 text-sm text-gray-700">Welcome back, {{ authStore.user?.name }}</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="card">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="rounded-md bg-primary-500 p-3">
              <ShoppingCartIcon class="h-6 w-6 text-white" />
            </div>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 truncate">Today's Sales</dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">{{ formatCurrency(stats.todaySales) }}</div>
              </dd>
            </dl>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="rounded-md bg-green-500 p-3">
              <CubeIcon class="h-6 w-6 text-white" />
            </div>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 truncate">Low Stock Items</dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">{{ stats.lowStockCount }}</div>
              </dd>
            </dl>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="rounded-md bg-blue-500 p-3">
              <ClipboardDocumentListIcon class="h-6 w-6 text-white" />
            </div>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 truncate">Pending Orders</dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">{{ stats.pendingPurchaseOrders }}</div>
              </dd>
            </dl>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="rounded-md bg-yellow-500 p-3">
              <CogIcon class="h-6 w-6 text-white" />
            </div>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 truncate">In Production</dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">{{ stats.activeProductionOrders }}</div>
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
      <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <RouterLink to="/sales/pos" class="card hover:shadow-md transition-shadow cursor-pointer">
          <div class="flex items-center">
            <ShoppingCartIcon class="h-8 w-8 text-primary-600 mr-3" />
            <div>
              <p class="text-sm font-medium text-gray-900">New Sale</p>
              <p class="text-xs text-gray-500">Point of Sale</p>
            </div>
          </div>
        </RouterLink>

        <RouterLink
          v-if="authStore.isManager"
          to="/inventory/purchase-orders"
          class="card hover:shadow-md transition-shadow cursor-pointer"
        >
          <div class="flex items-center">
            <ClipboardDocumentListIcon class="h-8 w-8 text-green-600 mr-3" />
            <div>
              <p class="text-sm font-medium text-gray-900">Purchase Order</p>
              <p class="text-xs text-gray-500">Order supplies</p>
            </div>
          </div>
        </RouterLink>

        <RouterLink
          v-if="authStore.isManager"
          to="/production/orders"
          class="card hover:shadow-md transition-shadow cursor-pointer"
        >
          <div class="flex items-center">
            <CogIcon class="h-8 w-8 text-blue-600 mr-3" />
            <div>
              <p class="text-sm font-medium text-gray-900">Production Order</p>
              <p class="text-xs text-gray-500">Start production</p>
            </div>
          </div>
        </RouterLink>

        <RouterLink to="/inventory/items" class="card hover:shadow-md transition-shadow cursor-pointer">
          <div class="flex items-center">
            <CubeIcon class="h-8 w-8 text-yellow-600 mr-3" />
            <div>
              <p class="text-sm font-medium text-gray-900">View Inventory</p>
              <p class="text-xs text-gray-500">Check stock levels</p>
            </div>
          </div>
        </RouterLink>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent Sales -->
      <div class="card">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Sales</h3>
        <div v-if="loading" class="text-center py-8">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
        </div>
        <div v-else-if="recentSales.length === 0" class="text-center py-8 text-gray-500">
          No recent sales
        </div>
        <div v-else class="flow-root">
          <ul role="list" class="-my-5 divide-y divide-gray-200">
            <li v-for="sale in recentSales" :key="sale.id" class="py-4">
              <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ sale.invoice_number }}
                  </p>
                  <p class="text-sm text-gray-500">
                    {{ sale.customer_name || 'Walk-in Customer' }}
                  </p>
                  <p class="text-xs text-gray-400">
                    {{ formatDate(sale.sale_date) }}
                  </p>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <span :class="getBadgeClass(sale.status)">
                    {{ sale.status }}
                  </span>
                  <p class="text-sm font-semibold text-gray-900 mt-1">
                    {{ formatCurrency(sale.total_amount) }}
                  </p>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Low Stock Items -->
      <div class="card">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Low Stock Alerts</h3>
        <div v-if="loading" class="text-center py-8">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
        </div>
        <div v-else-if="lowStockItems.length === 0" class="text-center py-8 text-gray-500">
          All items are well stocked
        </div>
        <div v-else class="flow-root">
          <ul role="list" class="-my-5 divide-y divide-gray-200">
            <li v-for="item in lowStockItems" :key="item.id" class="py-4">
              <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ item.name }}
                  </p>
                  <p class="text-sm text-gray-500">
                    {{ item.material_type?.name }}
                  </p>
                </div>
                <div class="ml-4 flex-shrink-0 text-right">
                  <p class="text-sm font-semibold text-red-600">
                    {{ item.quantity }} {{ item.unit }}
                  </p>
                  <p class="text-xs text-gray-500">
                    Min: {{ item.min_quantity }}
                  </p>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import {
  ShoppingCartIcon,
  CubeIcon,
  ClipboardDocumentListIcon,
  CogIcon
} from '@heroicons/vue/24/outline'
import type { Sale, InventoryItem } from '@/types'

const authStore = useAuthStore()
const loading = ref(true)

const stats = ref({
  todaySales: 0,
  lowStockCount: 0,
  pendingPurchaseOrders: 0,
  activeProductionOrders: 0
})

const recentSales = ref<Sale[]>([])
const lowStockItems = ref<InventoryItem[]>([])

onMounted(async () => {
  // TODO: Implement actual API calls when backend endpoints are ready
  // This is mock data for now
  setTimeout(() => {
    stats.value = {
      todaySales: 45000,
      lowStockCount: 5,
      pendingPurchaseOrders: 3,
      activeProductionOrders: 2
    }

    recentSales.value = [
      {
        id: 1,
        invoice_number: 'INV-001',
        customer_name: 'John Doe',
        sale_date: new Date().toISOString(),
        status: 'completed',
        total_amount: 5000,
        branch_id: 1
      },
      {
        id: 2,
        invoice_number: 'INV-002',
        customer_name: null,
        sale_date: new Date().toISOString(),
        status: 'completed',
        total_amount: 3500,
        branch_id: 1
      }
    ] as Sale[]

    lowStockItems.value = [
      {
        id: 1,
        name: 'White Flour',
        quantity: 50,
        unit: 'kg',
        min_quantity: 100,
        max_quantity: 500,
        material_type: { id: 1, name: 'Raw Materials', code: 'RM' }
      },
      {
        id: 2,
        name: 'Sugar',
        quantity: 20,
        unit: 'kg',
        min_quantity: 50,
        max_quantity: 200,
        material_type: { id: 1, name: 'Raw Materials', code: 'RM' }
      }
    ] as InventoryItem[]

    loading.value = false
  }, 500)
})

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-ET', {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: 0
  }).format(amount)
}

function formatDate(dateString: string): string {
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('en-US', {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}

function getBadgeClass(status: string): string {
  const baseClass = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
  switch (status) {
    case 'completed':
      return `${baseClass} bg-green-100 text-green-800`
    case 'draft':
      return `${baseClass} bg-gray-100 text-gray-800`
    case 'cancelled':
      return `${baseClass} bg-red-100 text-red-800`
    default:
      return `${baseClass} bg-blue-100 text-blue-800`
  }
}
</script>
