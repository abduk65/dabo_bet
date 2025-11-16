<template>
  <div>
    <PageHeader title="Customers" description="Manage customer information and pricing">
      <template #actions>
        <button @click="openCreateModal" class="btn-primary">
          <PlusIcon class="h-5 w-5 mr-2" />
          Add Customer
        </button>
      </template>
    </PageHeader>

    <!-- Filter Tabs -->
    <div class="card mb-6">
      <div class="flex flex-wrap gap-2">
        <button
          v-for="type in customerTypes"
          :key="type.value"
          @click="selectedType = type.value"
          :class="[
            'px-4 py-2 text-sm font-medium rounded-md transition-colors',
            selectedType === type.value
              ? 'bg-primary-100 text-primary-700 ring-2 ring-primary-500'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          ]"
        >
          {{ type.label }}
        </button>
      </div>
    </div>

    <!-- Customers Table -->
    <div class="card overflow-hidden">
      <SimpleTable
        :columns="columns"
        :data="filteredCustomers"
        :loading="loading"
        loading-text="Loading customers..."
        empty-text="No customers found."
      >
        <template #cell-customer_type="{ value }">
          <span :class="getTypeBadgeClass(value)">
            {{ value.replace('_', ' ') }}
          </span>
        </template>

        <template #cell-credit_limit="{ value }">
          {{ formatCurrency(value) }}
        </template>

        <template #cell-is_active="{ value }">
          <span :class="value ? 'badge-success' : 'badge-gray'">
            {{ value ? 'Active' : 'Inactive' }}
          </span>
        </template>

        <template #actions="{ row }">
          <button @click="viewDetails(row)" class="text-blue-600 hover:text-blue-900 mr-4">View</button>
          <button @click="openEditModal(row)" class="text-primary-600 hover:text-primary-900 mr-4">Edit</button>
          <button v-if="authStore.isOwner" @click="confirmDelete(row)" class="text-red-600 hover:text-red-900">Delete</button>
        </template>
      </SimpleTable>
    </div>

    <!-- Create/Edit Modal -->
    <Modal v-model:show="showModal" :title="isEditing ? 'Edit Customer' : 'Create Customer'" size="xl">
      <form @submit.prevent="handleSubmit">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <label class="block text-sm font-medium text-gray-700">Customer Code *</label>
            <input v-model="formData.customer_code" type="text" required class="input mt-1" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Customer Name *</label>
            <input v-model="formData.customer_name" type="text" required class="input mt-1" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Type *</label>
            <select v-model="formData.customer_type" required class="input mt-1">
              <option value="">Select Type</option>
              <option value="walk_in">Walk-in</option>
              <option value="retail">Retail</option>
              <option value="wholesale">Wholesale</option>
              <option value="distributor">Distributor</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Phone</label>
            <input v-model="formData.phone" type="tel" class="input mt-1" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input v-model="formData.email" type="email" class="input mt-1" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Credit Limit *</label>
            <input v-model.number="formData.credit_limit" type="number" step="0.01" required class="input mt-1" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Payment Terms (Days) *</label>
            <input v-model.number="formData.payment_terms_days" type="number" required class="input mt-1" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Commission % *</label>
            <input v-model.number="formData.commission_percentage" type="number" step="0.01" required class="input mt-1" />
          </div>
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <textarea v-model="formData.address" rows="2" class="input mt-1"></textarea>
          </div>
          <div class="flex items-center">
            <input v-model="formData.receives_commission" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" />
            <label class="ml-2 block text-sm text-gray-900">Receives Commission</label>
          </div>
          <div class="flex items-center">
            <input v-model="formData.is_active" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" />
            <label class="ml-2 block text-sm text-gray-900">Active</label>
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

    <!-- Confirm Delete -->
    <ConfirmDialog
      v-model:show="showDeleteDialog"
      type="danger"
      title="Delete Customer"
      :message="`Are you sure you want to delete '${customerToDelete?.customer_name}'?`"
      :loading="deleting"
      @confirm="handleDelete"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { salesService, type Customer } from '@/services/sales.service'
