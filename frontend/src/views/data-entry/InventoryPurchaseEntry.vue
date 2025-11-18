<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Simple Header -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
      <h1 class="text-4xl font-bold text-gray-900">📦 Record Purchase</h1>
      <p class="text-xl text-gray-600 mt-2">Write down what you bought today</p>
    </div>

    <!-- Today's Date (Big and Clear) -->
    <div class="bg-blue-100 rounded-lg p-6 mb-6">
      <p class="text-lg font-medium text-gray-700">Date:</p>
      <p class="text-3xl font-bold text-blue-600">{{ today }}</p>
    </div>

    <!-- Simple Entry Form -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">What did you buy?</h2>

      <div class="space-y-6">
        <!-- Material Type -->
        <div>
          <label class="block text-xl font-bold text-gray-900 mb-3">1. What item? *</label>
          <select v-model="currentEntry.materialType" class="w-full px-6 py-4 border-4 rounded-lg text-2xl font-semibold" required>
            <option value="">-- Choose item --</option>
            <option value="flour">🌾 Flour</option>
            <option value="sugar">🍬 Sugar</option>
            <option value="yeast">🧫 Yeast</option>
            <option value="salt">🧂 Salt</option>
            <option value="oil">🛢️ Oil</option>
            <option value="eggs">🥚 Eggs</option>
            <option value="milk">🥛 Milk</option>
            <option value="butter">🧈 Butter</option>
          </select>
        </div>

        <!-- Quantity -->
        <div>
          <label class="block text-xl font-bold text-gray-900 mb-3">2. How many? *</label>
          <div class="flex items-center gap-4">
            <input
              v-model.number="currentEntry.quantity"
              type="number"
              min="0"
              step="0.1"
              class="flex-1 px-6 py-6 border-4 rounded-lg text-4xl font-bold text-center"
              placeholder="0"
              required
            />
            <select v-model="currentEntry.unit" class="px-6 py-6 border-4 rounded-lg text-2xl font-semibold" required>
              <option value="kg">KG</option>
              <option value="pieces">Pieces</option>
              <option value="liters">Liters</option>
              <option value="bags">Bags</option>
            </select>
          </div>
        </div>

        <!-- Price -->
        <div>
          <label class="block text-xl font-bold text-gray-900 mb-3">3. Total price paid? *</label>
          <div class="flex items-center gap-4">
            <span class="text-3xl font-bold text-gray-700">ETB</span>
            <input
              v-model.number="currentEntry.totalPrice"
              type="number"
              min="0"
              step="0.01"
              class="flex-1 px-6 py-6 border-4 rounded-lg text-4xl font-bold text-center"
              placeholder="0.00"
              required
            />
          </div>
          <p v-if="currentEntry.quantity > 0 && currentEntry.totalPrice > 0" class="text-lg text-gray-600 mt-2">
            Price per {{ currentEntry.unit }}: {{ formatCurrency(currentEntry.totalPrice / currentEntry.quantity) }}
          </p>
        </div>

        <!-- Supplier (Optional) -->
        <div>
          <label class="block text-xl font-bold text-gray-900 mb-3">4. Who did you buy from? (optional)</label>
          <input
            v-model="currentEntry.supplier"
            type="text"
            class="w-full px-6 py-4 border-4 rounded-lg text-xl"
            placeholder="Supplier name..."
          />
        </div>

        <!-- Add Button -->
        <button
          @click="addToList"
          :disabled="!canAdd"
          class="w-full px-8 py-6 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold text-2xl disabled:opacity-50 disabled:cursor-not-allowed"
        >
          ✅ ADD TO LIST
        </button>
      </div>
    </div>

    <!-- List of Today's Purchases -->
    <div v-if="purchases.length > 0" class="bg-white rounded-lg shadow-lg p-8 mb-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Today's Purchases ({{ purchases.length }} items)</h2>

      <div class="space-y-4">
        <div v-for="(purchase, index) in purchases" :key="index" class="flex items-center justify-between p-6 border-4 rounded-lg bg-gray-50">
          <div class="flex-1">
            <p class="text-2xl font-bold text-gray-900">{{ getItemName(purchase.materialType) }}</p>
            <p class="text-xl text-gray-600 mt-1">{{ purchase.quantity }} {{ purchase.unit }} - {{ formatCurrency(purchase.totalPrice) }}</p>
            <p v-if="purchase.supplier" class="text-lg text-gray-500 mt-1">From: {{ purchase.supplier }}</p>
          </div>
          <button @click="removePurchase(index)" class="px-6 py-4 bg-red-500 text-white rounded-lg hover:bg-red-600 font-bold text-xl">
            ❌ Remove
          </button>
        </div>
      </div>

      <!-- Total -->
      <div class="mt-6 p-6 bg-blue-100 rounded-lg border-4 border-blue-300">
        <div class="flex items-center justify-between">
          <p class="text-2xl font-bold text-gray-900">TOTAL SPENT TODAY:</p>
          <p class="text-4xl font-bold text-blue-600">{{ formatCurrency(totalSpent) }}</p>
        </div>
      </div>

      <!-- Save Button -->
      <button
        @click="saveAllPurchases"
        class="w-full mt-6 px-8 py-8 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold text-3xl shadow-lg"
      >
        💾 SAVE ALL PURCHASES
      </button>
    </div>

    <!-- Empty State -->
    <div v-else class="bg-gray-100 rounded-lg p-12 text-center">
      <p class="text-2xl text-gray-500">No purchases added yet. Fill the form above to add items.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const today = computed(() => new Date().toLocaleDateString('en-ET', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }))

const currentEntry = ref({
  materialType: '',
  quantity: 0,
  unit: 'kg',
  totalPrice: 0,
  supplier: ''
})

const purchases = ref<any[]>([])

const canAdd = computed(() => {
  return currentEntry.value.materialType && currentEntry.value.quantity > 0 && currentEntry.value.totalPrice > 0
})

const totalSpent = computed(() => {
  return purchases.value.reduce((sum, p) => sum + p.totalPrice, 0)
})

function getItemName(type: string): string {
  const names: Record<string, string> = {
    flour: '🌾 Flour',
    sugar: '🍬 Sugar',
    yeast: '🧫 Yeast',
    salt: '🧂 Salt',
    oil: '🛢️ Oil',
    eggs: '🥚 Eggs',
    milk: '🥛 Milk',
    butter: '🧈 Butter'
  }
  return names[type] || type
}

function addToList() {
  if (!canAdd.value) return

  purchases.value.push({ ...currentEntry.value })

  // Reset form
  currentEntry.value = {
    materialType: '',
    quantity: 0,
    unit: 'kg',
    totalPrice: 0,
    supplier: ''
  }
}

function removePurchase(index: number) {
  purchases.value.splice(index, 1)
}

function saveAllPurchases() {
  if (purchases.value.length === 0) {
    alert('No purchases to save!')
    return
  }

  // TODO: Send to API
  alert(`Saved ${purchases.value.length} purchases!\nTotal: ${formatCurrency(totalSpent.value)}`)

  // Clear after save
  purchases.value = []
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-ET', {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount)
}
</script>
