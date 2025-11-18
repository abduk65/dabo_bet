<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Simple Header -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
      <h1 class="text-4xl font-bold text-gray-900">🚚 Send to Branch</h1>
      <p class="text-xl text-gray-600 mt-2">Record what you sent to other branches</p>
    </div>

    <!-- Today's Date -->
    <div class="bg-purple-100 rounded-lg p-6 mb-6">
      <p class="text-lg font-medium text-gray-700">Date:</p>
      <p class="text-3xl font-bold text-purple-600">{{ today }}</p>
    </div>

    <!-- Step 1: Select Branch -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">STEP 1: Where are you sending?</h2>

      <div class="grid grid-cols-2 gap-6">
        <button
          v-for="branch in branches"
          :key="branch.id"
          @click="selectedBranch = branch"
          class="p-8 border-4 rounded-lg text-left transition-all"
          :class="selectedBranch?.id === branch.id ? 'bg-purple-100 border-purple-500' : 'bg-white border-gray-300 hover:border-purple-300'"
        >
          <p class="text-4xl mb-2">{{ branch.icon }}</p>
          <p class="text-2xl font-bold text-gray-900">{{ branch.name }}</p>
        </button>
      </div>
    </div>

    <!-- Step 2: Enter Quantities -->
    <div v-if="selectedBranch" class="bg-white rounded-lg shadow-lg p-8 mb-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-2">STEP 2: How many are you sending?</h2>
      <p class="text-xl text-purple-600 font-bold mb-6">To: {{ selectedBranch.name }}</p>

      <div class="space-y-6">
        <div v-for="product in products" :key="product.id" class="p-6 border-4 rounded-lg" :class="product.quantity > 0 ? 'bg-purple-50 border-purple-400' : 'bg-white border-gray-300'">
          <div class="flex items-center gap-6">
            <!-- Product Info -->
            <div class="flex-1">
              <p class="text-3xl mb-2">{{ product.icon }}</p>
              <p class="text-2xl font-bold text-gray-900">{{ product.name }}</p>
              <p class="text-lg text-gray-600 mt-1">Available: {{ product.available }} pieces</p>
            </div>

            <!-- Quantity Input -->
            <div class="text-center">
              <input
                v-model.number="product.quantity"
                type="number"
                min="0"
                :max="product.available"
                class="w-40 px-6 py-6 border-4 rounded-lg text-5xl font-bold text-center focus:ring-4 focus:ring-purple-500"
                :class="product.quantity > 0 ? 'bg-purple-100 border-purple-500' : 'bg-white border-gray-300'"
                placeholder="0"
                @input="validateQuantity(product)"
              />
              <p class="text-lg text-gray-600 mt-2">pieces</p>
            </div>
          </div>

          <!-- Warning if exceeds available -->
          <div v-if="product.quantity > product.available" class="mt-4 p-3 bg-red-100 border-2 border-red-400 rounded-lg">
            <p class="text-lg font-bold text-red-700">⚠️ Cannot send more than available! Only {{ product.available }} available.</p>
          </div>
        </div>
      </div>

      <!-- Total Summary -->
      <div class="mt-8 p-6 bg-purple-100 rounded-lg border-4 border-purple-300">
        <p class="text-2xl font-bold text-gray-900">TOTAL SENDING: <span class="text-purple-600">{{ totalSending }}</span> pieces</p>
      </div>

      <!-- Save Button -->
      <button
        @click="saveDispatch"
        :disabled="!canSave"
        class="w-full mt-6 px-8 py-8 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-bold text-3xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
      >
        💾 SAVE DISPATCH
      </button>

      <!-- Clear Button -->
      <button
        @click="clearQuantities"
        class="w-full mt-4 px-6 py-4 bg-gray-500 text-white rounded-lg hover:bg-gray-600 font-bold text-xl"
      >
        Clear Quantities
      </button>
    </div>

    <!-- Empty State -->
    <div v-if="!selectedBranch" class="bg-gray-100 rounded-lg p-12 text-center">
      <p class="text-2xl text-gray-500">👆 First, select which branch you are sending to</p>
    </div>

    <!-- Today's Dispatches -->
    <div v-if="todayDispatches.length > 0" class="bg-white rounded-lg shadow-lg p-8">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Today's Dispatches ({{ todayDispatches.length }})</h2>

      <div class="space-y-4">
        <div v-for="(dispatch, index) in todayDispatches" :key="index" class="p-6 border-4 border-green-300 rounded-lg bg-green-50">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xl font-bold text-gray-900">To: {{ dispatch.branchName }}</p>
              <p class="text-lg text-gray-600 mt-1">{{ dispatch.time }}</p>
              <div class="mt-2 space-y-1">
                <p v-for="item in dispatch.items" :key="item.productName" class="text-lg text-gray-700">
                  {{ item.productName }}: {{ item.quantity }} pieces
                </p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-3xl font-bold text-green-600">{{ dispatch.totalPieces }}</p>
              <p class="text-sm text-gray-600">pieces</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const today = computed(() => new Date().toLocaleDateString('en-ET', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }))

const branches = ref([
  { id: 2, name: 'Piassa Branch', icon: '🏪' },
  { id: 3, name: 'Bole Branch', icon: '🏬' }
])

const selectedBranch = ref<any>(null)

const products = ref([
  { id: 1, name: 'White Bread', icon: '🍞', available: 450, quantity: 0 },
  { id: 2, name: 'Brown Bread', icon: '🥖', available: 380, quantity: 0 },
  { id: 3, name: 'Whole Cake', icon: '🎂', available: 50, quantity: 0 },
  { id: 4, name: 'Donut', icon: '🍩', available: 200, quantity: 0 },
  { id: 5, name: 'Croissant', icon: '🥐', available: 90, quantity: 0 },
  { id: 6, name: 'Muffin', icon: '🧁', available: 150, quantity: 0 }
])

const todayDispatches = ref<any[]>([])

const totalSending = computed(() => products.value.reduce((sum, p) => sum + (p.quantity || 0), 0))

const canSave = computed(() => {
  if (!selectedBranch.value || totalSending.value === 0) return false

  // Check no product exceeds available
  return !products.value.some(p => p.quantity > p.available)
})

function validateQuantity(product: any) {
  if (product.quantity > product.available) {
    // Don't allow more than available
    setTimeout(() => {
      product.quantity = product.available
    }, 100)
  }
}

function saveDispatch() {
  if (!canSave.value) return

  const items = products.value
    .filter(p => p.quantity > 0)
    .map(p => ({
      productName: p.name,
      quantity: p.quantity
    }))

  const dispatch = {
    branchName: selectedBranch.value.name,
    items,
    totalPieces: totalSending.value,
    time: new Date().toLocaleTimeString('en-ET', { hour: '2-digit', minute: '2-digit' })
  }

  // Add to today's dispatches
  todayDispatches.value.unshift(dispatch)

  // Update available stock (deduct what was sent)
  products.value.forEach(p => {
    if (p.quantity > 0) {
      p.available -= p.quantity
    }
  })

  // TODO: Send to API
  alert(`Dispatch saved!\n\nTo: ${selectedBranch.value.name}\nTotal: ${totalSending.value} pieces\n\nItems: ${items.map(i => `${i.productName}: ${i.quantity}`).join(', ')}`)

  // Reset
  clearQuantities()
  selectedBranch.value = null
}

function clearQuantities() {
  products.value.forEach(p => {
    p.quantity = 0
  })
}
</script>
