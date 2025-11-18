<template>
  <div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b px-6 py-4">
      <h1 class="text-2xl font-bold text-gray-900">Production & Dispatch</h1>
      <p class="text-sm text-gray-600 mt-1">{{ today }}</p>
    </div>

    <!-- Today's Production Orders -->
    <div class="px-6">
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b flex items-center justify-between">
          <div>
            <h2 class="text-lg font-bold text-gray-900">Today's Production</h2>
            <p class="text-sm text-gray-600">Record what was actually produced</p>
          </div>
          <button @click="showNewProductionModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + New Production
          </button>
        </div>
        <div class="p-6">
          <div class="space-y-4">
            <div v-for="order in todayProduction" :key="order.id" class="border rounded-lg p-4">
              <div class="flex items-center justify-between mb-3">
                <div>
                  <h3 class="font-bold text-gray-900">{{ order.productName }}</h3>
                  <p class="text-sm text-gray-600">Planned: {{ order.plannedQty }} pieces</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold"
                      :class="order.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                  {{ order.status }}
                </span>
              </div>

              <!-- Production entry form -->
              <div v-if="order.status !== 'completed'" class="grid grid-cols-3 gap-4 mt-4 p-4 bg-gray-50 rounded">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Actual Produced</label>
                  <input v-model.number="order.actualQty" type="number" class="w-full px-3 py-2 border rounded-lg" placeholder="0" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Damage/Waste</label>
                  <input v-model.number="order.wasteQty" type="number" class="w-full px-3 py-2 border rounded-lg" placeholder="0" />
                </div>
                <div class="flex items-end">
                  <button @click="recordProduction(order)" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Record
                  </button>
                </div>
              </div>

              <!-- Completed info -->
              <div v-else class="mt-3 p-3 bg-green-50 rounded text-sm">
                <p class="text-gray-700">✓ Produced: <span class="font-bold">{{ order.actualQty }} pieces</span></p>
                <p v-if="order.wasteQty > 0" class="text-gray-700 mt-1">Waste: {{ order.wasteQty }} pieces</p>
              </div>
            </div>

            <div v-if="todayProduction.length === 0" class="text-center py-12 text-gray-500">
              <p>No production orders for today</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Dispatch to Branches -->
    <div class="px-6">
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b flex items-center justify-between">
          <div>
            <h2 class="text-lg font-bold text-gray-900">Dispatch to Branches</h2>
            <p class="text-sm text-gray-600">Send products to sub-branches</p>
          </div>
          <button @click="showDispatchModal = true" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            + New Dispatch
          </button>
        </div>
        <div class="p-6">
          <div class="space-y-4">
            <div v-for="dispatch in todayDispatches" :key="dispatch.id" class="flex items-center justify-between p-4 border rounded-lg">
              <div>
                <h3 class="font-bold text-gray-900">{{ dispatch.toBranch }}</h3>
                <p class="text-sm text-gray-600">{{ dispatch.productName }} - {{ dispatch.qty }} pieces</p>
                <p class="text-xs text-gray-500 mt-1">{{ dispatch.time }}</p>
              </div>
              <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                Dispatched
              </span>
            </div>

            <div v-if="todayDispatches.length === 0" class="text-center py-12 text-gray-500">
              <p>No dispatches today</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Current Stock -->
    <div class="px-6">
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
          <h2 class="text-lg font-bold text-gray-900">Current Stock (Main Branch)</h2>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div v-for="stock in currentStock" :key="stock.id" class="text-center p-4 border rounded-lg">
              <p class="text-3xl font-bold text-gray-900">{{ stock.quantity }}</p>
              <p class="text-sm text-gray-600 mt-1">{{ stock.productName }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- New Production Modal -->
    <div v-if="showNewProductionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-bold mb-4">New Production Order</h3>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Product</label>
            <select v-model="newProduction.productId" class="w-full px-3 py-2 border rounded-lg">
              <option value="">Select product</option>
              <option value="1">White Bread</option>
              <option value="2">Brown Bread</option>
              <option value="3">Cake</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Planned Quantity</label>
            <input v-model.number="newProduction.plannedQty" type="number" class="w-full px-3 py-2 border rounded-lg" />
          </div>
        </div>
        <div class="flex gap-3 mt-6">
          <button @click="showNewProductionModal = false" class="flex-1 px-4 py-2 border rounded-lg">Cancel</button>
          <button @click="createProduction" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create</button>
        </div>
      </div>
    </div>

    <!-- Dispatch Modal -->
    <div v-if="showDispatchModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-bold mb-4">New Dispatch</h3>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">To Branch</label>
            <select v-model="newDispatch.branchId" class="w-full px-3 py-2 border rounded-lg">
              <option value="">Select branch</option>
              <option value="2">Piassa Branch</option>
              <option value="3">Bole Branch</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Product</label>
            <select v-model="newDispatch.productId" class="w-full px-3 py-2 border rounded-lg">
              <option value="">Select product</option>
              <option value="1">White Bread</option>
              <option value="2">Brown Bread</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Quantity</label>
            <input v-model.number="newDispatch.quantity" type="number" class="w-full px-3 py-2 border rounded-lg" />
          </div>
        </div>
        <div class="flex gap-3 mt-6">
          <button @click="showDispatchModal = false" class="flex-1 px-4 py-2 border rounded-lg">Cancel</button>
          <button @click="createDispatch" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Dispatch</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const today = computed(() => new Date().toLocaleDateString('en-ET', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }))

const todayProduction = ref([
  { id: 1, productName: 'White Bread', plannedQty: 500, actualQty: 0, wasteQty: 0, status: 'in_progress' },
  { id: 2, productName: 'Brown Bread', plannedQty: 400, actualQty: 0, wasteQty: 0, status: 'in_progress' }
])

const todayDispatches = ref([
  { id: 1, toBranch: 'Piassa Branch', productName: 'White Bread', qty: 200, time: '10:30 AM' }
])

const currentStock = ref([
  { id: 1, productName: 'White Bread', quantity: 300 },
  { id: 2, productName: 'Brown Bread', quantity: 400 }
])

const showNewProductionModal = ref(false)
const showDispatchModal = ref(false)

const newProduction = ref({ productId: '', plannedQty: 0 })
const newDispatch = ref({ branchId: '', productId: '', quantity: 0 })

function recordProduction(order: any) {
  if (!order.actualQty) {
    alert('Please enter actual quantity produced')
    return
  }
  order.status = 'completed'
  // TODO: Send to API
  alert(`Production recorded: ${order.actualQty} pieces of ${order.productName}`)
}

function createProduction() {
  // TODO: Send to API
  showNewProductionModal.value = false
  alert('Production order created')
}

function createDispatch() {
  if (!newDispatch.value.branchId || !newDispatch.value.productId || !newDispatch.value.quantity) {
    alert('Please fill all fields')
    return
  }
  // TODO: Send to API
  showDispatchModal.value = false
  alert('Dispatch created')
}
</script>
