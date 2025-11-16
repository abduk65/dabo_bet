<template>
  <div>
    <PageHeader title="Payments" description="Manage customer payments and payment history">
      <template #actions>
        <button @click="openCreateModal" class="btn-primary">
          <PlusIcon class="h-5 w-5 mr-2" />
          Record Payment
        </button>
      </template>
    </PageHeader>

    <!-- Filter Tabs -->
    <div class="card mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Date Range -->
        <div>
          <label class="block text-sm font-medium text-gray-700">From Date</label>
          <input v-model="filters.fromDate" type="date" class="input mt-1" @change="applyFilters" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">To Date</label>
          <input v-model="filters.toDate" type="date" class="input mt-1" @change="applyFilters" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Payment Method</label>
          <select v-model="filters.paymentMethod" class="input mt-1" @change="applyFilters">
            <option value="">All Methods</option>
            <option value="cash">Cash</option>
            <option value="credit">Credit</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="mobile_money">Mobile Money</option>
            <option value="check">Check</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Payments Table -->
    <div class="card overflow-hidden">
      <SimpleTable
        :columns="columns"
        :data="filteredPayments"
        :loading="loading"
        loading-text="Loading payments..."
        empty-text="No payments found."
      >
        <template #cell-payment_date="{ value }">
          {{ formatDate(value) }}
        </template>

        <template #cell-customer="{ row }">
          {{ row.customer?.customer_name || '-' }}
        </template>

        <template #cell-sale="{ row }">
          <span v-if="row.sale">{{ row.sale.sale_number }}</span>
          <span v-else class="text-gray-400">-</span>
        </template>

        <template #cell-amount="{ value }">
          {{ formatCurrency(value) }}
        </template>

        <template #cell-payment_method="{ value }">
          <span :class="getPaymentMethodBadgeClass(value)">
            {{ value.replace('_', ' ') }}
          </span>
        </template>

        <template #cell-status="{ value }">
          <span :class="getStatusBadgeClass(value)">
            {{ value }}
          </span>
        </template>

        <template #actions="{ row }">
          <button @click="viewDetails(row)" class="text-blue-600 hover:text-blue-900 mr-4">View</button>
          <button v-if="row.status === 'pending' && authStore.isManager" @click="confirmPayment(row)" class="text-green-600 hover:text-green-900 mr-4">Confirm</button>
          <button v-if="row.status === 'confirmed' && authStore.isManager" @click="voidPayment(row)" class="text-red-600 hover:text-red-900">Void</button>
        </template>
      </SimpleTable>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
      <div class="card bg-blue-50">
        <p class="text-sm font-medium text-blue-800">Total Payments</p>
        <p class="mt-2 text-3xl font-bold text-blue-900">{{ formatCurrency(totalPayments) }}</p>
      </div>
      <div class="card bg-green-50">
        <p class="text-sm font-medium text-green-800">Cash Payments</p>
        <p class="mt-2 text-3xl font-bold text-green-900">{{ formatCurrency(cashPayments) }}</p>
      </div>
      <div class="card bg-purple-50">
        <p class="text-sm font-medium text-purple-800">Credit Payments</p>
        <p class="mt-2 text-3xl font-bold text-purple-900">{{ formatCurrency(creditPayments) }}</p>
      </div>
      <div class="card bg-yellow-50">
        <p class="text-sm font-medium text-yellow-800">Pending</p>
        <p class="mt-2 text-3xl font-bold text-yellow-900">{{ formatCurrency(pendingPayments) }}</p>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <Modal v-model:show="showModal" :title="isEditing ? 'Edit Payment' : 'Record Payment'" size="lg">
      <form @submit.prevent="handleSubmit">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <label class="block text-sm font-medium text-gray-700">Customer *</label>
            <select v-model="formData.customer_id" required class="input mt-1" @change="onCustomerChange">
              <option :value="undefined">Select Customer</option>
              <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                {{ customer.customer_name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Related Sale</label>
            <select v-model="formData.sale_id" class="input mt-1" :disabled="!formData.customer_id">
              <option :value="undefined">General Payment</option>
              <option v-for="sale in customerSales" :key="sale.id" :value="sale.id">
                {{ sale.sale_number }} - {{ formatCurrency(sale.balance_due) }} due
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Payment Date *</label>
            <input v-model="formData.payment_date" type="date" required class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Amount *</label>
            <input v-model.number="formData.amount" type="number" step="0.01" required class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Payment Method *</label>
            <select v-model="formData.payment_method" required class="input mt-1">
              <option value="">Select Method</option>
              <option value="cash">Cash</option>
              <option value="credit">Credit</option>
              <option value="bank_transfer">Bank Transfer</option>
              <option value="mobile_money">Mobile Money</option>
              <option value="check">Check</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Reference Number</label>
            <input v-model="formData.reference_number" type="text" class="input mt-1" placeholder="Transaction/Check #" />
          </div>

          <div v-if="formData.payment_method === 'bank_transfer'" class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Bank Details</label>
            <input v-model="formData.bank_details" type="text" class="input mt-1" placeholder="Bank name and account" />
          </div>

          <div v-if="formData.payment_method === 'mobile_money'" class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Mobile Money Details</label>
            <input v-model="formData.mobile_money_details" type="text" class="input mt-1" placeholder="Provider and phone number" />
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea v-model="formData.notes" rows="2" class="input mt-1" placeholder="Additional payment notes"></textarea>
          </div>
        </div>

        <div v-if="formError" class="mt-4 rounded-md bg-red-50 p-4">
          <p class="text-sm text-red-800">{{ formError }}</p>
        </div>

        <template #footer>
          <div class="flex gap-3 justify-end">
            <button type="button" @click="closeModal" :disabled="submitting" class="btn">Cancel</button>
            <button type="submit" :disabled="submitting" class="btn-primary">{{ submitting ? 'Saving...' : 'Save' }}</button>
          </div>
        </template>
      </form>
    </Modal>

    <!-- Detail Modal -->
    <Modal v-model:show="showDetailModal" :title="`Payment Details - ${selectedPayment?.payment_number}`" size="md">
      <div v-if="selectedPayment" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-500">Payment Number</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedPayment.payment_number }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Status</label>
            <p class="mt-1">
              <span :class="getStatusBadgeClass(selectedPayment.status)">
                {{ selectedPayment.status }}
              </span>
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Customer</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedPayment.customer?.customer_name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Payment Date</label>
            <p class="mt-1 text-sm text-gray-900">{{ formatDate(selectedPayment.payment_date) }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Amount</label>
            <p class="mt-1 text-lg font-bold text-gray-900">{{ formatCurrency(selectedPayment.amount) }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Payment Method</label>
            <p class="mt-1">
              <span :class="getPaymentMethodBadgeClass(selectedPayment.payment_method)">
                {{ selectedPayment.payment_method.replace('_', ' ') }}
              </span>
            </p>
          </div>
          <div v-if="selectedPayment.sale" class="col-span-2">
            <label class="block text-sm font-medium text-gray-500">Related Sale</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedPayment.sale.sale_number }}</p>
          </div>
          <div v-if="selectedPayment.reference_number" class="col-span-2">
            <label class="block text-sm font-medium text-gray-500">Reference Number</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedPayment.reference_number }}</p>
          </div>
          <div v-if="selectedPayment.bank_details" class="col-span-2">
            <label class="block text-sm font-medium text-gray-500">Bank Details</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedPayment.bank_details }}</p>
          </div>
          <div v-if="selectedPayment.mobile_money_details" class="col-span-2">
            <label class="block text-sm font-medium text-gray-500">Mobile Money Details</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedPayment.mobile_money_details }}</p>
          </div>
          <div v-if="selectedPayment.notes" class="col-span-2">
            <label class="block text-sm font-medium text-gray-500">Notes</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedPayment.notes }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Recorded By</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedPayment.recorded_by?.name || '-' }}</p>
          </div>
          <div v-if="selectedPayment.confirmed_by">
            <label class="block text-sm font-medium text-gray-500">Confirmed By</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedPayment.confirmed_by?.name || '-' }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ formatDate(selectedPayment.confirmed_at) }}</p>
          </div>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { salesService, type Customer } from '@/services/sales.service'
