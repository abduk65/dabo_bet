<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Simple Header -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
      <h1 class="text-4xl font-bold text-gray-900">🍞 Record Production</h1>
      <p class="text-xl text-gray-600 mt-2">Write down what you baked today</p>
    </div>

    <!-- Today's Date -->
    <div class="bg-green-100 rounded-lg p-6 mb-6">
      <p class="text-lg font-medium text-gray-700">Date:</p>
      <p class="text-3xl font-bold text-green-600">{{ today }}</p>
    </div>

    <!-- Production Grid (One product per row) -->
    <div class="bg-white rounded-lg shadow-lg p-8">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">How many did you bake?</h2>

      <div class="space-y-6">
        <div v-for="product in products" :key="product.id" class="p-6 border-4 rounded-lg" :class="product.produced > 0 ? 'bg-green-50 border-green-400' : 'bg-white border-gray-300'">
          <div class="flex items-center gap-6">
            <!-- Product Icon & Name -->
            <div class="flex-1">
              <p class="text-4xl mb-2">{{ product.icon }}</p>
              <p class="text-2xl font-bold text-gray-900">{{ product.name }}</p>
            </div>

            <!-- Quantity Input (HUGE) -->
            <div class="text-center">
              <input
                v-model.number="product.produced"
                type="number"
                min="0"
                class="w-40 px-6 py-6 border-4 rounded-lg text-5xl font-bold text-center focus:ring-4 focus:ring-green-500"
                :class="product.produced > 0 ? 'bg-green-100 border-green-500' : 'bg-white border-gray-300'"
                placeholder="0"
              />
              <p class="text-lg text-gray-600 mt-2">pieces</p>
            </div>

            <!-- Waste/Damage (Optional) -->
            <div class="text-center">
              <label class="block text-sm font-medium text-gray-700 mb-2">Damaged?</label>
              <input
                v-model.number="product.damaged"
                type="number"
                min="0"
                class="w-32 px-4 py-4 border-4 rounded-lg text-3xl font-bold text-center"
                placeholder="0"
              />
              <p class="text-sm text-gray-600 mt-2">pieces</p>
            </div>
          </div>

          <!-- Net Production -->
          <div v-if="product.produced > 0" class="mt-4 p-4 bg-green-100 rounded-lg">
            <p class="text-xl font-bold text-green-700">
              Net Production: {{ product.produced - (product.damaged || 0) }} good pieces
              <span v-if="product.damaged > 0" class="text-red-600">({{ product.damaged }} damaged)</span>
            </p>
          </div>
        </div>
      </div>

      <!-- Total Summary -->
      <div class="mt-8 p-6 bg-blue-100 rounded-lg border-4 border-blue-300">
        <div class="grid grid-cols-3 gap-6 text-center">
          <div>
            <p class="text-lg font-medium text-gray-700">Total Produced</p>
            <p class="text-5xl font-bold text-blue-600">{{ totalProduced }}</p>
          </div>
          <div>
            <p class="text-lg font-medium text-gray-700">Total Damaged</p>
            <p class="text-5xl font-bold text-red-600">{{ totalDamaged }}</p>
          </div>
          <div>
            <p class="text-lg font-medium text-gray-700">Net Good</p>
            <p class="text-5xl font-bold text-green-600">{{ totalGood }}</p>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <button
        @click="saveProduction"
        :disabled="totalProduced === 0"
        class="w-full mt-8 px-8 py-8 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold text-3xl shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
      >
        💾 SAVE PRODUCTION
      </button>

      <!-- Clear Button -->
      <button
        @click="clearAll"
        class="w-full mt-4 px-6 py-4 bg-gray-500 text-white rounded-lg hover:bg-gray-600 font-bold text-xl"
      >
        Clear All
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const today = computed(() => new Date().toLocaleDateString('en-ET', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }))

const products = ref([
  { id: 1, name: 'White Bread', icon: '🍞', produced: 0, damaged: 0 },
  { id: 2, name: 'Brown Bread', icon: '🥖', produced: 0, damaged: 0 },
  { id: 3, name: 'Whole Cake', icon: '🎂', produced: 0, damaged: 0 },
  { id: 4, name: 'Donut', icon: '🍩', produced: 0, damaged: 0 },
  { id: 5, name: 'Croissant', icon: '🥐', produced: 0, damaged: 0 },
  { id: 6, name: 'Muffin', icon: '🧁', produced: 0, damaged: 0 }
])

const totalProduced = computed(() => products.value.reduce((sum, p) => sum + (p.produced || 0), 0))
const totalDamaged = computed(() => products.value.reduce((sum, p) => sum + (p.damaged || 0), 0))
const totalGood = computed(() => totalProduced.value - totalDamaged.value)

function saveProduction() {
  if (totalProduced.value === 0) {
    alert('No production to save!')
    return
  }

  const producedItems = products.value.filter(p => p.produced > 0)

  // TODO: Send to API
  alert(`Saved production!\n\nTotal Produced: ${totalProduced.value}\nDamaged: ${totalDamaged.value}\nGood: ${totalGood.value}\n\nItems: ${producedItems.map(p => `${p.name}: ${p.produced}`).join(', ')}`)

  // Clear after save
  clearAll()
}

function clearAll() {
  if (totalProduced.value > 0) {
    if (!confirm('Are you sure you want to clear all data?')) return
  }

  products.value.forEach(p => {
    p.produced = 0
    p.damaged = 0
  })
}
</script>
