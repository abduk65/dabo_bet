<template>
  <div>
    <PageHeader title="Dispatches" description="Manage product deliveries and dispatch orders">
      <template #actions>
        <button v-if="authStore.isSupervisor" @click="openCreateModal" class="btn-primary">
          <PlusIcon class="h-5 w-5 mr-2" />
          New Dispatch
        </button>
      </template>
    </PageHeader>

    <!-- Status Filter -->
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

    <!-- Dispatches Table -->
    <div class="card overflow-hidden">
      <SimpleTable
        :columns="columns"
        :data="filteredDispatches"
        :loading="loading"
        loading-text="Loading dispatches..."
        empty-text="No dispatches found."
      >
        <template #cell-customer="{ row }">
          {{ row.customer?.customer_name || '-' }}
        </template>

        <template #cell-dispatch_date="{ value }">
          {{ formatDate(value) }}
        </template>

        <template #cell-total_items="{ row }">
          {{ row.items?.length || 0 }} items
        </template>

        <template #cell-status="{ value }">
          <span :class="getStatusBadgeClass(value)">
            {{ value }}
          </span>
        </template>

        <template #actions="{ row }">
          <button @click="viewDetails(row)" class="text-blue-600 hover:text-blue-900 mr-4">View</button>
          <button v-if="row.status === 'pending' && authStore.isSupervisor" @click="dispatchOrder(row)" class="text-green-600 hover:text-green-900 mr-4">Dispatch</button>
          <button v-if="row.status === 'in_transit'" @click="confirmDelivery(row)" class="text-purple-600 hover:text-purple-900 mr-4">Confirm Delivery</button>
          <button v-if="row.status === 'pending' && authStore.isOwner" @click="confirmCancel(row)" class="text-red-600 hover:text-red-900">Cancel</button>
        </template>
      </SimpleTable>
    </div>

    <!-- Create/Edit Modal -->
    <Modal v-model:show="showModal" :title="isEditing ? 'Edit Dispatch' : 'Create Dispatch'" size="xl">
      <form @submit.prevent="handleSubmit">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <label class="block text-sm font-medium text-gray-700">Customer *</label>
            <select v-model="formData.customer_id" required class="input mt-1">
              <option :value="undefined">Select Customer</option>
              <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                {{ customer.customer_name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Dispatch Date *</label>
            <input v-model="formData.dispatch_date" type="date" required class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Delivery Address *</label>
            <textarea v-model="formData.delivery_address" rows="2" required class="input mt-1"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Driver Name</label>
            <input v-model="formData.driver_name" type="text" class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Vehicle Number</label>
            <input v-model="formData.vehicle_number" type="text" class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Driver Phone</label>
            <input v-model="formData.driver_phone" type="tel" class="input mt-1" />
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Dispatch Items</label>

            <!-- Add Item Button -->
            <button type="button" @click="addItem" class="btn-sm mb-4">
              <PlusIcon class="h-4 w-4 mr-1" />
              Add Item
            </button>

            <!-- Items Table -->
            <div v-if="formData.items.length > 0" class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Product</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Quantity</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Unit</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Action</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="(item, index) in formData.items" :key="index">
                    <td class="px-4 py-2">
                      <select v-model="item.product_id" required class="input-sm">
                        <option :value="undefined">Select Product</option>
                        <option v-for="product in products" :key="product.id" :value="product.id">
                          {{ product.product_name }}
                        </option>
                      </select>
                    </td>
                    <td class="px-4 py-2">
                      <input v-model.number="item.quantity" type="number" step="0.01" required class="input-sm w-20" />
                    </td>
                    <td class="px-4 py-2">
                      <select v-model="item.unit_id" required class="input-sm">
                        <option :value="undefined">Unit</option>
                        <option v-for="unit in units" :key="unit.id" :value="unit.id">
                          {{ unit.abbreviation }}
                        </option>
                      </select>
                    </td>
                    <td class="px-4 py-2">
                      <button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-900">
                        Remove
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p v-else class="text-sm text-gray-500 italic">No items added yet</p>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea v-model="formData.notes" rows="2" class="input mt-1" placeholder="Delivery instructions or special notes"></textarea>
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
    <Modal v-model:show="showDetailModal" :title="`Dispatch Details - ${selectedDispatch?.dispatch_number}`" size="lg">
      <div v-if="selectedDispatch" class="space-y-6">
        <!-- Header Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-500">Dispatch Number</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedDispatch.dispatch_number }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Status</label>
            <p class="mt-1">
              <span :class="getStatusBadgeClass(selectedDispatch.status)">
                {{ selectedDispatch.status }}
              </span>
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Customer</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedDispatch.customer?.customer_name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Dispatch Date</label>
            <p class="mt-1 text-sm text-gray-900">{{ formatDate(selectedDispatch.dispatch_date) }}</p>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-500">Delivery Address</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedDispatch.delivery_address }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Driver</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedDispatch.driver_name || '-' }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ selectedDispatch.driver_phone || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Vehicle</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedDispatch.vehicle_number || '-' }}</p>
          </div>
          <div v-if="selectedDispatch.actual_dispatch_time">
            <label class="block text-sm font-medium text-gray-500">Dispatched At</label>
            <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedDispatch.actual_dispatch_time) }}</p>
          </div>
          <div v-if="selectedDispatch.delivered_at">
            <label class="block text-sm font-medium text-gray-500">Delivered At</label>
            <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedDispatch.delivered_at) }}</p>
            <p class="mt-1 text-xs text-gray-500">Received by: {{ selectedDispatch.received_by || '-' }}</p>
          </div>
        </div>

        <!-- Items -->
        <div>
          <h3 class="text-sm font-medium text-gray-900 mb-3">Dispatch Items</h3>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Product</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Quantity</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Unit</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="item in selectedDispatch.items" :key="item.id">
                  <td class="px-4 py-2 text-sm text-gray-900">{{ item.product?.product_name }}</td>
                  <td class="px-4 py-2 text-sm text-gray-900">{{ item.quantity }}</td>
                  <td class="px-4 py-2 text-sm text-gray-500">{{ item.unit?.abbreviation }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Notes -->
        <div v-if="selectedDispatch.notes">
          <label class="block text-sm font-medium text-gray-500">Notes</label>
          <p class="mt-1 text-sm text-gray-900">{{ selectedDispatch.notes }}</p>
        </div>
      </div>
    </Modal>

    <!-- Confirm Delivery Modal -->
    <Modal v-model:show="showDeliveryModal" title="Confirm Delivery" size="md">
      <form @submit.prevent="handleConfirmDelivery">
        <div class="space-y-4">
          <p class="text-sm text-gray-600">Confirm that dispatch {{ selectedDispatch?.dispatch_number }} has been delivered.</p>

          <div>
            <label class="block text-sm font-medium text-gray-700">Delivery Date & Time *</label>
            <input v-model="deliveryForm.delivered_at" type="datetime-local" required class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Received By *</label>
            <input v-model="deliveryForm.received_by" type="text" required class="input mt-1" placeholder="Name of person who received" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Delivery Notes</label>
            <textarea v-model="deliveryForm.notes" rows="2" class="input mt-1" placeholder="Any notes about the delivery"></textarea>
          </div>
        </div>

        <template #footer>
          <div class="flex gap-3 justify-end">
            <button type="button" @click="showDeliveryModal = false" :disabled="confirmingDelivery" class="btn">Cancel</button>
            <button type="submit" :disabled="confirmingDelivery" class="btn-primary">{{ confirmingDelivery ? 'Confirming...' : 'Confirm Delivery' }}</button>
          </div>
        </template>
      </form>
    </Modal>

    <!-- Confirm Cancel -->
    <ConfirmDialog
      v-model:show="showCancelDialog"
      type="danger"
      title="Cancel Dispatch"
      :message="`Are you sure you want to cancel dispatch ${dispatchToCancel?.dispatch_number}?`"
      :loading="cancelling"
      @confirm="handleCancel"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { salesService, type Customer } from '@/services/sales.service'
import { productionService, type Product } from '@/services/production.service'
import { referenceService, type Unit } from '@/services/reference.service'
import PageHeader from '@/components/common/PageHeader.vue'
import SimpleTable from '@/components/common/SimpleTable.vue'
import Modal from '@/components/common/Modal.vue'
import ConfirmDialog from '@/components/common/ConfirmDialog.vue'
import { PlusIcon } from '@heroicons/vue/24/outline'

const authStore = useAuthStore()

const loading = ref(true)
const dispatches = ref<any[]>([])
const customers = ref<Customer[]>([])
const products = ref<Product[]>([])
const units = ref<Unit[]>([])
const selectedStatus = ref<string | undefined>(undefined)

const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref<number | null>(null)
const submitting = ref(false)
const formError = ref<string | null>(null)

const showDetailModal = ref(false)
const selectedDispatch = ref<any>(null)

const showDeliveryModal = ref(false)
const confirmingDelivery = ref(false)
const deliveryForm = ref({
  delivered_at: '',
  received_by: '',
  notes: ''
})

const showCancelDialog = ref(false)
const dispatchToCancel = ref<any>(null)
const cancelling = ref(false)

const formData = ref({
  customer_id: undefined as number | undefined,
  dispatch_date: new Date().toISOString().split('T')[0],
  delivery_address: '',
  driver_name: '',
  vehicle_number: '',
  driver_phone: '',
  notes: '',
  items: [] as Array<{
    product_id: number | undefined
    quantity: number
    unit_id: number | undefined
  }>
})

const statuses = [
  { value: undefined, label: 'All' },
  { value: 'pending', label: 'Pending' },
  { value: 'in_transit', label: 'In Transit' },
  { value: 'delivered', label: 'Delivered' },
  { value: 'cancelled', label: 'Cancelled' }
]

const columns = [
  { key: 'dispatch_number', label: 'Dispatch #', cellClass: 'whitespace-nowrap text-sm font-medium text-gray-900' },
  { key: 'dispatch_date', label: 'Date', slot: true, cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'customer', label: 'Customer', slot: true, cellClass: 'text-sm text-gray-900' },
  { key: 'delivery_address', label: 'Delivery Address', cellClass: 'text-sm text-gray-500' },
  { key: 'total_items', label: 'Items', slot: true, cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'status', label: 'Status', slot: true, cellClass: 'whitespace-nowrap' }
]

const filteredDispatches = computed(() => {
  if (!selectedStatus.value) return dispatches.value
  return dispatches.value.filter(d => d.status === selectedStatus.value)
})

onMounted(async () => {
  await Promise.all([loadDispatches(), loadCustomers(), loadProducts(), loadUnits()])
})

async function loadDispatches() {
  try {
    loading.value = true
    dispatches.value = await salesService.getDispatches()
  } catch (err) {
    console.error('Failed to load dispatches:', err)
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

async function loadProducts() {
  try {
    products.value = await productionService.getProducts()
  } catch (err) {
    console.error('Failed to load products:', err)
  }
}

async function loadUnits() {
  try {
    units.value = await referenceService.getUnits()
  } catch (err) {
    console.error('Failed to load units:', err)
  }
}

function getStatusBadgeClass(status: string): string {
  const classes: Record<string, string> = {
    pending: 'badge-yellow',
    in_transit: 'badge-blue',
    delivered: 'badge-success',
    cancelled: 'badge-red'
  }
  return classes[status] || 'badge-gray'
}

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  formData.value = {
    customer_id: undefined,
    dispatch_date: new Date().toISOString().split('T')[0],
    delivery_address: '',
    driver_name: '',
    vehicle_number: '',
    driver_phone: '',
    notes: '',
    items: []
  }
  formError.value = null
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

function addItem() {
  formData.value.items.push({
    product_id: undefined,
    quantity: 0,
    unit_id: undefined
  })
}

function removeItem(index: number) {
  formData.value.items.splice(index, 1)
}

async function handleSubmit() {
  try {
    submitting.value = true
    formError.value = null

    if (formData.value.items.length === 0) {
      formError.value = 'Please add at least one item to the dispatch'
      return
    }

    if (isEditing.value && editingId.value) {
      await salesService.updateDispatch(editingId.value, formData.value)
    } else {
      await salesService.createDispatch(formData.value)
    }

    closeModal()
    await loadDispatches()
  } catch (err: any) {
    formError.value = err.response?.data?.message || 'Failed to save dispatch'
  } finally {
    submitting.value = false
  }
}

function viewDetails(dispatch: any) {
  selectedDispatch.value = dispatch
  showDetailModal.value = true
}

async function dispatchOrder(dispatch: any) {
  if (confirm(`Mark dispatch ${dispatch.dispatch_number} as in transit?`)) {
    try {
      await salesService.dispatchOrder(dispatch.id)
      await loadDispatches()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to dispatch order')
    }
  }
}

function confirmDelivery(dispatch: any) {
  selectedDispatch.value = dispatch
  deliveryForm.value = {
    delivered_at: new Date().toISOString().slice(0, 16),
    received_by: '',
    notes: ''
  }
  showDeliveryModal.value = true
}

async function handleConfirmDelivery() {
  if (!selectedDispatch.value) return

  try {
    confirmingDelivery.value = true
    await salesService.confirmDelivery(selectedDispatch.value.id, deliveryForm.value)
    showDeliveryModal.value = false
    await loadDispatches()
  } catch (err: any) {
    alert(err.response?.data?.message || 'Failed to confirm delivery')
  } finally {
    confirmingDelivery.value = false
  }
}

function confirmCancel(dispatch: any) {
  dispatchToCancel.value = dispatch
  showCancelDialog.value = true
}

async function handleCancel() {
  if (!dispatchToCancel.value) return

  try {
    cancelling.value = true
    await salesService.cancelDispatch(dispatchToCancel.value.id)
    showCancelDialog.value = false
    await loadDispatches()
  } catch (err: any) {
    alert(err.response?.data?.message || 'Failed to cancel dispatch')
  } finally {
    cancelling.value = false
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

function formatDateTime(dateTime: string | null | undefined): string {
  if (!dateTime) return '-'
  return new Date(dateTime).toLocaleString('en-ET', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<style scoped>
.input-sm {
  @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm;
}

.btn-sm {
  @apply inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500;
}
</style>