import PageHeader from '@/components/common/PageHeader.vue'
import SimpleTable from '@/components/common/SimpleTable.vue'
import Modal from '@/components/common/Modal.vue'
import { PlusIcon } from '@heroicons/vue/24/outline'

const authStore = useAuthStore()

const loading = ref(true)
const payments = ref<any[]>([])
const customers = ref<Customer[]>([])
const customerSales = ref<any[]>([])

const filters = ref({
  fromDate: '',
  toDate: '',
  paymentMethod: ''
})

const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref<number | null>(null)
const submitting = ref(false)
const formError = ref<string | null>(null)

const showDetailModal = ref(false)
const selectedPayment = ref<any>(null)

const formData = ref({
  customer_id: undefined as number | undefined,
  sale_id: undefined as number | undefined,
  payment_date: new Date().toISOString().split('T')[0],
  amount: 0,
  payment_method: '' as any,
  reference_number: '',
  bank_details: '',
  mobile_money_details: '',
  notes: ''
})

const columns = [
  { key: 'payment_number', label: 'Payment #', cellClass: 'whitespace-nowrap text-sm font-medium text-gray-900' },
  { key: 'payment_date', label: 'Date', slot: true, cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'customer', label: 'Customer', slot: true, cellClass: 'text-sm text-gray-900' },
  { key: 'sale', label: 'Sale #', slot: true, cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'amount', label: 'Amount', slot: true, cellClass: 'whitespace-nowrap text-sm font-medium text-gray-900' },
  { key: 'payment_method', label: 'Method', slot: true, cellClass: 'whitespace-nowrap text-sm' },
  { key: 'status', label: 'Status', slot: true, cellClass: 'whitespace-nowrap' }
]

