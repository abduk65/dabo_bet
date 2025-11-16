<template>
  <div>
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Inventory Items</h1>
        <p class="mt-2 text-sm text-gray-700">Track stock levels and manage inventory items</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <button
          v-if="authStore.isManager"
          @click="openCreateModal"
          class="btn-primary"
        >
          <PlusIcon class="h-5 w-5 mr-2" />
          Add Item
        </button>
      </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div>
          <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
          <input
            id="search"
            v-model="filters.search"
            type="text"
            class="input mt-1"
            placeholder="Search by name or code..."
            @input="debouncedLoadItems"
          />
        </div>
        <div>
          <label for="material_type" class="block text-sm font-medium text-gray-700">Material Type</label>
          <select
            id="material_type"
            v-model="filters.material_type_id"
            class="input mt-1"
            @change="loadItems"
          >
            <option :value="undefined">All Types</option>
            <option v-for="type in materialTypes" :key="type.id" :value="type.id">
              {{ type.name }}
            </option>
          </select>
        </div>
        <div class="flex items-end">
          <button @click="clearFilters" class="btn w-full">
            Clear Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="card">
      <div class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
        <p class="mt-2 text-sm text-gray-500">Loading inventory items...</p>
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
                Item
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Type
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Brand
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Quantity
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
            <tr v-if="items.length === 0">
              <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                No inventory items found.
              </td>
            </tr>
            <tr v-for="item in items" :key="item.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ item.name }}</div>
                <div class="text-sm text-gray-500">{{ item.code }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ item.material_type?.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ item.brand?.name || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ item.quantity }} {{ item.unit }}</div>
                <div class="text-xs text-gray-500">Min: {{ item.min_quantity }}, Max: {{ item.max_quantity }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStockBadgeClass(item)">
                  {{ getStockStatus(item) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button
                  v-if="authStore.isManager"
                  @click="openEditModal(item)"
                  class="text-primary-600 hover:text-primary-900 mr-4"
                >
                  Edit
                </button>
                <button
                  v-if="authStore.isOwner"
                  @click="confirmDelete(item)"
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <TransitionRoot as="template" :show="showModal">
      <Dialog as="div" class="relative z-50" @close="closeModal">
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
        </TransitionChild>

        <div class="fixed inset-0 z-10 overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <TransitionChild
              as="template"
              enter="ease-out duration-300"
              enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              enter-to="opacity-100 translate-y-0 sm:scale-100"
              leave="ease-in duration-200"
              leave-from="opacity-100 translate-y-0 sm:scale-100"
              leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
              <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                <form @submit.prevent="handleSubmit">
                  <div>
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                      <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900 mb-4">
                        {{ isEditing ? 'Edit Inventory Item' : 'Create Inventory Item' }}
                      </DialogTitle>

                      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                          <label for="code" class="block text-sm font-medium text-gray-700">Code *</label>
                          <input
                            id="code"
                            v-model="formData.code"
                            type="text"
                            required
                            class="input mt-1"
                            placeholder="e.g., FLOUR-001"
                          />
                        </div>

                        <div>
                          <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                          <input
                            id="name"
                            v-model="formData.name"
                            type="text"
                            required
                            class="input mt-1"
                            placeholder="e.g., White Flour"
                          />
                        </div>

                        <div>
                          <label for="material_type_id" class="block text-sm font-medium text-gray-700">Material Type *</label>
                          <select
                            id="material_type_id"
                            v-model="formData.material_type_id"
                            required
                            class="input mt-1"
                          >
                            <option :value="undefined">Select Type</option>
                            <option v-for="type in materialTypes" :key="type.id" :value="type.id">
                              {{ type.name }}
                            </option>
                          </select>
                        </div>

                        <div>
                          <label for="brand_id" class="block text-sm font-medium text-gray-700">Brand</label>
                          <select
                            id="brand_id"
                            v-model="formData.brand_id"
                            class="input mt-1"
                          >
                            <option :value="undefined">No Brand</option>
                            <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                              {{ brand.name }}
                            </option>
                          </select>
                        </div>

                        <div>
                          <label for="quantity" class="block text-sm font-medium text-gray-700">Initial Quantity *</label>
                          <input
                            id="quantity"
                            v-model.number="formData.quantity"
                            type="number"
                            step="0.01"
                            required
                            class="input mt-1"
                            :disabled="isEditing"
                          />
                        </div>

                        <div>
                          <label for="unit" class="block text-sm font-medium text-gray-700">Unit *</label>
                          <input
                            id="unit"
                            v-model="formData.unit"
                            type="text"
                            required
                            class="input mt-1"
                            placeholder="e.g., kg, pcs, L"
                          />
                        </div>

                        <div>
                          <label for="min_quantity" class="block text-sm font-medium text-gray-700">Min Quantity *</label>
                          <input
                            id="min_quantity"
                            v-model.number="formData.min_quantity"
                            type="number"
                            step="0.01"
                            required
                            class="input mt-1"
                          />
                        </div>

                        <div>
                          <label for="max_quantity" class="block text-sm font-medium text-gray-700">Max Quantity *</label>
                          <input
                            id="max_quantity"
                            v-model.number="formData.max_quantity"
                            type="number"
                            step="0.01"
                            required
                            class="input mt-1"
                          />
                        </div>

                        <div>
                          <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost</label>
                          <input
                            id="unit_cost"
                            v-model.number="formData.unit_cost"
                            type="number"
                            step="0.01"
                            class="input mt-1"
                          />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div v-if="formError" class="mt-4 rounded-md bg-red-50 p-4">
                    <p class="text-sm text-red-800">{{ formError }}</p>
                  </div>

                  <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                    <button
                      type="submit"
                      :disabled="submitting"
                      class="btn-primary w-full sm:col-start-2"
                    >
                      {{ submitting ? 'Saving...' : 'Save' }}
                    </button>
                    <button
                      type="button"
                      @click="closeModal"
                      :disabled="submitting"
                      class="btn mt-3 sm:col-start-1 sm:mt-0 w-full"
                    >
                      Cancel
                    </button>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { inventoryService } from '@/services/inventory.service'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { PlusIcon } from '@heroicons/vue/24/outline'
import type { InventoryItem, MaterialType, Brand } from '@/types'

const authStore = useAuthStore()

const loading = ref(true)
const error = ref<string | null>(null)
const items = ref<InventoryItem[]>([])
const materialTypes = ref<MaterialType[]>([])
const brands = ref<Brand[]>([])

const filters = ref({
  search: '',
  material_type_id: undefined as number | undefined
})

const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref<number | null>(null)
const submitting = ref(false)
const formError = ref<string | null>(null)

const formData = ref({
  code: '',
  name: '',
  material_type_id: undefined as number | undefined,
  brand_id: undefined as number | undefined,
  quantity: 0,
  unit: '',
  min_quantity: 0,
  max_quantity: 0,
  unit_cost: undefined as number | undefined
})

let debounceTimeout: ReturnType<typeof setTimeout> | null = null

onMounted(async () => {
  await Promise.all([
    loadItems(),
    loadMaterialTypes(),
    loadBrands()
  ])
})

async function loadItems() {
  try {
    loading.value = true
    error.value = null
    items.value = await inventoryService.getInventoryItems(filters.value)
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to load inventory items'
  } finally {
    loading.value = false
  }
}

function debouncedLoadItems() {
  if (debounceTimeout) clearTimeout(debounceTimeout)
  debounceTimeout = setTimeout(() => {
    loadItems()
  }, 300)
}

async function loadMaterialTypes() {
  try {
    materialTypes.value = await inventoryService.getMaterialTypes()
  } catch (err) {
    console.error('Failed to load material types:', err)
  }
}

async function loadBrands() {
  try {
    brands.value = await inventoryService.getBrands()
  } catch (err) {
    console.error('Failed to load brands:', err)
  }
}

function clearFilters() {
  filters.value = {
    search: '',
    material_type_id: undefined
  }
  loadItems()
}

function getStockStatus(item: InventoryItem): string {
  if (item.quantity <= item.min_quantity) return 'Low Stock'
  if (item.quantity >= item.max_quantity) return 'Overstocked'
  return 'In Stock'
}

function getStockBadgeClass(item: InventoryItem): string {
  const baseClass = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
  if (item.quantity <= item.min_quantity) return `${baseClass} bg-red-100 text-red-800`
  if (item.quantity >= item.max_quantity) return `${baseClass} bg-yellow-100 text-yellow-800`
  return `${baseClass} bg-green-100 text-green-800`
}

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  formData.value = {
    code: '',
    name: '',
    material_type_id: undefined,
    brand_id: undefined,
    quantity: 0,
    unit: '',
    min_quantity: 0,
    max_quantity: 0,
    unit_cost: undefined
  }
  formError.value = null
  showModal.value = true
}

function openEditModal(item: InventoryItem) {
  isEditing.value = true
  editingId.value = item.id
  formData.value = {
    code: item.code,
    name: item.name,
    material_type_id: item.material_type_id,
    brand_id: item.brand_id || undefined,
    quantity: item.quantity,
    unit: item.unit,
    min_quantity: item.min_quantity,
    max_quantity: item.max_quantity,
    unit_cost: item.unit_cost || undefined
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
      await inventoryService.updateInventoryItem(editingId.value, formData.value)
    } else {
      await inventoryService.createInventoryItem(formData.value)
    }

    closeModal()
    await loadItems()
  } catch (err: any) {
    formError.value = err.response?.data?.message || 'Failed to save inventory item'
  } finally {
    submitting.value = false
  }
}

async function confirmDelete(item: InventoryItem) {
  if (confirm(`Are you sure you want to delete "${item.name}"? This action cannot be undone.`)) {
    try {
      await inventoryService.deleteInventoryItem(item.id)
      await loadItems()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to delete inventory item')
    }
  }
}
</script>
