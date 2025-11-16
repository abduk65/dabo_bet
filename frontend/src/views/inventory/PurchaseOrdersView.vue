<template>
  <div>
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Purchase Orders</h1>
        <p class="mt-2 text-sm text-gray-700">Manage procurement and supplier orders</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <button
          v-if="authStore.isManager"
          @click="router.push('/inventory/purchase-orders/new')"
          class="btn-primary"
        >
          <PlusIcon class="h-5 w-5 mr-2" />
          New Purchase Order
        </button>
      </div>
    </div>

    <!-- Status Filter -->
    <div class="card mb-6">
      <div class="flex flex-wrap gap-2">
        <button
          v-for="status in statuses"
          :key="status.value"
          @click="selectStatus(status.value)"
          :class="[
            'px-4 py-2 text-sm font-medium rounded-md transition-colors',
            selectedStatus === status.value
              ? 'bg-primary-100 text-primary-700 ring-2 ring-primary-500'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]"
        >
          {{ status.label }}
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="card">
      <div class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
        <p class="mt-2 text-sm text-gray-500">Loading purchase orders...</p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="card bg-red-50">
      <p class="text-red-800">{{ error }}</p>
    </div>

    <!-- Data Table -->
    <div v-else class="card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                PO Number
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Supplier
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Order Date
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Expected Date
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Total Amount
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">Actions</span>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="purchaseOrders.length === 0">
              <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                No purchase orders found.
              </td>
            </tr>
            <tr v-for="po in purchaseOrders" :key="po.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <RouterLink
                  :to="`/inventory/purchase-orders/${po.id}`"
                  class="text-sm font-medium text-primary-600 hover:text-primary-900"
                >
                  {{ po.po_number }}
                </RouterLink>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ po.supplier_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(po.order_date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ po.expected_date ? formatDate(po.expected_date) : '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatCurrency(po.total_amount) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusBadgeClass(po.status)">
                  {{ po.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <RouterLink
                  :to="`/inventory/purchase-orders/${po.id}`"
                  class="text-primary-600 hover:text-primary-900"
                >
                  View
                </RouterLink>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { inventoryService } from '@/services/inventory.service'
import { PlusIcon } from '@heroicons/vue/24/outline'
import type { PurchaseOrder } from '@/types'

const router = useRouter()
const authStore = useAuthStore()

const loading = ref(true)
const error = ref<string | null>(null)
const purchaseOrders = ref<PurchaseOrder[]>([])
const selectedStatus = ref<string | undefined>(undefined)

const statuses = [
  { value: undefined, label: 'All Orders' },
  { value: 'draft', label: 'Draft' },
  { value: 'submitted', label: 'Submitted' },
  { value: 'approved', label: 'Approved' },
  { value: 'received', label: 'Received' },
  { value: 'cancelled', label: 'Cancelled' }
]

onMounted(async () => {
  await loadPurchaseOrders()
})

async function loadPurchaseOrders() {
  try {
    loading.value = true
    error.value = null
    const params = selectedStatus.value ? { status: selectedStatus.value } : undefined
    purchaseOrders.value = await inventoryService.getPurchaseOrders(params)
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to load purchase orders'
  } finally {
    loading.value = false
  }
}

function selectStatus(status: string | undefined) {
  selectedStatus.value = status
  loadPurchaseOrders()
}

function formatDate(dateString: string): string {
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  }).format(date)
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-ET', {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: 0
  }).format(amount)
}

function getStatusBadgeClass(status: string): string {
  const baseClass = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase'
  switch (status) {
    case 'draft':
      return `${baseClass} bg-gray-100 text-gray-800`
    case 'submitted':
      return `${baseClass} bg-blue-100 text-blue-800`
    case 'approved':
      return `${baseClass} bg-green-100 text-green-800`
    case 'received':
      return `${baseClass} bg-purple-100 text-purple-800`
    case 'cancelled':
      return `${baseClass} bg-red-100 text-red-800`
    default:
      return `${baseClass} bg-gray-100 text-gray-800`
  }
}
</script>
