<template>
  <div>
    <PageHeader title="Sales" description="View and manage all sales transactions">
      <template #actions>
        <button @click="router.push('/sales/pos')" class="btn-primary">
          <PlusIcon class="h-5 w-5 mr-2" />
          New Sale
        </button>
      </template>
    </PageHeader>

    <!-- Status Filters -->
    <div class="card mb-6">
      <div class="flex flex-wrap gap-2">
        <button
          v-for="status in statuses"
          :key="status.value"
          @click="selectedStatus = status.value"
          :class="[
            'px-4 py-2 text-sm font-medium rounded-md',
            selectedStatus === status.value
              ? 'bg-primary-600 text-white'
              : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
          ]"
        >
          {{ status.label }}
        </button>
      </div>
    </div>

    <!-- Sales Table -->
    <div class="card overflow-hidden">
      <SimpleTable
        :columns="columns"
        :data="sales"
        :loading="loading"
        loading-text="Loading sales..."
        empty-text="No sales found."
      >
        <template #cell-customer_name="{ row }">
          {{ row.customer?.customer_name || 'Walk-in Customer' }}
        </template>

        <template #cell-total_amount="{ value }">
          {{ formatCurrency(value) }}
        </template>

        <template #cell-balance_due="{ value }">
          <span :class="value > 0 ? 'text-red-600 font-semibold' : 'text-gray-500'">
            {{ formatCurrency(value) }}
          </span>
        </template>

        <template #cell-status="{ value }">
          <span :class="getStatusBadgeClass(value)">
            {{ value }}
          </span>
        </template>

        <template #actions="{ row }">
          <button @click="viewSale(row)" class="text-primary-600 hover:text-primary-900 mr-4">View</button>
          <button v-if="row.status === 'draft' && authStore.isManager" @click="completeSale(row)" class="text-green-600 hover:text-green-900 mr-4">Complete</button>
          <button v-if="row.status !== 'cancelled' && authStore.isManager" @click="cancelSale(row)" class="text-red-600 hover:text-red-900">Cancel</button>
        </template>
      </SimpleTable>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { salesService, type Sale } from '@/services/sales.service'
import PageHeader from '@/components/common/PageHeader.vue'
import SimpleTable from '@/components/common/SimpleTable.vue'
import { PlusIcon } from '@heroicons/vue/24/outline'

const router = useRouter()
const authStore = useAuthStore()
const loading = ref(true)
const sales = ref<Sale[]>([])
const selectedStatus = ref<string | undefined>(undefined)

const statuses = [
  { value: undefined, label: 'All' },
  { value: 'completed', label: 'Completed' },
  { value: 'draft', label: 'Draft' },
  { value: 'cancelled', label: 'Cancelled' }
]

const columns = [
  { key: 'sale_number', label: 'Sale #', cellClass: 'whitespace-nowrap text-sm font-medium text-gray-900' },
  { key: 'sale_date', label: 'Date', formatter: (v: string) => new Date(v).toLocaleDateString(), cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'customer_name', label: 'Customer', slot: true, cellClass: 'whitespace-nowrap text-sm text-gray-900' },
  { key: 'payment_type', label: 'Payment', cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'total_amount', label: 'Total', slot: true, cellClass: 'whitespace-nowrap text-sm text-gray-900' },
  { key: 'balance_due', label: 'Balance', slot: true, cellClass: 'whitespace-nowrap text-sm' },
  { key: 'status', label: 'Status', slot: true, cellClass: 'whitespace-nowrap' }
]

onMounted(() => loadSales())

async function loadSales() {
  try {
    loading.value = true
    sales.value = await salesService.getSales({ status: selectedStatus.value })
  } catch (err) {
    console.error('Failed to load sales:', err)
  } finally {
    loading.value = false
  }
}

function getStatusBadgeClass(status: string): string {
  const classes: Record<string, string> = {
    completed: 'badge-success',
    draft: 'badge-gray',
    cancelled: 'badge-red'
  }
  return classes[status] || 'badge-gray'
}

function viewSale(sale: Sale) {
  console.log('View sale:', sale)
}

async function completeSale(sale: Sale) {
  if (confirm(`Complete sale ${sale.sale_number}?`)) {
    try {
      await salesService.completeSale(sale.id)
      await loadSales()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to complete sale')
    }
  }
}

async function cancelSale(sale: Sale) {
  if (confirm(`Cancel sale ${sale.sale_number}? This cannot be undone.`)) {
    try {
      await salesService.cancelSale(sale.id)
      await loadSales()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to cancel sale')
    }
  }
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-ET', {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: 0
  }).format(amount)
}
</script>
