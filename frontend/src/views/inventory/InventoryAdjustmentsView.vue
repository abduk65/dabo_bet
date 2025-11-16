<template>
  <div>
    <PageHeader title="Inventory Adjustments" description="Manage inventory adjustments and corrections">
      <template #actions>
        <button v-if="authStore.isSupervisor" @click="openCreateModal" class="btn-primary">
          <PlusIcon class="h-5 w-5 mr-2" />
          New Adjustment
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

    <!-- Adjustments Table -->
    <div class="card overflow-hidden">
      <SimpleTable
        :columns="columns"
        :data="filteredAdjustments"
        :loading="loading"
        loading-text="Loading adjustments..."
        empty-text="No adjustments found."
      >
        <template #cell-adjustment_date="{ value }">
          {{ formatDate(value) }}
        </template>

        <template #cell-inventory_item="{ row }">
          <div class="text-sm font-medium text-gray-900">{{ row.inventory_item?.name || '-' }}</div>
          <div class="text-xs text-gray-500">{{ row.inventory_item?.item_code || '-' }}</div>
        </template>

        <template #cell-adjustment_type="{ value }">
          <span :class="getTypeBadgeClass(value)">
            {{ value.replace('_', ' ') }}
          </span>
        </template>

        <template #cell-quantity="{ row }">
          <span :class="row.quantity_change >= 0 ? 'text-green-600' : 'text-red-600'">
            {{ row.quantity_change >= 0 ? '+' : '' }}{{ row.quantity_change }} {{ row.unit?.abbreviation }}
          </span>
        </template>

        <template #cell-status="{ value }">
          <span :class="getStatusBadgeClass(value)">
            {{ value }}
          </span>
        </template>

        <template #actions="{ row }">
          <button @click="viewDetails(row)" class="text-blue-600 hover:text-blue-900 mr-4">View</button>
          <button v-if="row.status === 'pending' && authStore.isManager" @click="approveAdjustment(row)" class="text-green-600 hover:text-green-900 mr-4">Approve</button>
          <button v-if="row.status === 'pending' && authStore.isManager" @click="rejectAdjustment(row)" class="text-red-600 hover:text-red-900 mr-4">Reject</button>
          <button v-if="row.status === 'pending' && authStore.isOwner" @click="confirmDelete(row)" class="text-red-600 hover:text-red-900">Delete</button>
        </template>
      </SimpleTable>
    </div>

    <!-- Create/Edit Modal -->
    <Modal v-model:show="showModal" :title="isEditing ? 'Edit Adjustment' : 'Create Adjustment'" size="lg">
      <form @submit.prevent="handleSubmit">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Inventory Item *</label>
            <select v-model="formData.inventory_item_id" required class="input mt-1" @change="onItemChange">
              <option :value="undefined">Select Item</option>
              <option v-for="item in inventoryItems" :key="item.id" :value="item.id">
                {{ item.name }} ({{ item.item_code }})
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Adjustment Type *</label>
            <select v-model="formData.adjustment_type" required class="input mt-1">
              <option value="">Select Type</option>
              <option value="physical_count">Physical Count</option>
              <option value="damage">Damage</option>
              <option value="expired">Expired</option>
              <option value="loss">Loss</option>
              <option value="found">Found</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Adjustment Date *</label>
            <input v-model="formData.adjustment_date" type="date" required class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Quantity Change *</label>
            <input v-model.number="formData.quantity_change" type="number" step="0.01" required class="input mt-1" />
            <p class="mt-1 text-xs text-gray-500">Use negative values for decreases</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Unit *</label>
            <select v-model="formData.unit_id" required class="input mt-1">
              <option :value="undefined">Select Unit</option>
              <option v-for="unit in units" :key="unit.id" :value="unit.id">
                {{ unit.unit_name }} ({{ unit.abbreviation }})
              </option>
            </select>
          </div>

          <div v-if="selectedItemStock" class="sm:col-span-2">
            <div class="rounded-md bg-blue-50 p-4">
              <div class="flex">
                <div class="flex-1">
                  <p class="text-sm font-medium text-blue-800">Current Stock Level</p>
                  <p class="mt-1 text-2xl font-bold text-blue-900">{{ selectedItemStock.current_quantity }} {{ selectedItemStock.unit?.abbreviation }}</p>
                </div>
                <div v-if="formData.quantity_change" class="flex-1">
                  <p class="text-sm font-medium text-blue-800">After Adjustment</p>
                  <p class="mt-1 text-2xl font-bold text-blue-900">
                    {{ (selectedItemStock.current_quantity + formData.quantity_change).toFixed(2) }} {{ selectedItemStock.unit?.abbreviation }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Reason *</label>
            <textarea v-model="formData.reason" rows="3" required class="input mt-1" placeholder="Explain the reason for this adjustment"></textarea>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea v-model="formData.notes" rows="2" class="input mt-1" placeholder="Additional notes (optional)"></textarea>
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
    <Modal v-model:show="showDetailModal" :title="`Adjustment Details - ${selectedAdjustment?.reference_number}`" size="md">
      <div v-if="selectedAdjustment" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-500">Reference Number</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedAdjustment.reference_number }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Status</label>
            <p class="mt-1">
              <span :class="getStatusBadgeClass(selectedAdjustment.status)">
                {{ selectedAdjustment.status }}
              </span>
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Item</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedAdjustment.inventory_item?.name }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ selectedAdjustment.inventory_item?.item_code }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Type</label>
            <p class="mt-1">
              <span :class="getTypeBadgeClass(selectedAdjustment.adjustment_type)">
                {{ selectedAdjustment.adjustment_type.replace('_', ' ') }}
              </span>
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Quantity Change</label>
            <p class="mt-1 text-sm font-semibold" :class="selectedAdjustment.quantity_change >= 0 ? 'text-green-600' : 'text-red-600'">
              {{ selectedAdjustment.quantity_change >= 0 ? '+' : '' }}{{ selectedAdjustment.quantity_change }} {{ selectedAdjustment.unit?.abbreviation }}
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Date</label>
            <p class="mt-1 text-sm text-gray-900">{{ formatDate(selectedAdjustment.adjustment_date) }}</p>
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-500">Reason</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedAdjustment.reason }}</p>
          </div>
          <div v-if="selectedAdjustment.notes" class="col-span-2">
            <label class="block text-sm font-medium text-gray-500">Notes</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedAdjustment.notes }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-500">Created By</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedAdjustment.created_by?.name || '-' }}</p>
          </div>
          <div v-if="selectedAdjustment.approved_by">
            <label class="block text-sm font-medium text-gray-500">Approved By</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedAdjustment.approved_by?.name || '-' }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ formatDate(selectedAdjustment.approved_at) }}</p>
          </div>
        </div>
      </div>
    </Modal>

    <!-- Confirm Delete -->
    <ConfirmDialog
      v-model:show="showDeleteDialog"
      type="danger"
      title="Delete Adjustment"
      :message="`Are you sure you want to delete this adjustment?`"
      :loading="deleting"
      @confirm="handleDelete"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { inventoryService, type InventoryItem } from '@/services/inventory.service'
