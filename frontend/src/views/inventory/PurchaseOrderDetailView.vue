<template>
  <div>
    <div class="mb-6 flex items-center justify-between">
      <div>
        <RouterLink to="/inventory/purchase-orders" class="text-sm text-primary-600 hover:text-primary-900">
          &larr; Back to Purchase Orders
        </RouterLink>
        <h1 class="mt-2 text-2xl font-semibold text-gray-900">
          Purchase Order {{ purchaseOrder?.po_number }}
        </h1>
      </div>
      <div class="flex gap-3">
        <button
          v-if="purchaseOrder?.status === 'pending' && authStore.isManager"
          @click="approvePO"
          class="btn-primary"
        >
          Approve Order
        </button>
        <button
          v-if="purchaseOrder?.status === 'approved' && authStore.isManager"
          @click="openReceiveModal"
          class="btn-primary"
        >
          Receive Items
        </button>
        <button
          v-if="purchaseOrder?.status === 'pending' && authStore.isOwner"
          @click="confirmCancel"
          class="btn text-red-600 hover:bg-red-50"
        >
          Cancel Order
        </button>
      </div>
    </div>

    <div v-if="loading" class="card py-12">
      <LoadingSpinner text="Loading purchase order..." />
    </div>

    <div v-else-if="purchaseOrder" class="space-y-6">
      <!-- Header Information -->
      <div class="card">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-500">PO Number</label>
            <p class="mt-1 text-sm text-gray-900">{{ purchaseOrder.po_number }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Order Date</label>
            <p class="mt-1 text-sm text-gray-900">{{ formatDate(purchaseOrder.order_date) }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Status</label>
            <p class="mt-1">
              <span :class="getStatusBadgeClass(purchaseOrder.status)">
                {{ purchaseOrder.status }}
              </span>
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Supplier</label>
            <p class="mt-1 text-sm text-gray-900">{{ purchaseOrder.supplier?.supplier_name || '-' }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ purchaseOrder.supplier?.phone || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Branch</label>
            <p class="mt-1 text-sm text-gray-900">{{ purchaseOrder.branch?.branch_name || '-' }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Expected Delivery</label>
            <p class="mt-1 text-sm text-gray-900">{{ formatDate(purchaseOrder.expected_delivery_date) }}</p>
          </div>
          <div v-if="purchaseOrder.approved_by">
            <label class="block text-sm font-medium text-gray-500">Approved By</label>
            <p class="mt-1 text-sm text-gray-900">{{ purchaseOrder.approved_by?.name || '-' }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ formatDate(purchaseOrder.approval_date) }}</p>
          </div>
          <div v-if="purchaseOrder.received_date">
            <label class="block text-sm font-medium text-gray-500">Received Date</label>
            <p class="mt-1 text-sm text-gray-900">{{ formatDate(purchaseOrder.received_date) }}</p>
          </div>
        </div>
        <div v-if="purchaseOrder.notes" class="mt-4">
          <label class="block text-sm font-medium text-gray-500">Notes</label>
          <p class="mt-1 text-sm text-gray-900">{{ purchaseOrder.notes }}</p>
        </div>
      </div>

      <!-- Line Items -->
      <div class="card">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordered Qty</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tax</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="item in purchaseOrder.items" :key="item.id">
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ item.inventory_item?.name || '-' }}</div>
                  <div class="text-xs text-gray-500">{{ item.inventory_item?.item_code || '-' }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ item.quantity_ordered }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ item.unit?.abbreviation || '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ formatCurrency(item.unit_price) }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ formatCurrency(item.subtotal) }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ formatCurrency(item.tax_amount) }}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ formatCurrency(item.total_amount) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Totals -->
        <div class="mt-6 border-t pt-4">
          <div class="flex justify-end">
            <div class="w-64 space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal:</span>
                <span class="font-medium">{{ formatCurrency(purchaseOrder.total_amount - purchaseOrder.tax_amount) }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tax:</span>
                <span class="font-medium">{{ formatCurrency(purchaseOrder.tax_amount) }}</span>
              </div>
              <div class="flex justify-between text-lg font-bold border-t pt-2">
                <span>Total:</span>
                <span class="text-primary-600">{{ formatCurrency(purchaseOrder.total_amount) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Receiving History -->
      <div v-if="receivingRecords.length > 0" class="card">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Receiving History</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity Received</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Received By</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="record in receivingRecords" :key="record.id">
                <td class="px-6 py-4 text-sm text-gray-900">{{ record.inventory_item?.name || '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ record.quantity_received }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ record.unit?.abbreviation || '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ record.received_by?.name || '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ formatDate(record.received_date) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Receive Items Modal -->
    <Modal v-model:show="showReceiveModal" title="Receive Items" size="xl">
      <form @submit.prevent="handleReceive">
        <div class="space-y-4">
          <p class="text-sm text-gray-600">Record received items for this purchase order.</p>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordered</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receiving</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, index) in receiveForm.items" :key="item.po_item_id">
                  <td class="px-4 py-3 text-sm text-gray-900">{{ item.item_name }}</td>
                  <td class="px-4 py-3 text-sm text-gray-500">{{ item.quantity_ordered }}</td>
                  <td class="px-4 py-3">
                    <input
                      v-model.number="receiveForm.items[index].quantity_received"
                      type="number"
                      step="0.01"
                      min="0"
                      :max="item.quantity_ordered"
                      class="input w-24"
                    />
                  </td>
                  <td class="px-4 py-3 text-sm text-gray-500">{{ item.unit_name }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Received Date *</label>
            <input v-model="receiveForm.received_date" type="date" required class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea v-model="receiveForm.notes" rows="3" class="input mt-1" placeholder="Any notes about the delivery..."></textarea>
          </div>
        </div>

        <div v-if="receiveError" class="mt-4 rounded-md bg-red-50 p-4">
          <p class="text-sm text-red-800">{{ receiveError }}</p>
        </div>

        <template #footer>
          <div class="flex gap-3 justify-end">
            <button type="button" @click="closeReceiveModal" :disabled="receiving" class="btn">Cancel</button>
            <button type="submit" :disabled="receiving" class="btn-primary">{{ receiving ? 'Processing...' : 'Receive Items' }}</button>
          </div>
        </template>
      </form>
    </Modal>

    <!-- Confirm Cancel -->
    <ConfirmDialog
      v-model:show="showCancelDialog"
      type="danger"
      title="Cancel Purchase Order"
      :message="`Are you sure you want to cancel purchase order ${purchaseOrder?.po_number}?`"
      :loading="cancelling"
      @confirm="handleCancel"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { inventoryService, type PurchaseOrder } from '@/services/inventory.service'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import Modal from '@/components/common/Modal.vue'
import ConfirmDialog from '@/components/common/ConfirmDialog.vue'

const route = useRoute()
const authStore = useAuthStore()

const loading = ref(true)
const purchaseOrder = ref<PurchaseOrder | null>(null)
const receivingRecords = ref<any[]>([])

const showReceiveModal = ref(false)
const receiving = ref(false)
const receiveError = ref<string | null>(null)
const receiveForm = ref({
  received_date: new Date().toISOString().split('T')[0],
  notes: '',
  items: [] as Array<{
    po_item_id: number
    inventory_item_id: number
    item_name: string
    quantity_ordered: number
    quantity_received: number
    unit_name: string
    unit_id: number
  }>
})

const showCancelDialog = ref(false)
const cancelling = ref(false)

onMounted(async () => {
  await loadPurchaseOrder()
})

async function loadPurchaseOrder() {
  try {
    loading.value = true
    const id = parseInt(route.params.id as string)
    purchaseOrder.value = await inventoryService.getPurchaseOrder(id)

    // Load receiving records if order has been received
    if (purchaseOrder.value.status === 'received' || purchaseOrder.value.status === 'partially_received') {
      try {
        receivingRecords.value = await inventoryService.getPurchaseOrderReceivingHistory(id)
      } catch (err) {
        console.error('Failed to load receiving history:', err)
      }
    }
  } catch (err) {
    console.error('Failed to load purchase order:', err)
  } finally {
    loading.value = false
  }
}

function getStatusBadgeClass(status: string): string {
  const classes: Record<string, string> = {
    pending: 'badge-yellow',
    approved: 'badge-blue',
    received: 'badge-success',
    partially_received: 'badge-purple',
    cancelled: 'badge-red'
  }
  return classes[status] || 'badge-gray'
}

async function approvePO() {
  if (!purchaseOrder.value) return

  if (confirm(`Approve purchase order ${purchaseOrder.value.po_number}?`)) {
    try {
      await inventoryService.approvePurchaseOrder(purchaseOrder.value.id)
      await loadPurchaseOrder()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to approve purchase order')
    }
  }
}

function openReceiveModal() {
  if (!purchaseOrder.value) return

  // Initialize receive form with PO items
  receiveForm.value.items = (purchaseOrder.value.items || []).map(item => ({
    po_item_id: item.id,
    inventory_item_id: item.inventory_item_id,
    item_name: item.inventory_item?.name || '',
    quantity_ordered: item.quantity_ordered,
    quantity_received: item.quantity_ordered, // Default to full quantity
    unit_name: item.unit?.abbreviation || '',
    unit_id: item.unit_id
  }))
  receiveForm.value.received_date = new Date().toISOString().split('T')[0]
  receiveForm.value.notes = ''
  receiveError.value = null
  showReceiveModal.value = true
}

function closeReceiveModal() {
  showReceiveModal.value = false
}

async function handleReceive() {
  if (!purchaseOrder.value) return

  try {
    receiving.value = true
    receiveError.value = null

    await inventoryService.receivePurchaseOrder(purchaseOrder.value.id, {
      received_date: receiveForm.value.received_date,
      notes: receiveForm.value.notes,
      items: receiveForm.value.items.map(item => ({
        po_item_id: item.po_item_id,
        inventory_item_id: item.inventory_item_id,
        quantity_received: item.quantity_received,
        unit_id: item.unit_id
      }))
    })

    closeReceiveModal()
    await loadPurchaseOrder()
  } catch (err: any) {
    receiveError.value = err.response?.data?.message || 'Failed to receive items'
  } finally {
    receiving.value = false
  }
}

function confirmCancel() {
  showCancelDialog.value = true
}

async function handleCancel() {
  if (!purchaseOrder.value) return

  try {
    cancelling.value = true
    await inventoryService.cancelPurchaseOrder(purchaseOrder.value.id)
    showCancelDialog.value = false
    await loadPurchaseOrder()
  } catch (err: any) {
    alert(err.response?.data?.message || 'Failed to cancel purchase order')
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

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-ET', {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: 2
  }).format(amount)
}
</script>