import PageHeader from '@/components/common/PageHeader.vue'
import SimpleTable from '@/components/common/SimpleTable.vue'
import Modal from '@/components/common/Modal.vue'
import ConfirmDialog from '@/components/common/ConfirmDialog.vue'
import { PlusIcon } from '@heroicons/vue/24/outline'

const authStore = useAuthStore()
const loading = ref(true)
const customers = ref<Customer[]>([])
const selectedType = ref<string | undefined>(undefined)

const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref<number | null>(null)
const submitting = ref(false)
const formError = ref<string | null>(null)

const showDeleteDialog = ref(false)
const customerToDelete = ref<Customer | null>(null)
const deleting = ref(false)

const formData = ref({
  customer_code: '',
  customer_name: '',
  customer_type: '' as any,
  phone: '',
  email: '',
  address: '',
  credit_limit: 0,
  payment_terms_days: 30,
  commission_percentage: 0,
  receives_commission: false,
  is_active: true
})

const customerTypes = [
  { value: undefined, label: 'All Customers' },
  { value: 'walk_in', label: 'Walk-in' },
  { value: 'retail', label: 'Retail' },
  { value: 'wholesale', label: 'Wholesale' },
  { value: 'distributor', label: 'Distributor' }
]

const columns = [
  { key: 'customer_code', label: 'Code', cellClass: 'whitespace-nowrap text-sm font-medium text-gray-900' },
  { key: 'customer_name', label: 'Name', cellClass: 'whitespace-nowrap text-sm text-gray-900' },
  { key: 'customer_type', label: 'Type', slot: true },
  { key: 'phone', label: 'Phone', cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'credit_limit', label: 'Credit Limit', slot: true, cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'is_active', label: 'Status', slot: true }
]

const filteredCustomers = computed(() => {
  if (!selectedType.value) return customers.value
  return customers.value.filter(c => c.customer_type === selectedType.value)
})

onMounted(() => loadCustomers())

async function loadCustomers() {
  try {
    loading.value = true
    customers.value = await salesService.getCustomers()
  } catch (err) {
    console.error('Failed to load customers:', err)
  } finally {
    loading.value = false
  }
}

function getTypeBadgeClass(type: string): string {
  const classes: Record<string, string> = {
    walk_in: 'badge-gray',
    retail: 'badge-blue',
    wholesale: 'badge-green',
    distributor: 'badge-purple'
  }
  return classes[type] || 'badge-gray'
}

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  formData.value = {
    customer_code: '',
    customer_name: '',
    customer_type: '',
    phone: '',
    email: '',
    address: '',
    credit_limit: 0,
    payment_terms_days: 30,
    commission_percentage: 0,
    receives_commission: false,
    is_active: true
  }
  formError.value = null
  showModal.value = true
}

function openEditModal(customer: Customer) {
  isEditing.value = true
  editingId.value = customer.id
  formData.value = {
    customer_code: customer.customer_code,
    customer_name: customer.customer_name,
    customer_type: customer.customer_type,
    phone: customer.phone || '',
    email: customer.email || '',
    address: customer.address || '',
    credit_limit: customer.credit_limit,
    payment_terms_days: customer.payment_terms_days,
    commission_percentage: customer.commission_percentage,
    receives_commission: customer.receives_commission,
    is_active: customer.is_active
  }
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
      await salesService.updateCustomer(editingId.value, formData.value)
    } else {
      await salesService.createCustomer(formData.value)
    }

    closeModal()
    await loadCustomers()
  } catch (err: any) {
    formError.value = err.response?.data?.message || 'Failed to save customer'
  } finally {
    submitting.value = false
  }
}

function viewDetails(customer: Customer) {
  // Navigate to customer detail view (to be implemented)
  console.log('View customer:', customer)
}

function confirmDelete(customer: Customer) {
  customerToDelete.value = customer
  showDeleteDialog.value = true
}

async function handleDelete() {
  if (!customerToDelete.value) return

  try {
    deleting.value = true
    await salesService.deleteCustomer(customerToDelete.value.id)
    showDeleteDialog.value = false
    await loadCustomers()
  } catch (err: any) {
    alert(err.response?.data?.message || 'Failed to delete customer')
  } finally {
    deleting.value = false
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
