<template>
  <div>
    <PageHeader title="Products" description="Manage finished goods and product catalog">
      <template #actions>
        <button v-if="authStore.isManager" @click="openCreateModal" class="btn-primary">
          <PlusIcon class="h-5 w-5 mr-2" />
          Add Product
        </button>
      </template>
    </PageHeader>

    <!-- Filter Tabs -->
    <div class="card mb-6">
      <div class="flex flex-wrap gap-2">
        <button
          v-for="type in productTypes"
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

    <!-- Products Table -->
    <div class="card overflow-hidden">
      <SimpleTable
        :columns="columns"
        :data="filteredProducts"
        :loading="loading"
        loading-text="Loading products..."
        empty-text="No products found. Create one to get started."
      >
        <template #cell-product_type="{ value }">
          <span :class="getTypeBadgeClass(value)">
            {{ value }}
          </span>
        </template>

        <template #cell-is_active="{ value }">
          <span :class="value ? 'badge-success' : 'badge-gray'">
            {{ value ? 'Active' : 'Inactive' }}
          </span>
        </template>

        <template #actions="{ row }">
          <button
            @click="viewPriceHistory(row)"
            class="text-blue-600 hover:text-blue-900 mr-4"
          >
            Prices
          </button>
          <button
            v-if="authStore.isManager"
            @click="openEditModal(row)"
            class="text-primary-600 hover:text-primary-900 mr-4"
          >
            Edit
          </button>
          <button
            v-if="authStore.isOwner"
            @click="confirmDelete(row)"
            class="text-red-600 hover:text-red-900"
          >
            Delete
          </button>
        </template>
      </SimpleTable>
    </div>

    <!-- Create/Edit Modal -->
    <Modal
      v-model:show="showModal"
      :title="isEditing ? 'Edit Product' : 'Create Product'"
      size="lg"
    >
      <form @submit.prevent="handleSubmit">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name *</label>
            <input
              id="product_name"
              v-model="formData.product_name"
              type="text"
              required
              class="input mt-1"
              placeholder="e.g., White Bread"
            />
          </div>

          <div>
            <label for="product_code" class="block text-sm font-medium text-gray-700">Product Code *</label>
            <input
              id="product_code"
              v-model="formData.product_code"
              type="text"
              required
              class="input mt-1"
              placeholder="e.g., BREAD-001"
            />
          </div>

          <div>
            <label for="product_type" class="block text-sm font-medium text-gray-700">Product Type *</label>
            <select
              id="product_type"
              v-model="formData.product_type"
              required
              class="input mt-1"
            >
              <option value="">Select Type</option>
              <option value="bread">Bread</option>
              <option value="pastry">Pastry</option>
              <option value="cake">Cake</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div>
            <label for="unit_id" class="block text-sm font-medium text-gray-700">Unit *</label>
            <select
              id="unit_id"
              v-model="formData.unit_id"
              required
              class="input mt-1"
            >
              <option :value="undefined">Select Unit</option>
              <option v-for="unit in units" :key="unit.id" :value="unit.id">
                {{ unit.unit_name }} ({{ unit.abbreviation }})
              </option>
            </select>
          </div>

          <div>
            <label for="shelf_life_days" class="block text-sm font-medium text-gray-700">Shelf Life (Days) *</label>
            <input
              id="shelf_life_days"
              v-model.number="formData.shelf_life_days"
              type="number"
              min="1"
              required
              class="input mt-1"
            />
          </div>

          <div class="sm:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
              id="description"
              v-model="formData.description"
              rows="3"
              class="input mt-1"
              placeholder="Optional product description"
            ></textarea>
          </div>

          <div class="sm:col-span-2 flex items-center">
            <input
              id="is_active"
              v-model="formData.is_active"
              type="checkbox"
              class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
            />
            <label for="is_active" class="ml-2 block text-sm text-gray-900">
              Active
            </label>
          </div>
        </div>

        <div v-if="formError" class="mt-4 rounded-md bg-red-50 p-4">
          <p class="text-sm text-red-800">{{ formError }}</p>
        </div>

        <template #footer>
          <div class="flex gap-3 justify-end">
            <button
              type="button"
              @click="closeModal"
              :disabled="submitting"
              class="btn"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="submitting"
              class="btn-primary"
            >
              {{ submitting ? 'Saving...' : 'Save' }}
            </button>
          </div>
        </template>
      </form>
    </Modal>

    <!-- Price History Modal -->
    <Modal
      v-model:show="showPriceModal"
      :title="`Price History - ${selectedProduct?.product_name}`"
      size="lg"
    >
      <div v-if="loadingPrices" class="py-12">
        <LoadingSpinner text="Loading price history..." />
      </div>
      <div v-else>
        <div v-if="authStore.isManager" class="mb-4 pb-4 border-b">
          <h4 class="text-sm font-medium text-gray-900 mb-3">Set New Price</h4>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Price *</label>
              <input
                v-model.number="newPrice.price"
                type="number"
                step="0.01"
                min="0"
                class="input mt-1"
                placeholder="0.00"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Effective Date *</label>
              <input
                v-model="newPrice.effective_date"
                type="date"
                class="input mt-1"
              />
            </div>
          </div>
          <button
            @click="saveNewPrice"
            :disabled="!newPrice.price || !newPrice.effective_date || savingPrice"
            class="btn-primary mt-3"
          >
            {{ savingPrice ? 'Saving...' : 'Set Price' }}
          </button>
        </div>

        <div v-if="priceHistory.length === 0" class="text-center py-8 text-gray-500">
          No price history available
        </div>
        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Effective Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="price in priceHistory" :key="price.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatCurrency(price.price) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(price.effective_date) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="price.is_current ? 'badge-success' : 'badge-gray'">
                    {{ price.is_current ? 'Current' : 'Historical' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </Modal>

    <!-- Confirm Delete Dialog -->
    <ConfirmDialog
      v-model:show="showDeleteDialog"
      type="danger"
      title="Delete Product"
      :message="`Are you sure you want to delete '${productToDelete?.product_name}'? This action cannot be undone.`"
      confirm-text="Delete"
      :loading="deleting"
      @confirm="handleDelete"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { productionService, type Product, type ProductPrice } from '@/services/production.service'
import { referenceService, type Unit } from '@/services/reference.service'
import PageHeader from '@/components/common/PageHeader.vue'
import SimpleTable from '@/components/common/SimpleTable.vue'
import Modal from '@/components/common/Modal.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import ConfirmDialog from '@/components/common/ConfirmDialog.vue'
import { PlusIcon } from '@heroicons/vue/24/outline'

const authStore = useAuthStore()

const loading = ref(true)
const products = ref<Product[]>([])
const units = ref<Unit[]>([])
const selectedType = ref<string | undefined>(undefined)

const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref<number | null>(null)
const submitting = ref(false)
const formError = ref<string | null>(null)

const showPriceModal = ref(false)
const loadingPrices = ref(false)
const selectedProduct = ref<Product | null>(null)
const priceHistory = ref<ProductPrice[]>([])
const newPrice = ref({ price: 0, effective_date: '' })
const savingPrice = ref(false)

const showDeleteDialog = ref(false)
const productToDelete = ref<Product | null>(null)
const deleting = ref(false)

const formData = ref({
  product_code: '',
  product_name: '',
  product_type: '' as 'bread' | 'pastry' | 'cake' | 'other' | '',
  unit_id: undefined as number | undefined,
  shelf_life_days: 1,
  description: '',
  is_active: true
})

const productTypes = [
  { value: undefined, label: 'All Products' },
  { value: 'bread', label: 'Bread' },
  { value: 'pastry', label: 'Pastry' },
  { value: 'cake', label: 'Cake' },
  { value: 'other', label: 'Other' }
]

const columns = [
  { key: 'product_code', label: 'Code', cellClass: 'whitespace-nowrap text-sm font-medium text-gray-900' },
  { key: 'product_name', label: 'Name', cellClass: 'whitespace-nowrap text-sm text-gray-900' },
  { key: 'product_type', label: 'Type', slot: true, cellClass: 'whitespace-nowrap text-sm' },
  { key: 'unit.unit_name', label: 'Unit', cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'shelf_life_days', label: 'Shelf Life', formatter: (v: number) => `${v} days`, cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'is_active', label: 'Status', slot: true, cellClass: 'whitespace-nowrap text-sm' }
]

const filteredProducts = computed(() => {
  if (!selectedType.value) return products.value
  return products.value.filter(p => p.product_type === selectedType.value)
})

onMounted(async () => {
  await Promise.all([loadProducts(), loadUnits()])
})

async function loadProducts() {
  try {
    loading.value = true
    products.value = await productionService.getProducts()
  } catch (err: any) {
    console.error('Failed to load products:', err)
  } finally {
    loading.value = false
  }
}

async function loadUnits() {
  try {
    units.value = await referenceService.getUnits()
  } catch (err) {
    console.error('Failed to load units:', err)
  }
}

function getTypeBadgeClass(type: string): string {
  const classes = {
    bread: 'badge-primary',
    pastry: 'badge-purple',
    cake: 'badge-pink',
    other: 'badge-gray'
  }
  return classes[type as keyof typeof classes] || 'badge-gray'
}

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  formData.value = {
    product_code: '',
    product_name: '',
    product_type: '',
    unit_id: undefined,
    shelf_life_days: 1,
    description: '',
    is_active: true
  }
  formError.value = null
  showModal.value = true
}

function openEditModal(product: Product) {
  isEditing.value = true
  editingId.value = product.id
  formData.value = {
    product_code: product.product_code,
    product_name: product.product_name,
    product_type: product.product_type,
    unit_id: product.unit_id,
    shelf_life_days: product.shelf_life_days,
    description: product.description || '',
    is_active: product.is_active
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
      await productionService.updateProduct(editingId.value, formData.value)
    } else {
      await productionService.createProduct(formData.value)
    }

    closeModal()
    await loadProducts()
  } catch (err: any) {
    formError.value = err.response?.data?.message || 'Failed to save product'
  } finally {
    submitting.value = false
  }
}

