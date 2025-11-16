<template>
  <div>
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Brands</h1>
        <p class="mt-2 text-sm text-gray-700">Manage supplier and product brands</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <button
          v-if="authStore.isManager"
          @click="openCreateModal"
          class="btn-primary"
        >
          <PlusIcon class="h-5 w-5 mr-2" />
          Add Brand
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="card">
      <div class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
        <p class="mt-2 text-sm text-gray-500">Loading brands...</p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="card bg-red-50">
      <p class="text-red-800">{{ error }}</p>
    </div>

    <!-- Data Table -->
    <div v-else class="card overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Name
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Description
            </th>
            <th scope="col" class="relative px-6 py-3">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="brands.length === 0">
            <td colspan="3" class="px-6 py-12 text-center text-sm text-gray-500">
              No brands found. Create one to get started.
            </td>
          </tr>
          <tr v-for="brand in brands" :key="brand.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ brand.name }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">
              {{ brand.description || '-' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button
                v-if="authStore.isManager"
                @click="openEditModal(brand)"
                class="text-primary-600 hover:text-primary-900 mr-4"
              >
                Edit
              </button>
              <button
                v-if="authStore.isOwner"
                @click="confirmDelete(brand)"
                class="text-red-600 hover:text-red-900"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
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
              <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <form @submit.prevent="handleSubmit">
                  <div>
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                      <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900 mb-4">
                        {{ isEditing ? 'Edit Brand' : 'Create Brand' }}
                      </DialogTitle>

                      <div class="space-y-4">
                        <div>
                          <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                          <input
                            id="name"
                            v-model="formData.name"
                            type="text"
                            required
                            class="input mt-1"
                            placeholder="e.g., Nestlé, Unilever"
                          />
                        </div>

                        <div>
                          <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                          <textarea
                            id="description"
                            v-model="formData.description"
                            rows="3"
                            class="input mt-1"
                            placeholder="Optional description"
                          ></textarea>
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
import type { Brand } from '@/types'

const authStore = useAuthStore()

const loading = ref(true)
const error = ref<string | null>(null)
const brands = ref<Brand[]>([])

const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref<number | null>(null)
const submitting = ref(false)
const formError = ref<string | null>(null)

const formData = ref({
  name: '',
  description: ''
})

onMounted(async () => {
  await loadBrands()
})

async function loadBrands() {
  try {
    loading.value = true
    error.value = null
    brands.value = await inventoryService.getBrands()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to load brands'
  } finally {
    loading.value = false
  }
}

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  formData.value = {
    name: '',
    description: ''
  }
  formError.value = null
  showModal.value = true
}

function openEditModal(brand: Brand) {
  isEditing.value = true
  editingId.value = brand.id
  formData.value = {
    name: brand.name,
    description: brand.description || ''
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
      await inventoryService.updateBrand(editingId.value, formData.value)
    } else {
      await inventoryService.createBrand(formData.value)
    }

    closeModal()
    await loadBrands()
  } catch (err: any) {
    formError.value = err.response?.data?.message || 'Failed to save brand'
  } finally {
    submitting.value = false
  }
}

async function confirmDelete(brand: Brand) {
  if (confirm(`Are you sure you want to delete "${brand.name}"? This action cannot be undone.`)) {
    try {
      await inventoryService.deleteBrand(brand.id)
      await loadBrands()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to delete brand')
    }
  }
}
</script>
