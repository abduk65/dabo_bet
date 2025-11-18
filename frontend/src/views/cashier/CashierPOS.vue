<template>
  <div class="h-screen flex flex-col bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b px-6 py-4 flex items-center justify-between">
      <div>
        <h1 class="text-xl font-bold text-gray-900">{{ branchName }} - POS</h1>
        <p class="text-sm text-gray-600">{{ today }}</p>
      </div>
      <div class="text-right">
        <p class="text-sm text-gray-600">Cashier: {{ cashierName }}</p>
        <p class="text-lg font-bold text-blue-600">Today's Sales: {{ formatCurrency(todaySales) }}</p>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 grid grid-cols-3 gap-6 p-6 overflow-hidden">
      <!-- Product Selection -->
      <div class="col-span-2 bg-white rounded-lg shadow p-6 overflow-auto">
        <h2 class="text-lg font-bold mb-4">Products</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          <button
            v-for="product in products"
            :key="product.id"
            @click="addToCart(product)"
            class="p-6 border-2 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all text-left"
            :class="product.stock <= 0 ? 'opacity-50 cursor-not-allowed' : ''"
            :disabled="product.stock <= 0"
          >
            <div class="text-3xl mb-2">{{ product.icon }}</div>
            <p class="font-bold text-gray-900">{{ product.name }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ formatCurrency(product.price) }}</p>
            <p class="text-xs text-gray-500 mt-1">Stock: {{ product.stock }}</p>
          </button>
        </div>
      </div>

      <!-- Cart & Checkout -->
      <div class="bg-white rounded-lg shadow p-6 flex flex-col">
        <h2 class="text-lg font-bold mb-4">Current Sale</h2>

        <!-- Cart Items -->
        <div class="flex-1 overflow-auto space-y-2 mb-4">
          <div v-if="cart.length === 0" class="text-center py-12 text-gray-500">
            <p class="text-sm">No items in cart</p>
          </div>

          <div v-for="item in cart" :key="item.id" class="flex items-center justify-between p-3 border rounded-lg">
            <div class="flex-1">
              <p class="font-semibold text-gray-900">{{ item.name }}</p>
              <p class="text-sm text-gray-600">{{ formatCurrency(item.price) }} each</p>
            </div>
            <div class="flex items-center gap-2">
              <button @click="decreaseQty(item)" class="w-8 h-8 rounded-lg bg-gray-200 hover:bg-gray-300">-</button>
              <span class="w-12 text-center font-bold">{{ item.quantity }}</span>
              <button @click="increaseQty(item)" class="w-8 h-8 rounded-lg bg-gray-200 hover:bg-gray-300">+</button>
              <button @click="removeFromCart(item)" class="ml-2 w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600">×</button>
            </div>
          </div>
        </div>

        <!-- Totals -->
        <div class="border-t pt-4 space-y-2">
          <div class="flex justify-between text-sm">
            <span class="text-gray-600">Subtotal:</span>
            <span class="font-semibold">{{ formatCurrency(subtotal) }}</span>
          </div>
          <div class="flex justify-between text-lg font-bold">
            <span>Total:</span>
            <span class="text-blue-600">{{ formatCurrency(total) }}</span>
          </div>
        </div>

        <!-- Payment Input -->
        <div class="mt-4 space-y-3">
          <div>
            <label class="block text-sm font-medium mb-1">Cash Received</label>
            <input
              v-model.number="cashReceived"
              type="number"
              class="w-full px-4 py-3 border-2 rounded-lg text-lg font-bold"
              placeholder="0.00"
              @focus="$event.target.select()"
            />
          </div>

          <div v-if="cashReceived > 0" class="p-3 bg-gray-50 rounded-lg">
            <div class="flex justify-between">
              <span class="text-sm text-gray-600">Change:</span>
              <span class="text-lg font-bold" :class="change >= 0 ? 'text-green-600' : 'text-red-600'">
                {{ formatCurrency(change) }}
              </span>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 space-y-2">
          <button
            @click="completeSale"
            :disabled="cart.length === 0 || cashReceived < total"
            class="w-full px-6 py-4 bg-green-600 text-white rounded-lg font-bold text-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Complete Sale
          </button>
          <button
            @click="clearCart"
            :disabled="cart.length === 0"
            class="w-full px-6 py-3 border-2 rounded-lg font-semibold hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Clear Cart
          </button>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-8 w-full max-w-md text-center">
        <div class="text-6xl mb-4">✓</div>
        <h3 class="text-2xl font-bold text-green-600 mb-2">Sale Completed!</h3>
        <p class="text-3xl font-bold text-gray-900 mb-2">{{ formatCurrency(total) }}</p>
        <p class="text-gray-600 mb-6">Change: {{ formatCurrency(change) }}</p>
        <button @click="closeSuccessModal" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
          New Sale
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const branchName = ref('Piassa Branch')
const cashierName = ref('Ahmed M.')
const todaySales = ref(12500)

const today = computed(() => new Date().toLocaleDateString('en-ET', { weekday: 'long', month: 'short', day: 'numeric' }))

const products = ref([
  { id: 1, name: 'White Bread', price: 25, stock: 450, icon: '🍞' },
  { id: 2, name: 'Brown Bread', price: 30, stock: 380, icon: '🥖' },
  { id: 3, name: 'Cake (Slice)', price: 50, stock: 120, icon: '🍰' },
  { id: 4, name: 'Donut', price: 15, stock: 200, icon: '🍩' },
  { id: 5, name: 'Croissant', price: 35, stock: 90, icon: '🥐' },
  { id: 6, name: 'Muffin', price: 20, stock: 150, icon: '🧁' }
])

const cart = ref<any[]>([])
const cashReceived = ref(0)
const showSuccessModal = ref(false)

const subtotal = computed(() => {
  return cart.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
})

const total = computed(() => subtotal.value)

const change = computed(() => cashReceived.value - total.value)

function addToCart(product: any) {
  if (product.stock <= 0) return

  const existing = cart.value.find(item => item.id === product.id)
  if (existing) {
    if (existing.quantity < product.stock) {
      existing.quantity++
    }
  } else {
    cart.value.push({
      ...product,
      quantity: 1
    })
  }
}

function increaseQty(item: any) {
  const product = products.value.find(p => p.id === item.id)
  if (item.quantity < product.stock) {
    item.quantity++
  }
}

function decreaseQty(item: any) {
  if (item.quantity > 1) {
    item.quantity--
  }
}

function removeFromCart(item: any) {
  const index = cart.value.findIndex(i => i.id === item.id)
  if (index > -1) {
    cart.value.splice(index, 1)
  }
}

function clearCart() {
  cart.value = []
  cashReceived.value = 0
}

function completeSale() {
  if (cart.value.length === 0) return
  if (cashReceived.value < total.value) {
    alert('Insufficient cash received')
    return
  }

  // TODO: Send sale to API

  // Update stock
  cart.value.forEach(item => {
    const product = products.value.find(p => p.id === item.id)
    if (product) {
      product.stock -= item.quantity
    }
  })

  // Update today's sales
  todaySales.value += total.value

  showSuccessModal.value = true
}

function closeSuccessModal() {
  showSuccessModal.value = false
  clearCart()
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-ET', {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount)
}
</script>