const filteredPayments = computed(() => {
  let filtered = payments.value

  if (filters.value.fromDate) {
    filtered = filtered.filter(p => p.payment_date >= filters.value.fromDate)
  }
  if (filters.value.toDate) {
    filtered = filtered.filter(p => p.payment_date <= filters.value.toDate)
  }
  if (filters.value.paymentMethod) {
    filtered = filtered.filter(p => p.payment_method === filters.value.paymentMethod)
  }

  return filtered
})

const totalPayments = computed(() => {
  return filteredPayments.value
    .filter(p => p.status === 'confirmed')
    .reduce((sum, p) => sum + p.amount, 0)
})

const cashPayments = computed(() => {
  return filteredPayments.value
    .filter(p => p.status === 'confirmed' && p.payment_method === 'cash')
    .reduce((sum, p) => sum + p.amount, 0)
})

const creditPayments = computed(() => {
  return filteredPayments.value
    .filter(p => p.status === 'confirmed' && p.payment_method === 'credit')
    .reduce((sum, p) => sum + p.amount, 0)
})

const pendingPayments = computed(() => {
  return filteredPayments.value
    .filter(p => p.status === 'pending')
    .reduce((sum, p) => sum + p.amount, 0)
})

onMounted(async () => {
  await Promise.all([loadPayments(), loadCustomers()])
})

async function loadPayments() {
  try {
    loading.value = true
    payments.value = await salesService.getPayments()
  } catch (err) {
    console.error('Failed to load payments:', err)
  } finally {
    loading.value = false
  }
}

async function loadCustomers() {
  try {
    customers.value = await salesService.getCustomers()
  } catch (err) {
    console.error('Failed to load customers:', err)
  }
}

async function onCustomerChange() {
  if (!formData.value.customer_id) {
    customerSales.value = []
    return
  }

  try {
    // Load sales with outstanding balance for selected customer
    customerSales.value = await salesService.getSales({
      customer_id: formData.value.customer_id,
      has_balance: true
    })
  } catch (err) {
    console.error('Failed to load customer sales:', err)
  }
}

function applyFilters() {
  // Filters are reactive, computed property will update automatically
}

function getStatusBadgeClass(status: string): string {
  const classes: Record<string, string> = {
    pending: 'badge-yellow',
    confirmed: 'badge-success',
    voided: 'badge-red'
  }
  return classes[status] || 'badge-gray'
}

function getPaymentMethodBadgeClass(method: string): string {
  const classes: Record<string, string> = {
    cash: 'badge-green',
    credit: 'badge-blue',
    bank_transfer: 'badge-purple',
    mobile_money: 'badge-yellow',
    check: 'badge-gray'
  }
  return classes[method] || 'badge-gray'
}

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  formData.value = {
    customer_id: undefined,
    sale_id: undefined,
    payment_date: new Date().toISOString().split('T')[0],
    amount: 0,
    payment_method: '',
    reference_number: '',
    bank_details: '',
    mobile_money_details: '',
    notes: ''
  }
  customerSales.value = []
  formError.value = null
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

async function handleSubmit() {
  try {
    submitting.value = true
    formError.value = null

    if (isEditing.value && editingId.value) {
      await salesService.updatePayment(editingId.value, formData.value)
    } else {
      await salesService.createPayment(formData.value)
    }

    closeModal()
    await loadPayments()
  } catch (err: any) {
    formError.value = err.response?.data?.message || 'Failed to save payment'
  } finally {
    submitting.value = false
  }
}

function viewDetails(payment: any) {
  selectedPayment.value = payment
  showDetailModal.value = true
}

async function confirmPayment(payment: any) {
  if (confirm(`Confirm payment ${payment.payment_number} for ${formatCurrency(payment.amount)}?`)) {
    try {
      await salesService.confirmPayment(payment.id)
      await loadPayments()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to confirm payment')
    }
  }
}

async function voidPayment(payment: any) {
  const reason = prompt(`Void payment ${payment.payment_number}? Enter reason:`)
  if (reason) {
    try {
      await salesService.voidPayment(payment.id, { reason })
      await loadPayments()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to void payment')
    }
  }
}

function formatDate(date: string | null | undefined): string {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('en-ET', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-ET', {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: 0
  }).format(amount)
}
</script>
