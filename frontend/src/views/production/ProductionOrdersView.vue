<template>
  <div>
    <PageHeader title="Production Orders" description="Manage production planning and execution">
      <template #actions>
        <button v-if="authStore.isManager" @click="openCreateModal" class="btn-primary">
          <PlusIcon class="h-5 w-5 mr-2" />
          New Production Order
        </button>
      </template>
    </PageHeader>

    <!-- Status Filter --><div class="card mb-6">
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

    <!-- Production Orders Table -->
    <div class="card overflow-hidden">
      <SimpleTable
        :columns="columns"
        :data="filteredOrders"
        :loading="loading"
        loading-text="Loading production orders..."
        empty-text="No production orders found."
      >
        <template #cell-product_name="{ row }">
          {{ row.recipe?.product?.product_name || '-' }}
        </template>

        <template #cell-batch_info="{ row }">
          {{ row.planned_quantity }} {{ row.recipe?.unit?.abbreviation }}
        </template>

        <template #cell-production_date="{ value }">
          {{ value ? new Date(value).toLocaleDateString() : '-' }}
        </template>

        <template #cell-status="{ value }">
          <span :class="getStatusBadgeClass(value)">
            {{ value }}
          </span>
        </template>

        <template #actions="{ row }">
          <button v-if="row.status === 'planned' && authStore.isManager" @click="startProduction(row)" class="text-green-600 hover:text-green-900 mr-4">Start</button>
          <button v-if="row.status === 'in_progress'" @click="viewConsumption(row)" class="text-blue-600 hover:text-blue-900 mr-4">Consumption</button>
          <button v-if="row.status === 'in_progress'" @click="viewOutput(row)" class="text-purple-600 hover:text-purple-900 mr-4">Output</button>
          <button v-if="row.status === 'in_progress' && authStore.isManager" @click="completeProduction(row)" class="text-green-600 hover:text-green-900 mr-4">Complete</button>
          <button v-if="authStore.isManager" @click="openEditModal(row)" class="text-primary-600 hover:text-primary-900 mr-4">Edit</button>
          <button v-if="row.status === 'planned' && authStore.isOwner" @click="confirmDelete(row)" class="text-red-600 hover:text-red-900">Cancel</button>
        </template>
      </SimpleTable>
    </div>

    <!-- Create/Edit Modal -->
    <Modal v-model:show="showModal" :title="isEditing ? 'Edit Production Order' : 'Create Production Order'" size="lg">
      <form @submit.prevent="handleSubmit">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Recipe *</label>
            <select v-model="formData.recipe_id" required class="input mt-1" @change="onRecipeChange">
              <option :value="undefined">Select Recipe</option>
              <option v-for="recipe in recipes" :key="recipe.id" :value="recipe.id">
                {{ recipe.product?.product_name }} - {{ recipe.recipe_version }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Production Date *</label>
            <input v-model="formData.production_date" type="date" required class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Branch *</label>
            <select v-model="formData.branch_id" required class="input mt-1">
              <option :value="undefined">Select Branch</option>
              <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                {{ branch.branch_name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Planned Quantity *</label>
            <input v-model.number="formData.planned_quantity" type="number" step="0.01" required class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Planned Start Time</label>
            <input v-model="formData.planned_start_time" type="time" class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Planned End Time</label>
            <input v-model="formData.planned_end_time" type="time" class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Assigned Supervisor</label>
            <select v-model="formData.supervisor_id" class="input mt-1">
              <option :value="undefined">None</option>
              <option v-for="user in supervisors" :key="user.id" :value="user.id">
                {{ user.name }}
              </option>
            </select>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea v-model="formData.notes" rows="3" class="input mt-1" placeholder="Production notes or special instructions"></textarea>
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

    <!-- Consumption Modal -->
    <Modal v-model:show="showConsumptionModal" :title="`Material Consumption - Order #${selectedOrder?.order_number}`" size="lg">
      <div v-if="loadingConsumption" class="py-12">
        <LoadingSpinner text="Loading consumption records..." />
      </div>
      <div v-else>
        <!-- Add Consumption Button -->
        <div v-if="selectedOrder?.status === 'in_progress' && authStore.isManager" class="mb-4">
          <button @click="openAddConsumptionForm" class="btn-primary">
            <PlusIcon class="h-5 w-5 mr-2" />
            Record Consumption
          </button>
        </div>

        <!-- Consumption Records Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity Used</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recorded By</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="consumptionRecords.length === 0">
                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No consumption recorded yet</td>
              </tr>
              <tr v-for="record in consumptionRecords" :key="record.id">
                <td class="px-6 py-4 text-sm text-gray-900">
                  {{ record.inventory_item?.name || '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ record.quantity_used }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ record.unit?.abbreviation || '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ record.recorded_by?.name || '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ formatDateTime(record.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Add Consumption Form -->
        <div v-if="showAddConsumptionForm" class="mt-6 border-t pt-6">
          <h4 class="text-sm font-medium text-gray-900 mb-4">Record New Consumption</h4>
          <form @submit.prevent="recordConsumption" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
              <label class="block text-sm font-medium text-gray-700">Item *</label>
              <select v-model="consumptionForm.inventory_item_id" required class="input mt-1">
                <option :value="undefined">Select Item</option>
                <option v-for="item in inventoryItems" :key="item.id" :value="item.id">
                  {{ item.name }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Quantity Used *</label>
              <input v-model.number="consumptionForm.quantity_used" type="number" step="0.01" required class="input mt-1" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Unit *</label>
              <select v-model="consumptionForm.unit_id" required class="input mt-1">
                <option :value="undefined">Select Unit</option>
                <option v-for="unit in units" :key="unit.id" :value="unit.id">
                  {{ unit.unit_name }} ({{ unit.abbreviation }})
                </option>
              </select>
            </div>
            <div class="sm:col-span-3 flex gap-2">
              <button type="submit" :disabled="recordingConsumption" class="btn-primary">
                {{ recordingConsumption ? 'Recording...' : 'Record' }}
              </button>
              <button type="button" @click="cancelAddConsumption" class="btn">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </Modal>

    <!-- Output Modal -->
    <Modal v-model:show="showOutputModal" :title="`Production Output - Order #${selectedOrder?.order_number}`" size="lg">
      <div v-if="loadingOutput" class="py-12">
        <LoadingSpinner text="Loading output records..." />
      </div>
      <div v-else>
        <!-- Add Output Button -->
        <div v-if="selectedOrder?.status === 'in_progress' && authStore.isManager" class="mb-4">
          <button @click="openAddOutputForm" class="btn-primary">
            <PlusIcon class="h-5 w-5 mr-2" />
            Record Output
          </button>
        </div>

        <!-- Output Records Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity Produced</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quality Grade</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recorded By</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="outputRecords.length === 0">
                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No output recorded yet</td>
              </tr>
              <tr v-for="record in outputRecords" :key="record.id">
                <td class="px-6 py-4 text-sm text-gray-900">{{ record.quantity_produced }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ record.unit?.abbreviation || '-' }}</td>
                <td class="px-6 py-4 text-sm">
                  <span :class="getQualityBadgeClass(record.quality_grade)">
                    {{ record.quality_grade || 'standard' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ record.recorded_by?.name || '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ formatDateTime(record.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Summary -->
        <div class="mt-6 grid grid-cols-2 gap-4">
          <div class="card bg-blue-50">
            <p class="text-sm text-gray-600">Total Output</p>
            <p class="text-2xl font-bold text-blue-600">{{ totalOutput }} {{ selectedOrder?.recipe?.unit?.abbreviation }}</p>
          </div>
          <div class="card bg-green-50">
            <p class="text-sm text-gray-600">Yield %</p>
            <p class="text-2xl font-bold text-green-600">{{ yieldPercentage }}%</p>
          </div>
        </div>

        <!-- Add Output Form -->
        <div v-if="showAddOutputForm" class="mt-6 border-t pt-6">
          <h4 class="text-sm font-medium text-gray-900 mb-4">Record New Output</h4>
          <form @submit.prevent="recordOutput" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
              <label class="block text-sm font-medium text-gray-700">Quantity Produced *</label>
              <input v-model.number="outputForm.quantity_produced" type="number" step="0.01" required class="input mt-1" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Unit *</label>
              <select v-model="outputForm.unit_id" required class="input mt-1">
                <option :value="undefined">Select Unit</option>
                <option v-for="unit in units" :key="unit.id" :value="unit.id">
                  {{ unit.unit_name }} ({{ unit.abbreviation }})
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Quality Grade</label>
              <select v-model="outputForm.quality_grade" class="input mt-1">
                <option value="premium">Premium</option>
                <option value="standard">Standard</option>
                <option value="seconds">Seconds</option>
              </select>
            </div>
            <div class="sm:col-span-3">
              <label class="block text-sm font-medium text-gray-700">Notes</label>
              <textarea v-model="outputForm.notes" rows="2" class="input mt-1"></textarea>
            </div>
            <div class="sm:col-span-3 flex gap-2">
              <button type="submit" :disabled="recordingOutput" class="btn-primary">
                {{ recordingOutput ? 'Recording...' : 'Record' }}
              </button>
              <button type="button" @click="cancelAddOutput" class="btn">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </Modal>

    <!-- Confirm Delete -->
    <ConfirmDialog
      v-model:show="showDeleteDialog"
      type="danger"
      title="Cancel Production Order"
      :message="`Are you sure you want to cancel this production order?`"
      :loading="deleting"
      @confirm="handleDelete"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { productionService, type ProductionOrder, type Recipe } from '@/services/production.service'
import { referenceService, type Unit, type Branch } from '@/services/reference.service'
import { inventoryService, type InventoryItem } from '@/services/inventory.service'
import PageHeader from '@/components/common/PageHeader.vue'
import SimpleTable from '@/components/common/SimpleTable.vue'
import Modal from '@/components/common/Modal.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import ConfirmDialog from '@/components/common/ConfirmDialog.vue'
import { PlusIcon } from '@heroicons/vue/24/outline'

const authStore = useAuthStore()

const loading = ref(true)
const productionOrders = ref<ProductionOrder[]>([])
const recipes = ref<Recipe[]>([])
const branches = ref<Branch[]>([])
const supervisors = ref<any[]>([])
const units = ref<Unit[]>([])
const inventoryItems = ref<InventoryItem[]>([])
const selectedStatus = ref<string | undefined>(undefined)

const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref<number | null>(null)
const submitting = ref(false)
const formError = ref<string | null>(null)

const showConsumptionModal = ref(false)
const loadingConsumption = ref(false)
const selectedOrder = ref<ProductionOrder | null>(null)
const consumptionRecords = ref<any[]>([])
const showAddConsumptionForm = ref(false)
const recordingConsumption = ref(false)

const showOutputModal = ref(false)
const loadingOutput = ref(false)
const outputRecords = ref<any[]>([])
const showAddOutputForm = ref(false)
const recordingOutput = ref(false)

const showDeleteDialog = ref(false)
const orderToDelete = ref<ProductionOrder | null>(null)
const deleting = ref(false)

const formData = ref({
  recipe_id: undefined as number | undefined,
  production_date: new Date().toISOString().split('T')[0],
  branch_id: undefined as number | undefined,
  planned_quantity: 0,
  planned_start_time: undefined as string | undefined,
  planned_end_time: undefined as string | undefined,
  supervisor_id: undefined as number | undefined,
  notes: ''
})

const consumptionForm = ref({
  inventory_item_id: undefined as number | undefined,
  quantity_used: 0,
  unit_id: undefined as number | undefined
})

const outputForm = ref({
  quantity_produced: 0,
  unit_id: undefined as number | undefined,
  quality_grade: 'standard',
  notes: ''
})

const statuses = [
  { value: undefined, label: 'All' },
  { value: 'planned', label: 'Planned' },
  { value: 'in_progress', label: 'In Progress' },
  { value: 'completed', label: 'Completed' },
  { value: 'cancelled', label: 'Cancelled' }
]

const columns = [
  { key: 'order_number', label: 'Order #', cellClass: 'whitespace-nowrap text-sm font-medium text-gray-900' },
  { key: 'product_name', label: 'Product', slot: true, cellClass: 'text-sm text-gray-900' },
  { key: 'batch_info', label: 'Planned Qty', slot: true, cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'production_date', label: 'Date', slot: true, cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'status', label: 'Status', slot: true, cellClass: 'whitespace-nowrap' }
]

const filteredOrders = computed(() => {
  if (!selectedStatus.value) return productionOrders.value
  return productionOrders.value.filter(o => o.status === selectedStatus.value)
})

const totalOutput = computed(() => {
  return outputRecords.value.reduce((sum, record) => sum + (record.quantity_produced || 0), 0)
})

const yieldPercentage = computed(() => {
  if (!selectedOrder.value?.planned_quantity || totalOutput.value === 0) return 0
  return ((totalOutput.value / selectedOrder.value.planned_quantity) * 100).toFixed(1)
})

onMounted(async () => {
  await Promise.all([
    loadProductionOrders(),
    loadRecipes(),
    loadBranches(),
    loadUnits(),
    loadInventoryItems()
  ])
})

async function loadProductionOrders() {
  try {
    loading.value = true
    productionOrders.value = await productionService.getProductionOrders()
  } catch (err) {
    console.error('Failed to load production orders:', err)
  } finally {
    loading.value = false
  }
}

async function loadRecipes() {
  try {
    recipes.value = await productionService.getRecipes()
  } catch (err) {
    console.error('Failed to load recipes:', err)
  }
}

async function loadBranches() {
  try {
    branches.value = await referenceService.getBranches()
  } catch (err) {
    console.error('Failed to load branches:', err)
  }
}

async function loadUnits() {
  try {
    units.value = await referenceService.getUnits()
  } catch (err) {
    console.error('Failed to load units:', err)
  }
}

async function loadInventoryItems() {
  try {
    inventoryItems.value = await inventoryService.getInventoryItems()
  } catch (err) {
    console.error('Failed to load inventory items:', err)
  }
}

function getStatusBadgeClass(status: string): string {
  const classes: Record<string, string> = {
    planned: 'badge-blue',
    in_progress: 'badge-yellow',
    completed: 'badge-success',
    cancelled: 'badge-red'
  }
  return classes[status] || 'badge-gray'
}

function getQualityBadgeClass(grade: string): string {
  const classes: Record<string, string> = {
    premium: 'badge-success',
    standard: 'badge-blue',
    seconds: 'badge-yellow'
  }
  return classes[grade] || 'badge-gray'
}

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  formData.value = {
    recipe_id: undefined,
    production_date: new Date().toISOString().split('T')[0],
    branch_id: authStore.user?.branch_id,
    planned_quantity: 0,
    planned_start_time: undefined,
    planned_end_time: undefined,
    supervisor_id: undefined,
    notes: ''
  }
  formError.value = null
  showModal.value = true
}

function openEditModal(order: ProductionOrder) {
  isEditing.value = true
  editingId.value = order.id
  formData.value = {
    recipe_id: order.recipe_id,
    production_date: order.production_date,
    branch_id: order.branch_id,
    planned_quantity: order.planned_quantity,
    planned_start_time: order.planned_start_time,
    planned_end_time: order.planned_end_time,
    supervisor_id: order.supervisor_id,
    notes: order.notes || ''
  }
  formError.value = null
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

function onRecipeChange() {
  const selectedRecipe = recipes.value.find(r => r.id === formData.value.recipe_id)
  if (selectedRecipe) {
    formData.value.planned_quantity = selectedRecipe.batch_size
  }
}

async function handleSubmit() {
  try {
    submitting.value = true
    formError.value = null

    if (isEditing.value && editingId.value) {
      await productionService.updateProductionOrder(editingId.value, formData.value)
    } else {
      await productionService.createProductionOrder(formData.value)
    }

    closeModal()
    await loadProductionOrders()
  } catch (err: any) {
    formError.value = err.response?.data?.message || 'Failed to save production order'
  } finally {
    submitting.value = false
  }
}

async function startProduction(order: ProductionOrder) {
  if (confirm(`Start production for order ${order.order_number}?`)) {
    try {
      await productionService.startProduction(order.id)
      await loadProductionOrders()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to start production')
    }
  }
}

async function viewConsumption(order: ProductionOrder) {
  selectedOrder.value = order
  showConsumptionModal.value = true
  loadingConsumption.value = true
  showAddConsumptionForm.value = false

  try {
    consumptionRecords.value = await productionService.getProductionConsumption(order.id)
  } catch (err) {
    console.error('Failed to load consumption:', err)
  } finally {
    loadingConsumption.value = false
  }
}

function openAddConsumptionForm() {
  consumptionForm.value = {
    inventory_item_id: undefined,
    quantity_used: 0,
    unit_id: undefined
  }
  showAddConsumptionForm.value = true
}

function cancelAddConsumption() {
  showAddConsumptionForm.value = false
}

async function recordConsumption() {
  if (!selectedOrder.value) return

  try {
    recordingConsumption.value = true
    await productionService.recordConsumption(selectedOrder.value.id, consumptionForm.value)
    showAddConsumptionForm.value = false
    // Reload consumption records
    consumptionRecords.value = await productionService.getProductionConsumption(selectedOrder.value.id)
  } catch (err: any) {
    alert(err.response?.data?.message || 'Failed to record consumption')
  } finally {
    recordingConsumption.value = false
  }
}

async function viewOutput(order: ProductionOrder) {
  selectedOrder.value = order
  showOutputModal.value = true
  loadingOutput.value = true
  showAddOutputForm.value = false

  try {
    outputRecords.value = await productionService.getProductionOutput(order.id)
  } catch (err) {
    console.error('Failed to load output:', err)
  } finally {
    loadingOutput.value = false
  }
}

function openAddOutputForm() {
  outputForm.value = {
    quantity_produced: 0,
    unit_id: selectedOrder.value?.recipe?.unit_id,
    quality_grade: 'standard',
    notes: ''
  }
  showAddOutputForm.value = true
}

function cancelAddOutput() {
  showAddOutputForm.value = false
}

async function recordOutput() {
  if (!selectedOrder.value) return

  try {
    recordingOutput.value = true
    await productionService.recordOutput(selectedOrder.value.id, outputForm.value)
    showAddOutputForm.value = false
    // Reload output records
    outputRecords.value = await productionService.getProductionOutput(selectedOrder.value.id)
  } catch (err: any) {
    alert(err.response?.data?.message || 'Failed to record output')
  } finally {
    recordingOutput.value = false
  }
}

async function completeProduction(order: ProductionOrder) {
  if (confirm(`Complete production for order ${order.order_number}? This will finalize the order.`)) {
    try {
      await productionService.completeProduction(order.id)
      await loadProductionOrders()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to complete production')
    }
  }
}

function confirmDelete(order: ProductionOrder) {
  orderToDelete.value = order
  showDeleteDialog.value = true
}

async function handleDelete() {
  if (!orderToDelete.value) return

  try {
    deleting.value = true
    await productionService.deleteProductionOrder(orderToDelete.value.id)
    showDeleteDialog.value = false
    await loadProductionOrders()
  } catch (err: any) {
    alert(err.response?.data?.message || 'Failed to cancel production order')
  } finally {
    deleting.value = false
  }
}

function formatDateTime(dateTime: string): string {
  if (!dateTime) return '-'
  const date = new Date(dateTime)
  return date.toLocaleString('en-ET', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>
