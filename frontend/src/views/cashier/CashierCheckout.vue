<template>
  <div class="cashier-checkout min-h-screen bg-gray-50">
    <!-- Header with timer -->
    <div class="bg-white shadow-sm sticky top-0 z-10">
      <div class="px-4 py-3">
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-bold text-gray-900">New Sale</h1>

          <!-- Timer -->
          <div class="flex items-center gap-2">
            <ClockIcon :class="['h-5 w-5', getTimerColor()]" />
            <span :class="['text-lg font-bold', getTimerColor()]">
              {{ formatTimer(elapsedTime) }}
            </span>
            <span class="text-xs text-gray-500">/ 20:00</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Wizard -->
    <SwipeableSteps
      v-model="currentStep"
      :steps="steps"
      :can-proceed="canProceedToNextStep"
      finish-button-text="Complete Sale"
      @finish="handleFinish"
    >
      <!-- Step 1: Products -->
      <template #step-0>
        <div class="p-4 space-y-4">
          <h2 class="text-lg font-semibold text-gray-900">Select Products</h2>

          <!-- Product search -->
          <div class="relative">
            <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
            <input
              v-model="productSearch"
              type="text"
              placeholder="Search products..."
              class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-base focus:ring-primary-500 focus:border-primary-500"
            />
          </div>

          <!-- Product grid -->
          <div class="grid grid-cols-2 gap-3">
            <button
              v-for="product in filteredProducts"
              :key="product.id"
              @click="addProduct(product)"
              class="flex flex-col items-center gap-2 p-4 bg-white border-2 border-gray-200 rounded-lg active:bg-primary-50 active:border-primary-500 transition-colors"
            >
              <div v-if="product.image" class="h-16 w-16 rounded overflow-hidden">
                <img :src="product.image" :alt="product.name" class="h-full w-full object-cover" />
              </div>
              <div v-else class="h-16 w-16 rounded bg-gray-100 flex items-center justify-center">
                <CubeIcon class="h-8 w-8 text-gray-400" />
              </div>

              <p class="text-sm font-medium text-gray-900 text-center line-clamp-2">
                {{ product.name }}
              </p>
              <p class="text-base font-bold text-primary-600">
                {{ formatCurrency(product.price) }}
              </p>
            </button>
          </div>

          <!-- Selected items -->
          <div v-if="selectedItems.length > 0" class="mt-6">
            <h3 class="text-base font-semibold text-gray-900 mb-3">
              Cart ({{ selectedItems.length }})
            </h3>

            <div class="space-y-2">
              <div
                v-for="(item, index) in selectedItems"
                :key="index"
                class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg p-3"
              >
                <div class="flex-1">
                  <p class="text-sm font-medium text-gray-900">{{ item.product.name }}</p>
                  <p class="text-xs text-gray-500">
                    {{ formatCurrency(item.product.price) }} × {{ item.quantity }}
                  </p>
                </div>

                <!-- Quantity controls -->
                <div class="flex items-center gap-2">
                  <button
                    @click="decrementQuantity(index)"
                    class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center active:bg-gray-200"
                  >
                    <MinusIcon class="h-5 w-5 text-gray-600" />
                  </button>

                  <span class="text-lg font-bold text-gray-900 w-8 text-center">
                    {{ item.quantity }}
                  </span>

                  <button
                    @click="incrementQuantity(index)"
                    class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center active:bg-gray-200"
                  >
                    <PlusIcon class="h-5 w-5 text-gray-600" />
                  </button>
                </div>

                <!-- Subtotal -->
                <p class="text-base font-bold text-gray-900 w-24 text-right">
                  {{ formatCurrency(item.product.price * item.quantity) }}
                </p>

                <!-- Remove button -->
                <button
                  @click="removeItem(index)"
                  class="h-10 w-10 rounded-lg flex items-center justify-center text-red-600 active:bg-red-50"
                >
                  <TrashIcon class="h-5 w-5" />
                </button>
              </div>
            </div>

            <!-- Subtotal -->
            <div class="mt-4 p-4 bg-primary-50 border border-primary-200 rounded-lg">
              <div class="flex items-center justify-between">
                <span class="text-base font-semibold text-gray-900">Subtotal</span>
                <span class="text-2xl font-bold text-primary-600">
                  {{ formatCurrency(subtotal) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </template>

      <!-- Step 2: Commission -->
      <template #step-1>
        <div class="p-4 space-y-6">
          <h2 class="text-lg font-semibold text-gray-900">Commission</h2>

          <!-- Commission breakdown -->
          <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600">Subtotal</span>
              <span class="text-base font-semibold text-gray-900">
                {{ formatCurrency(subtotal) }}
              </span>
            </div>

            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600">Commission Rate</span>
              <span class="text-base font-semibold text-gray-900">
                {{ commissionRate }}%
              </span>
            </div>

            <div class="h-px bg-gray-200"></div>

            <div class="flex items-center justify-between">
              <span class="text-base font-semibold text-gray-900">Commission Amount</span>
              <span class="text-xl font-bold text-green-600">
                {{ formatCurrency(commissionAmount) }}
              </span>
            </div>

            <div class="flex items-center justify-between">
              <span class="text-base font-semibold text-gray-900">Total Due</span>
              <span class="text-2xl font-bold text-primary-600">
                {{ formatCurrency(totalDue) }}
              </span>
            </div>
          </div>

          <!-- Commission info -->
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
              <InformationCircleIcon class="h-5 w-5 text-blue-600 flex-shrink-0 mt-0.5" />
              <div class="flex-1">
                <p class="text-sm font-medium text-blue-900">Commission Breakdown</p>
                <p class="text-xs text-blue-700 mt-1">
                  Your {{ commissionRate }}% commission on this sale is {{ formatCurrency(commissionAmount) }}.
                  This will be added to your daily totals.
                </p>
              </div>
            </div>
          </div>
        </div>
      </template>

      <!-- Step 3: Cash Payment -->
      <template #step-2>
        <div class="p-4 space-y-6">
          <h2 class="text-lg font-semibold text-gray-900">Cash Payment</h2>

          <!-- Total due (prominent) -->
          <div class="bg-primary-50 border-2 border-primary-300 rounded-lg p-6 text-center">
            <p class="text-sm font-medium text-primary-700 mb-2">Total Due</p>
            <p class="text-4xl font-bold text-primary-900">
              {{ formatCurrency(totalDue) }}
            </p>
          </div>

          <!-- Cash received input -->
          <div>
            <CurrencyInput
              v-model="cashReceived"
              label="Cash Received"
              :large="true"
              :min="0"
              :show-formatted-value="true"
              class="mb-4"
            />

            <!-- Quick amount buttons -->
            <div class="grid grid-cols-3 gap-2">
              <button
                v-for="amount in quickAmounts"
                :key="amount"
                @click="cashReceived = amount"
                class="h-14 px-4 py-2 bg-white border-2 border-gray-300 rounded-lg text-base font-semibold text-gray-900 active:bg-primary-50 active:border-primary-500"
              >
                {{ formatCurrency(amount, true) }}
              </button>

              <button
                @click="cashReceived = totalDue"
                class="h-14 px-4 py-2 bg-green-100 border-2 border-green-300 rounded-lg text-base font-semibold text-green-900 active:bg-green-200"
              >
                Exact
              </button>
            </div>
          </div>

          <!-- Change calculation -->
          <div v-if="cashReceived && cashReceived >= totalDue" class="bg-green-50 border-2 border-green-300 rounded-lg p-6">
            <div class="text-center">
              <p class="text-sm font-medium text-green-700 mb-2">Change to Give</p>
              <p class="text-4xl font-bold text-green-900">
                {{ formatCurrency(change) }}
              </p>
            </div>
          </div>

          <!-- Insufficient cash warning -->
          <div v-else-if="cashReceived && cashReceived < totalDue" class="bg-red-50 border-2 border-red-300 rounded-lg p-4">
            <div class="flex items-start gap-3">
              <ExclamationTriangleIcon class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" />
              <div>
                <p class="text-sm font-medium text-red-900">Insufficient Amount</p>
                <p class="text-xs text-red-700 mt-1">
                  Need {{ formatCurrency(totalDue - (cashReceived || 0)) }} more
                </p>
              </div>
            </div>
          </div>
        </div>
      </template>

      <!-- Step 4: Review & Submit -->
      <template #step-3>
        <div class="p-4 space-y-6">
          <h2 class="text-lg font-semibold text-gray-900">Review Sale</h2>

          <!-- Summary -->
          <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-200">
            <!-- Items -->
            <div class="p-4">
              <p class="text-sm font-medium text-gray-600 mb-3">Items ({{ selectedItems.length }})</p>
              <div class="space-y-2">
                <div
                  v-for="(item, index) in selectedItems"
                  :key="index"
                  class="flex items-center justify-between text-sm"
                >
                  <span class="text-gray-900">
                    {{ item.quantity }}× {{ item.product.name }}
                  </span>
                  <span class="font-semibold text-gray-900">
                    {{ formatCurrency(item.product.price * item.quantity) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Totals -->
            <div class="p-4 space-y-2">
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-semibold text-gray-900">{{ formatCurrency(subtotal) }}</span>
              </div>
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600">Commission ({{ commissionRate }}%)</span>
                <span class="font-semibold text-green-600">{{ formatCurrency(commissionAmount) }}</span>
              </div>
              <div class="h-px bg-gray-200 my-2"></div>
              <div class="flex items-center justify-between">
                <span class="text-base font-semibold text-gray-900">Total</span>
                <span class="text-xl font-bold text-primary-600">{{ formatCurrency(totalDue) }}</span>
              </div>
            </div>

            <!-- Payment -->
            <div class="p-4">
              <div class="flex items-center justify-between text-sm mb-2">
                <span class="text-gray-600">Cash Received</span>
                <span class="font-semibold text-gray-900">{{ formatCurrency(cashReceived || 0) }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-base font-semibold text-gray-900">Change</span>
                <span class="text-xl font-bold text-green-600">{{ formatCurrency(change) }}</span>
              </div>
            </div>
          </div>

          <!-- Variance check -->
          <div :class="['border-2 rounded-lg p-4', getVarianceClass()]">
            <div class="flex items-start gap-3">
              <component :is="getVarianceIcon()" :class="['h-6 w-6 flex-shrink-0', getVarianceIconColor()]" />
              <div class="flex-1">
                <p :class="['text-sm font-semibold', getVarianceTextColor()]">
                  {{ getVarianceMessage() }}
                </p>
                <p :class="['text-xs mt-1', getVarianceTextColor()]">
                  Time: {{ formatTimer(elapsedTime) }} (Target: 20:00)
                </p>
              </div>
            </div>
          </div>

          <!-- Performance feedback -->
          <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <p class="text-xs font-medium text-gray-700 mb-2">Transaction Summary</p>
            <div class="grid grid-cols-2 gap-3 text-xs">
              <div>
                <span class="text-gray-600">Speed:</span>
                <span :class="['ml-2 font-semibold', getSpeedColor()]">
                  {{ getSpeedRating() }}
                </span>
              </div>
              <div>
                <span class="text-gray-600">Accuracy:</span>
                <span class="ml-2 font-semibold text-green-600">100%</span>
              </div>
            </div>
          </div>
        </div>
      </template>
    </SwipeableSteps>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import {
  ClockIcon,
  MagnifyingGlassIcon,
  CubeIcon,
  MinusIcon,
  PlusIcon,
  TrashIcon,
  InformationCircleIcon,
  ExclamationTriangleIcon,
  CheckCircleIcon
} from '@heroicons/vue/24/outline'
import SwipeableSteps from '@/components/mobile/SwipeableSteps.vue'
import CurrencyInput from '@/components/forms/CurrencyInput.vue'
import { formatCurrency } from '@/utils/formatters'
import { useNotification } from '@/composables/useNotification'

const router = useRouter()
const { notify } = useNotification()

// State
const currentStep = ref(0)
const productSearch = ref('')
const selectedItems = ref<Array<{ product: any; quantity: number }>>([])
const cashReceived = ref<number | null>(null)
const elapsedTime = ref(0)
const timerInterval = ref<number>()

const commissionRate = ref(2.5) // 2.5% commission
const TARGET_TIME = 20 * 60 // 20 minutes in seconds

// Mock products
const products = ref([
  { id: 1, name: 'White Bread', price: 25, image: null },
  { id: 2, name: 'Brown Bread', price: 30, image: null },
  { id: 3, name: 'Croissant', price: 15, image: null },
  { id: 4, name: 'Cake (Slice)', price: 40, image: null },
  { id: 5, name: 'Cookies (Pack)', price: 35, image: null },
  { id: 6, name: 'Danish Pastry', price: 20, image: null }
])

const steps = [
  { id: 0, label: 'Products', description: 'Select items' },
  { id: 1, label: 'Commission', description: 'Review earnings' },
  { id: 2, label: 'Payment', description: 'Receive cash' },
  { id: 3, label: 'Review', description: 'Confirm sale' }
]

// Computed
const filteredProducts = computed(() => {
  if (!productSearch.value) return products.value

  const search = productSearch.value.toLowerCase()
  return products.value.filter(p => p.name.toLowerCase().includes(search))
})

const subtotal = computed(() => {
  return selectedItems.value.reduce((sum, item) => {
    return sum + (item.product.price * item.quantity)
  }, 0)
})

const commissionAmount = computed(() => {
  return subtotal.value * (commissionRate.value / 100)
})

const totalDue = computed(() => {
  return subtotal.value + commissionAmount.value
})

const change = computed(() => {
  if (!cashReceived.value) return 0
  return Math.max(0, cashReceived.value - totalDue.value)
})

const quickAmounts = computed(() => {
  const amounts = [50, 100, 200, 500, 1000]
  return amounts.filter(a => a >= totalDue.value).slice(0, 5)
})

const canProceedToNextStep = computed(() => {
  switch (currentStep.value) {
    case 0: // Products step
      return selectedItems.value.length > 0
    case 1: // Commission step
      return true
    case 2: // Payment step
      return cashReceived.value !== null && cashReceived.value >= totalDue.value
    case 3: // Review step
      return true
    default:
      return false
  }
})

// Methods
function startTimer() {
  elapsedTime.value = 0
  timerInterval.value = window.setInterval(() => {
    elapsedTime.value++
  }, 1000)
}

function stopTimer() {
  if (timerInterval.value) {
    clearInterval(timerInterval.value)
  }
}

function formatTimer(seconds: number): string {
  const mins = Math.floor(seconds / 60)
  const secs = seconds % 60
  return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`
}

function getTimerColor(): string {
  if (elapsedTime.value < TARGET_TIME) return 'text-green-600'
  if (elapsedTime.value < TARGET_TIME * 1.5) return 'text-yellow-600'
  return 'text-red-600'
}

function addProduct(product: any) {
  const existingIndex = selectedItems.value.findIndex(item => item.product.id === product.id)

  if (existingIndex > -1) {
    selectedItems.value[existingIndex].quantity++
  } else {
    selectedItems.value.push({ product, quantity: 1 })
  }
}

function incrementQuantity(index: number) {
  selectedItems.value[index].quantity++
}

function decrementQuantity(index: number) {
  if (selectedItems.value[index].quantity > 1) {
    selectedItems.value[index].quantity--
  } else {
    removeItem(index)
  }
}

function removeItem(index: number) {
  selectedItems.value.splice(index, 1)
}

function getVarianceClass(): string {
  const variance = (elapsedTime.value / TARGET_TIME) * 100 - 100

  if (variance < 1) return 'bg-green-50 border-green-300'
  if (variance < 3) return 'bg-yellow-50 border-yellow-300'
  return 'bg-red-50 border-red-300'
}

function getVarianceIcon() {
  const variance = (elapsedTime.value / TARGET_TIME) * 100 - 100

  if (variance < 1) return CheckCircleIcon
  return ExclamationTriangleIcon
}

function getVarianceIconColor(): string {
  const variance = (elapsedTime.value / TARGET_TIME) * 100 - 100

  if (variance < 1) return 'text-green-600'
  if (variance < 3) return 'text-yellow-600'
  return 'text-red-600'
}

function getVarianceTextColor(): string {
  const variance = (elapsedTime.value / TARGET_TIME) * 100 - 100

  if (variance < 1) return 'text-green-900'
  if (variance < 3) return 'text-yellow-900'
  return 'text-red-900'
}

function getVarianceMessage(): string {
  const variance = (elapsedTime.value / TARGET_TIME) * 100 - 100

  if (variance < 1) return 'Excellent! Under target time'
  if (variance < 3) return 'Good! Slightly over target'
  return 'Slow - Try to speed up next time'
}

function getSpeedRating(): string {
  const variance = (elapsedTime.value / TARGET_TIME) * 100 - 100

  if (variance < 1) return 'Excellent'
  if (variance < 3) return 'Good'
  return 'Needs Improvement'
}

function getSpeedColor(): string {
  const variance = (elapsedTime.value / TARGET_TIME) * 100 - 100

  if (variance < 1) return 'text-green-600'
  if (variance < 3) return 'text-yellow-600'
  return 'text-red-600'
}

async function handleFinish() {
  try {
    // TODO: Submit to backend
    stopTimer()

    notify('success', 'Sale completed successfully!')

    // Show performance feedback
    const variance = (elapsedTime.value / TARGET_TIME) * 100 - 100
    if (variance < 1) {
      notify('success', 'Great job! You completed this under 20 minutes', '', 3000)
    }

    // Reset and navigate
    setTimeout(() => {
      router.push('/cashier/dashboard')
    }, 2000)
  } catch (error: any) {
    notify('error', 'Failed to complete sale', error.message)
  }
}

// Lifecycle
onMounted(() => {
  startTimer()
})

onUnmounted(() => {
  stopTimer()
})
</script>

<style scoped>
.cashier-checkout {
  /* Optimize for 7" tablets */
  max-width: 768px;
  margin: 0 auto;
}

/* Large touch targets */
button {
  min-height: 48px;
}

/* Smooth transitions */
* {
  transition-property: background-color, border-color, color;
  transition-duration: 150ms;
  transition-timing-function: ease-in-out;
}

/* Line clamp for product names */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