async function viewPriceHistory(product: Product) {
  selectedProduct.value = product
  showPriceModal.value = true
  loadingPrices.value = true
  newPrice.value = { price: 0, effective_date: new Date().toISOString().split('T')[0] }

  try {
    priceHistory.value = await productionService.getProductPriceHistory(product.id)
  } catch (err) {
    console.error('Failed to load price history:', err)
  } finally {
    loadingPrices.value = false
  }
}

async function saveNewPrice() {
  if (!selectedProduct.value) return

  try {
    savingPrice.value = true
    await productionService.setProductPrice(selectedProduct.value.id, newPrice.value)
    priceHistory.value = await productionService.getProductPriceHistory(selectedProduct.value.id)
    newPrice.value = { price: 0, effective_date: new Date().toISOString().split('T')[0] }
  } catch (err: any) {
    alert(err.response?.data?.message || 'Failed to set price')
  } finally {
    savingPrice.value = false
  }
}

function confirmDelete(product: Product) {
  productToDelete.value = product
  showDeleteDialog.value = true
}

async function handleDelete() {
  if (!productToDelete.value) return

  try {
    deleting.value = true
    await productionService.deleteProduct(productToDelete.value.id)
    showDeleteDialog.value = false
    await loadProducts()
  } catch (err: any) {
    alert(err.response?.data?.message || 'Failed to delete product')
  } finally {
    deleting.value = false
  }
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-ET', {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: 2
  }).format(amount)
}

function formatDate(dateString: string): string {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}
</script>