import { referenceService, type Unit } from '@/services/reference.service'
import PageHeader from '@/components/common/PageHeader.vue'
import SimpleTable from '@/components/common/SimpleTable.vue'
import Modal from '@/components/common/Modal.vue'
import ConfirmDialog from '@/components/common/ConfirmDialog.vue'
import { PlusIcon } from '@heroicons/vue/24/outline'

const authStore = useAuthStore()

const loading = ref(true)
const adjustments = ref<any[]>([])
const inventoryItems = ref<InventoryItem[]>([])
const units = ref<Unit[]>([])
const selectedStatus = ref<string | undefined>(undefined)
const selectedItemStock = ref<any>(null)

const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref<number | null>(null)
const submitting = ref(false)
const formError = ref<string | null>(null)

const showDetailModal = ref(false)
const selectedAdjustment = ref<any>(null)

const showDeleteDialog = ref(false)
const adjustmentToDelete = ref<any>(null)
const deleting = ref(false)

const formData = ref({
  inventory_item_id: undefined as number | undefined,
  adjustment_type: '' as any,
  adjustment_date: new Date().toISOString().split('T')[0],
  quantity_change: 0,
  unit_id: undefined as number | undefined,
  reason: '',
  notes: ''
})

const statuses = [
  { value: undefined, label: 'All' },
  { value: 'pending', label: 'Pending' },
  { value: 'approved', label: 'Approved' },
  { value: 'rejected', label: 'Rejected' }
]

