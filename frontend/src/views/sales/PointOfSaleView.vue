<template>
  <div class="flex flex-col h-[calc(100vh-theme(spacing.32))]">
    <PageHeader title="Point of Sale" description="Process customer sales and payments" />

    <div class="flex-1 flex gap-6 overflow-hidden">
      <!-- Product Selection (Left Side) -->
      <div class="flex-1 flex flex-col">
        <!-- Product Search & Filters -->
        <div class="card mb-4">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search products..."
            class="input"
          />
        </div>

        <!-- Product Type Filter -->
        <div class="flex gap-2 mb-4">
          <button
            v-for="type in productFilters"
            :key="type.value"
            @click="selectedFilter = type.value"
            :class="[
              'px-3 py-2 text-sm font-medium rounded-md',
              selectedFilter === type.value
                ? 'bg-primary-600 text-white'
                : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
            ]"
          >
            {{ type.label }}
          </button>
        </div>

        <!-- Products Grid -->
        <div class="flex-1 overflow-y-auto">
          <div v-if="loadingProducts" class="flex justify-center items-center h-full">
            <LoadingSpinner text="Loading products..." />
          </div>
          <div v-else class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            <button
              v-for="product in filteredProducts"
              :key="product.id"
              @click="addToCart(product)"
              class="card hover:shadow-lg transition-shadow text-left p-4"
            >
              <h3 class="font-medium text-gray-900">{{ product.product_name }}</h3>
              <p class="text-sm text-gray-500">{{ product.product_code }}</p>
              <p class="text-lg font-semibold text-primary-600 mt-2">
                {{ formatCurrency(getCurrentPrice(product)) }}
              </p>
              <span :class="['text-xs inline-block mt-2', getTypeBadgeClass(product.product_type)]">
                {{ product.product_type }}
              </span>
            </button>
          </div>
        </div>
      </div>

      <!-- Cart & Checkout (Right Side) -->
      <div class="w-96 flex flex-col card">
        <!-- Customer Selection -->
        <div class="pb-4 border-b">
          <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
          <select v-model="selectedCustomerId" class="input">
            <option :value="null">Walk-in Customer</option>
            <option v-for="customer in customers" :key="customer.id" :value="customer.id">
              {{ customer.customer_name }} ({{ customer.customer_type }})
            </option>
          </select>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto py-4">
          <div v-if="cart.length === 0" class="text-center text-gray-500 py-8">
            <p>Cart is empty</p>
            <p class="text-sm mt-1">Add products to start a sale</p>
          </div>
          <div v-else class="space-y-2">
            <div
              v-for="(item, index) in cart"
              :key="index"
              class="flex items-center gap-2 p-2 rounded border"
            >
              <div class="flex-1">
                <p class="text-sm font-medium">{{ item.product_name }}</p>
                <p class="text-xs text-gray-500">{{ formatCurrency(item.unit_price) }} each</p>
              </div>
              <div class="flex items-center gap-2">
                <button
                  @click="decreaseQuantity(index)"
                  class="w-7 h-7 rounded bg-gray-200 hover:bg-gray-300 flex items-center justify-center"
                >
                  -
                </button>
                <span class="w-8 text-center">{{ item.quantity }}</span>
                <button
                  @click="increaseQuantity(index)"
                  class="w-7 h-7 rounded bg-gray-200 hover:bg-gray-300 flex items-center justify-center"
                >
                  +
                </button>
                <button
                  @click="removeFromCart(index)"
                  class="w-7 h-7 rounded bg-red-100 hover:bg-red-200 text-red-600 flex items-center justify-center"
                >
                  ×
                </button>
              </div>
              <div class="text-right">
                <p class="font-semibold">{{ formatCurrency(item.line_total) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Summary -->
        <div class="border-t pt-4 space-y-2">
          <div class="flex justify-between text-sm">
            <span class="text-gray-600">Subtotal:</span>
            <span class="font-medium">{{ formatCurrency(subtotal) }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-600">Tax (0%):</span>
            <span class="font-medium">{{ formatCurrency(tax) }}</span>
          </div>
          <div class="flex justify-between text-lg font-bold border-t pt-2">
            <span>Total:</span>
            <span class="text-primary-600">{{ formatCurrency(total) }}</span>
          </div>
        </div>

        <!-- Payment Method -->
        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
          <select v-model="paymentType" class="input">
            <option value="cash">Cash</option>
            <option value="credit">Credit</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="mobile_money">Mobile Money</option>
          </select>
        </div>

        <!-- Actions -->
        <div class="mt-4 space-y-2">
          <button
            @click="processPayment"
            :disabled="cart.length === 0 || processing"
            class="btn-primary w-full py-3 text-lg"
          >
            {{ processing ? 'Processing...' : `Pay ${formatCurrency(total)}` }}
          </button>
          <button
            @click="clearCart"
            :disabled="cart.length === 0"
            class="btn w-full"
          >
            Clear Cart
          </button>
        </div>
      </div>
    </div>

    <!-- Payment Success Modal -->
    <Modal v-model:show="showSuccessModal" title="Sale Completed" size="md">
      <div class="text-center py-6">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
          <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Payment Successful!</h3>
        <p class="text-sm text-gray-500 mb-1">Sale Number: <span class="font-medium">{{ completedSale?.sale_number }}</span></p>
        <p class="text-2xl font-bold text-primary-600 mt-4">{{ formatCurrency(completedSale?.total_amount || 0) }}</p>
      </div>
      <template #footer>
        <div class="flex gap-3 justify-end">
          <button @click="printReceipt" class="btn">Print Receipt</button>
          <button @click="newSale" class="btn-primary">New Sale</button>
        </div>
      </template>
    </Modal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { productionService, type Product } from '@/services/production.service'
import { salesService, type Customer, type Sale } from '@/services/sales.service'
import PageHeader from '@/components/common/PageHeader.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import Modal from '@/components/common/Modal.vue'

const authStore = useAuthStore()

const loadingProducts = ref(true)
const products = ref<Product[]>([])
const customers = ref<Customer[]>([])
const searchQuery = ref('')
const selectedFilter = ref<string | null>(null)
const selectedCustomerId = ref<number | null>(null)
const paymentType = ref('cash')
const processing = ref(false)

interface CartItem {
  product_id: number
  product_name: string
  product_code: string
  quantity: number
  unit_price: number
  discount_percentage: number
  line_total: number
}

const cart = ref<CartItem[]>([])
const showSuccessModal = ref(false)
const completedSale = ref<Sale | null>(null)

const productFilters = [
  { value: null, label: 'All' },
  { value: 'bread', label: 'Bread' },
  { value: 'pastry', label: 'Pastry' },
  { value: 'cake', label: 'Cake' }
]

const filteredProducts = computed(() => {
  let filtered = products.value

  if (selectedFilter.value) {
    filtered = filtered.filter(p => p.product_type === selectedFilter.value)
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(p =>
      p.product_name.toLowerCase().includes(query) ||
      p.product_code.toLowerCase().includes(query)
    )
  }

  return filtered
})

const subtotal = computed(() => {
  return cart.value.reduce((sum, item) => sum + item.line_total, 0)
})

const tax = computed(() => {
  return 0 // Tax calculation can be added here
})

const total = computed(() => {
  return subtotal.value + tax.value
})

onMounted(async () => {
  await Promise.all([loadProducts(), loadCustomers()])
})

async function loadProducts() {
  try {
    loadingProducts.value = true
    products.value = await productionService.getProducts({ type: undefined })
  } catch (err) {
    console.error('Failed to load products:', err)
  } finally {
    loadingProducts.value = false
  }
}

async function loadCustomers() {
  try {
    customers.value = await salesService.getCustomers()
  } catch (err) {
    console.error('Failed to load customers:', err)
  }
}

function getCurrentPrice(product: Product): number {
  // Get current price from product prices
  // For now, return a default price
  return 50 // This should come from product.prices
}

function addToCart(product: Product) {
  const existingItem = cart.value.find(item => item.product_id === product.id)

  if (existingItem) {
    existingItem.quantity++
    existingItem.line_total = existingItem.quantity * existingItem.unit_price * (1 - existingItem.discount_percentage / 100)
  } else {
    const unitPrice = getCurrentPrice(product)
    cart.value.push({
      product_id: product.id,
      product_name: product.product_name,
      product_code: product.product_code,
      quantity: 1,
      unit_price: unitPrice,
      discount_percentage: 0,
      line_total: unitPrice
    })
  }
}

function increaseQuantity(index: number) {
  const item = cart.value[index]
  item.quantity++
  item.line_total = item.quantity * item.unit_price * (1 - item.discount_percentage / 100)
}

function decreaseQuantity(index: number) {
  const item = cart.value[index]
  if (item.quantity > 1) {
    item.quantity--
    item.line_total = item.quantity * item.unit_price * (1 - item.discount_percentage / 100)
  } else {
    removeFromCart(index)
  }
}

function removeFromCart(index: number) {
  cart.value.splice(index, 1)
}

function clearCart() {
  cart.value = []
  selectedCustomerId.value = null
  paymentType.value = 'cash'
}

async function processPayment() {
  if (cart.value.length === 0) return

  try {
    processing.value = true

    const saleData = {
      branch_id: authStore.user?.branch_id || 1,
      customer_id: selectedCustomerId.value,
      sale_date: new Date().toISOString().split('T')[0],
      payment_type: paymentType.value,
      subtotal_amount: subtotal.value,
      tax_amount: tax.value,
      total_amount: total.value,
      amount_paid: total.value,
      balance_due: 0,
      status: 'completed',
      items: cart.value
    }

    const sale = await salesService.createSale(saleData)
    completedSale.value = sale
    showSuccessModal.value = true
  } catch (err: any) {
    alert(err.response?.data?.message || 'Failed to process payment')
  } finally {
    processing.value = false
  }
}

function printReceipt() {
  // Implement print functionality
  console.log('Print receipt for sale:', completedSale.value)
  window.print()
}

function newSale() {
  clearCart()
  showSuccessModal.value = false
  completedSale.value = null
}

function getTypeBadgeClass(type: string): string {
  const classes: Record<string, string> = {
    bread: 'badge-primary',
    pastry: 'badge-purple',
    cake: 'badge-pink',
    other: 'badge-gray'
  }
  return classes[type] || 'badge-gray'
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-ET', {
    style: 'currency',
    currency: 'ETB',
    minimumFractionDigits: 0
  }).format(amount)
}
</script>
