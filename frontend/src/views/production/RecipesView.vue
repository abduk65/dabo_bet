<template>
  <div>
    <PageHeader title="Recipes" description="Manage product recipes and bills of materials">
      <template #actions>
        <button v-if="authStore.isManager" @click="openCreateModal" class="btn-primary">
          <PlusIcon class="h-5 w-5 mr-2" />
          Add Recipe
        </button>
      </template>
    </PageHeader>

    <!-- Recipes Table -->
    <div class="card overflow-hidden">
      <SimpleTable
        :columns="columns"
        :data="recipes"
        :loading="loading"
        loading-text="Loading recipes..."
        empty-text="No recipes found."
      >
        <template #cell-product_name="{ row }">
          {{ row.product?.product_name || '-' }}
        </template>

        <template #cell-batch_info="{ row }">
          {{ row.batch_size }} {{ row.unit?.abbreviation }}
        </template>

        <template #cell-total_time="{ row }">
          {{ row.total_time_minutes ? `${row.total_time_minutes} min` : '-' }}
        </template>

        <template #cell-is_active="{ value }">
          <span :class="value ? 'badge-success' : 'badge-gray'">
            {{ value ? 'Active' : 'Inactive' }}
          </span>
        </template>

        <template #actions="{ row }">
          <button @click="viewComponents(row)" class="text-blue-600 hover:text-blue-900 mr-4">Components</button>
          <button @click="calculateCost(row)" class="text-green-600 hover:text-green-900 mr-4">Cost</button>
          <button v-if="authStore.isManager" @click="openEditModal(row)" class="text-primary-600 hover:text-primary-900 mr-4">Edit</button>
          <button v-if="!row.is_active && authStore.isManager" @click="activateRecipe(row)" class="text-green-600 hover:text-green-900 mr-4">Activate</button>
          <button v-if="authStore.isOwner" @click="confirmDelete(row)" class="text-red-600 hover:text-red-900">Delete</button>
        </template>
      </SimpleTable>
    </div>

    <!-- Create/Edit Modal -->
    <Modal v-model:show="showModal" :title="isEditing ? 'Edit Recipe' : 'Create Recipe'" size="xl">
      <form @submit.prevent="handleSubmit">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <label class="block text-sm font-medium text-gray-700">Product *</label>
            <select v-model="formData.product_id" required class="input mt-1">
              <option :value="undefined">Select Product</option>
              <option v-for="product in products" :key="product.id" :value="product.id">
                {{ product.product_name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Recipe Version *</label>
            <input v-model="formData.recipe_version" type="text" required class="input mt-1" placeholder="e.g., v1.0" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Batch Size *</label>
            <input v-model.number="formData.batch_size" type="number" step="0.01" required class="input mt-1" />
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

          <div>
            <label class="block text-sm font-medium text-gray-700">Prep Time (minutes)</label>
            <input v-model.number="formData.prep_time_minutes" type="number" class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Bake Time (minutes)</label>
            <input v-model.number="formData.bake_time_minutes" type="number" class="input mt-1" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Total Time (minutes)</label>
            <input v-model.number="formData.total_time_minutes" type="number" class="input mt-1" />
          </div>

          <div class="flex items-center">
            <input v-model="formData.is_active" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" />
            <label class="ml-2 block text-sm text-gray-900">Active</label>
          </div>

          <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Instructions</label>
            <textarea v-model="formData.instructions" rows="4" class="input mt-1" placeholder="Step-by-step preparation instructions"></textarea>
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

    <!-- Components Modal -->
    <Modal v-model:show="showComponentsModal" :title="`Recipe Components - ${selectedRecipe?.product?.product_name}`" size="lg">
      <div v-if="loadingComponents" class="py-12">
        <LoadingSpinner text="Loading components..." />
      </div>
      <div v-else>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Substitutable</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="recipeComponents.length === 0">
                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No components found</td>
              </tr>
              <tr v-for="component in recipeComponents" :key="component.id">
                <td class="px-6 py-4 text-sm text-gray-900">
                  {{ component.inventory_item?.name || '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ component.quantity }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ component.unit?.abbreviation || '-' }}</td>
                <td class="px-6 py-4">
                  <span :class="component.can_substitute ? 'badge-success' : 'badge-gray'">
                    {{ component.can_substitute ? 'Yes' : 'No' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </Modal>

    <!-- Cost Modal -->
    <Modal v-model:show="showCostModal" :title="`Recipe Cost - ${selectedRecipe?.product?.product_name}`" size="md">
      <div v-if="calculatingCost" class="py-12">
        <LoadingSpinner text="Calculating cost..." />
      </div>
      <div v-else-if="recipeCost" class="space-y-4">
        <div class="card bg-blue-50">
          <p class="text-sm text-gray-600">Total Recipe Cost</p>
          <p class="text-3xl font-bold text-blue-600">{{ formatCurrency(recipeCost.total_cost) }}</p>
        </div>
        <div class="card bg-green-50">
          <p class="text-sm text-gray-600">Cost Per Unit</p>
          <p class="text-3xl font-bold text-green-600">{{ formatCurrency(recipeCost.cost_per_unit) }}</p>
        </div>
      </div>
    </Modal>

    <!-- Confirm Delete -->
    <ConfirmDialog
      v-model:show="showDeleteDialog"
      type="danger"
      title="Delete Recipe"
      :message="`Are you sure you want to delete this recipe?`"
      :loading="deleting"
      @confirm="handleDelete"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { productionService, type Recipe, type Product, type RecipeComponent } from '@/services/production.service'
import { referenceService, type Unit } from '@/services/reference.service'
import PageHeader from '@/components/common/PageHeader.vue'
import SimpleTable from '@/components/common/SimpleTable.vue'
import Modal from '@/components/common/Modal.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import ConfirmDialog from '@/components/common/ConfirmDialog.vue'
import { PlusIcon } from '@heroicons/vue/24/outline'

const authStore = useAuthStore()

const loading = ref(true)
const recipes = ref<Recipe[]>([])
const products = ref<Product[]>([])
const units = ref<Unit[]>([])

const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref<number | null>(null)
const submitting = ref(false)
const formError = ref<string | null>(null)

const showComponentsModal = ref(false)
const loadingComponents = ref(false)
const selectedRecipe = ref<Recipe | null>(null)
const recipeComponents = ref<RecipeComponent[]>([])

const showCostModal = ref(false)
const calculatingCost = ref(false)
const recipeCost = ref<{ total_cost: number; cost_per_unit: number } | null>(null)

const showDeleteDialog = ref(false)
const recipeToDelete = ref<Recipe | null>(null)
const deleting = ref(false)

const formData = ref({
  product_id: undefined as number | undefined,
  recipe_version: '',
  batch_size: 0,
  unit_id: undefined as number | undefined,
  prep_time_minutes: undefined as number | undefined,
  bake_time_minutes: undefined as number | undefined,
  total_time_minutes: undefined as number | undefined,
  instructions: '',
  is_active: true
})

const columns = [
  { key: 'product_name', label: 'Product', slot: true, cellClass: 'text-sm text-gray-900' },
  { key: 'recipe_version', label: 'Version', cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'batch_info', label: 'Batch Size', slot: true, cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'total_time', label: 'Time', slot: true, cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'is_active', label: 'Status', slot: true, cellClass: 'whitespace-nowrap' }
]

onMounted(async () => {
  await Promise.all([loadRecipes(), loadProducts(), loadUnits()])
})

async function loadRecipes() {
  try {
    loading.value = true
    recipes.value = await productionService.getRecipes()
  } catch (err) {
    console.error('Failed to load recipes:', err)
  } finally {
    loading.value = false
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

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  formData.value = {
    product_id: undefined,
    recipe_version: '',
    batch_size: 0,
    unit_id: undefined,
    prep_time_minutes: undefined,
    bake_time_minutes: undefined,
    total_time_minutes: undefined,
    instructions: '',
    is_active: true
  }
  formError.value = null
  showModal.value = true
}

function openEditModal(recipe: Recipe) {
  isEditing.value = true
  editingId.value = recipe.id
  formData.value = {
    product_id: recipe.product_id,
    recipe_version: recipe.recipe_version,
    batch_size: recipe.batch_size,
    unit_id: recipe.unit_id,
    prep_time_minutes: recipe.prep_time_minutes,
    bake_time_minutes: recipe.bake_time_minutes,
    total_time_minutes: recipe.total_time_minutes,
    instructions: recipe.instructions || '',
    is_active: recipe.is_active
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
      await productionService.updateRecipe(editingId.value, formData.value)
    } else {
      await productionService.createRecipe(formData.value)
    }

    closeModal()
    await loadRecipes()
  } catch (err: any) {
    formError.value = err.response?.data?.message || 'Failed to save recipe'
  } finally {
    submitting.value = false
  }
}

async function viewComponents(recipe: Recipe) {
  selectedRecipe.value = recipe
  showComponentsModal.value = true
  loadingComponents.value = true

  try {
    recipeComponents.value = await productionService.getRecipeComponents(recipe.id)
  } catch (err) {
    console.error('Failed to load components:', err)
  } finally {
    loadingComponents.value = false
  }
}

async function calculateCost(recipe: Recipe) {
  selectedRecipe.value = recipe
  showCostModal.value = true
  calculatingCost.value = true

  try {
    recipeCost.value = await productionService.calculateRecipeCost(recipe.id)
  } catch (err) {
    console.error('Failed to calculate cost:', err)
  } finally {
    calculatingCost.value = false
  }
}

async function activateRecipe(recipe: Recipe) {
  if (confirm(`Activate recipe version ${recipe.recipe_version} for ${recipe.product?.product_name}?`)) {
    try {
      await productionService.activateRecipe(recipe.id)
      await loadRecipes()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to activate recipe')
    }
  }
}

function confirmDelete(recipe: Recipe) {
  recipeToDelete.value = recipe
  showDeleteDialog.value = true
}

async function handleDelete() {
  if (!recipeToDelete.value) return

  try {
    deleting.value = true
    await productionService.deleteRecipe(recipeToDelete.value.id)
    showDeleteDialog.value = false
    await loadRecipes()
  } catch (err: any) {
    alert(err.response?.data?.message || 'Failed to delete recipe')
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
</script>