const columns = [
  { key: 'reference_number', label: 'Reference #', cellClass: 'whitespace-nowrap text-sm font-medium text-gray-900' },
  { key: 'adjustment_date', label: 'Date', slot: true, cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'inventory_item', label: 'Item', slot: true, cellClass: 'text-sm' },
  { key: 'adjustment_type', label: 'Type', slot: true, cellClass: 'whitespace-nowrap text-sm' },
  { key: 'quantity', label: 'Quantity Change', slot: true, cellClass: 'whitespace-nowrap text-sm font-medium' },
  { key: 'status', label: 'Status', slot: true, cellClass: 'whitespace-nowrap' }
]

const filteredAdjustments = computed(() => {
  if (!selectedStatus.value) return adjustments.value
  return adjustments.value.filter(a => a.status === selectedStatus.value)
})

onMounted(async () => {
  await Promise.all([loadAdjustments(), loadInventoryItems(), loadUnits()])
})

async function loadAdjustments() {
  try {
    loading.value = true
    adjustments.value = await inventoryService.getInventoryAdjustments()
  } catch (err) {
    console.error('Failed to load adjustments:', err)
  } finally {
    loading.value = false
  }
}

async function loadInventoryItems() {
  try {
    inventoryItems.value = await inventoryService.getInventoryItems()
  } catch (err) {
    console.error('Failed to load inventory items:', err)
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
    approved: 'badge-success',
    rejected: 'badge-red'
  }
  return classes[status] || 'badge-gray'
}

function getTypeBadgeClass(type: string): string {
  const classes: Record<string, string> = {
    physical_count: 'badge-blue',
    damage: 'badge-red',
    expired: 'badge-yellow',
    loss: 'badge-red',
    found: 'badge-green',
    other: 'badge-gray'
  }
  return classes[type] || 'badge-gray'
}

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  formData.value = {
    inventory_item_id: undefined,
    adjustment_type: '',
    adjustment_date: new Date().toISOString().split('T')[0],
    quantity_change: 0,
    unit_id: undefined,
    reason: '',
    notes: ''
  }
  selectedItemStock.value = null
  formError.value = null
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

async function onItemChange() {
  if (!formData.value.inventory_item_id) {
    selectedItemStock.value = null
    return
  }

  try {
    selectedItemStock.value = await inventoryService.getInventoryItemStockLevel(formData.value.inventory_item_id)
    // Auto-select the unit from the item if available
    if (selectedItemStock.value?.unit_id) {
      formData.value.unit_id = selectedItemStock.value.unit_id
    }
  } catch (err) {
    console.error('Failed to load stock level:', err)
  }
}

async function handleSubmit() {
  try {
    submitting.value = true
    formError.value = null

    if (isEditing.value && editingId.value) {
      await inventoryService.updateInventoryAdjustment(editingId.value, formData.value)
    } else {
      await inventoryService.createInventoryAdjustment(formData.value)
    }

    closeModal()
    await loadAdjustments()
  } catch (err: any) {
    formError.value = err.response?.data?.message || 'Failed to save adjustment'
  } finally {
    submitting.value = false
  }
}

function viewDetails(adjustment: any) {
  selectedAdjustment.value = adjustment
  showDetailModal.value = true
}

async function approveAdjustment(adjustment: any) {
  if (confirm(`Approve adjustment ${adjustment.reference_number}? This will update inventory levels.`)) {
    try {
      await inventoryService.approveInventoryAdjustment(adjustment.id)
      await loadAdjustments()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to approve adjustment')
    }
  }
}

async function rejectAdjustment(adjustment: any) {
  const reason = prompt(`Reject adjustment ${adjustment.reference_number}? Enter rejection reason:`)
  if (reason) {
    try {
      await inventoryService.rejectInventoryAdjustment(adjustment.id, { rejection_reason: reason })
      await loadAdjustments()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to reject adjustment')
    }
  }
}

function confirmDelete(adjustment: any) {
  adjustmentToDelete.value = adjustment
  showDeleteDialog.value = true
}

async function handleDelete() {
  if (!adjustmentToDelete.value) return

  try {
    deleting.value = true
    await inventoryService.deleteInventoryAdjustment(adjustmentToDelete.value.id)
    showDeleteDialog.value = false
    await loadAdjustments()
  } catch (err: any) {
    alert(err.response?.data?.message || 'Failed to delete adjustment')
  } finally {
    deleting.value = false
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
</script>
