<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Daily Sales Entry</h1>
          <p class="text-lg text-gray-600 mt-1">{{ branchName }} - {{ selectedDate }}</p>
        </div>
        <div class="flex items-center gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
            <input
              v-model="selectedDate"
              type="date"
              class="px-4 py-2 border-2 rounded-lg text-lg font-semibold"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
            <select v-model="selectedBranch" class="px-4 py-2 border-2 rounded-lg text-lg font-semibold">
              <option value="main">Main Branch</option>
              <option value="piassa">Piassa Branch</option>
              <option value="bole">Bole Branch</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Entry Grid -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Sales Entry (Like Excel)</h2>

      <!-- Excel-like Table -->
      <div class="overflow-x-auto">
        <table class="w-full border-collapse border-2 border-gray-300">
          <thead>
            <tr class="bg-blue-600 text-white">
              <th class="border-2 border-gray-300 px-4 py-3 text-left text-lg font-bold">Product</th>
              <th class="border-2 border-gray-300 px-4 py-3 text-center text-lg font-bold">Yesterday Leftover</th>
              <th class="border-2 border-gray-300 px-4 py-3 text-center text-lg font-bold">Today Produced</th>
              <th class="border-2 border-gray-300 px-4 py-3 text-center text-lg font-bold">Received (Dispatch)</th>
              <th class="border-2 border-gray-300 px-4 py-3 text-center text-lg font-bold">Total Available</th>
              <th class="border-2 border-gray-300 px-4 py-3 text-center text-lg font-bold">Sold Today</th>
              <th class="border-2 border-gray-300 px-4 py-3 text-center text-lg font-bold">Today Leftover</th>
              <th class="border-2 border-gray-300 px-4 py-3 text-center text-lg font-bold">Variance</th>
              <th class="border-2 border-gray-300 px-4 py-3 text-right text-lg font-bold">Revenue (ETB)</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
              <td class="border-2 border-gray-300 px-4 py-2">
                <span class="text-xl font-bold text-gray-900">{{ product.name }}</span>
                <span class="text-sm text-gray-500 block">@ {{ formatCurrency(product.price) }}</span>
              </td>

              <!-- Yesterday Leftover (read-only, from yesterday's entry) -->
              <td class="border-2 border-gray-300 px-4 py-2 bg-gray-100 text-center">
                <input
                  v-model.number="product.yesterdayLeftover"
                  type="number"
                  readonly
                  class="w-24 px-3 py-2 text-center text-xl font-bold bg-gray-100 rounded"
                />
              </td>

              <!-- Today Produced (for main branch) or empty for sub-branches -->
              <td class="border-2 border-gray-300 px-4 py-2 text-center" :class="selectedBranch !== 'main' ? 'bg-gray-100' : ''">
                <input
                  v-model.number="product.produced"
                  type="number"
                  :readonly="selectedBranch !== 'main'"
                  class="w-24 px-3 py-3 text-center text-2xl font-bold border-2 rounded focus:ring-2 focus:ring-blue-500"
                  :class="selectedBranch !== 'main' ? 'bg-gray-100' : 'bg-yellow-50'"
                  @input="calculateRow(product)"
                />
              </td>

              <!-- Received from Dispatch (for sub-branches) -->
              <td class="border-2 border-gray-300 px-4 py-2 text-center" :class="selectedBranch === 'main' ? 'bg-gray-100' : ''">
                <input
                  v-model.number="product.received"
                  type="number"
                  :readonly="selectedBranch === 'main'"
                  class="w-24 px-3 py-3 text-center text-2xl font-bold border-2 rounded focus:ring-2 focus:ring-blue-500"
                  :class="selectedBranch === 'main' ? 'bg-gray-100' : 'bg-green-50'"
                  @input="calculateRow(product)"
                />
              </td>

              <!-- Total Available (auto-calculated) -->
              <td class="border-2 border-gray-300 px-4 py-2 bg-blue-50 text-center">
                <span class="text-2xl font-bold text-blue-600">{{ product.totalAvailable }}</span>
              </td>

              <!-- Sold Today (manual entry) -->
              <td class="border-2 border-gray-300 px-4 py-2 text-center">
                <input
                  v-model.number="product.soldToday"
                  type="number"
                  class="w-24 px-3 py-3 text-center text-2xl font-bold border-2 border-green-500 rounded focus:ring-2 focus:ring-green-500 bg-white"
                  @input="calculateRow(product)"
                />
              </td>

              <!-- Today Leftover (manual entry) -->
              <td class="border-2 border-gray-300 px-4 py-2 text-center">
                <input
                  v-model.number="product.todayLeftover"
                  type="number"
                  class="w-24 px-3 py-3 text-center text-2xl font-bold border-2 rounded focus:ring-2 focus:ring-orange-500 bg-orange-50"
                  @input="calculateRow(product)"
                />
              </td>

              <!-- Variance (auto-calculated, shows theft/waste) -->
              <td class="border-2 border-gray-300 px-4 py-2 text-center" :class="product.variance !== 0 ? 'bg-red-100' : 'bg-green-50'">
                <span class="text-2xl font-bold" :class="product.variance !== 0 ? 'text-red-600' : 'text-green-600'">
                  {{ product.variance > 0 ? '+' : '' }}{{ product.variance }}
                </span>
              </td>

              <!-- Revenue (auto-calculated) -->
              <td class="border-2 border-gray-300 px-4 py-2 bg-green-50 text-right">
                <span class="text-xl font-bold text-green-600">{{ formatCurrency(product.revenue) }}</span>
              </td>
            </tr>

            <!-- Totals Row -->
            <tr class="bg-gray-800 text-white font-bold">
              <td class="border-2 border-gray-300 px-4 py-3 text-xl">TOTAL</td>
              <td class="border-2 border-gray-300 px-4 py-3 text-center text-xl">{{ totalYesterdayLeftover }}</td>
              <td class="border-2 border-gray-300 px-4 py-3 text-center text-xl">{{ totalProduced }}</td>
              <td class="border-2 border-gray-300 px-4 py-3 text-center text-xl">{{ totalReceived }}</td>
              <td class="border-2 border-gray-300 px-4 py-3 text-center text-xl bg-blue-600">{{ totalAvailable }}</td>
              <td class="border-2 border-gray-300 px-4 py-3 text-center text-xl bg-green-600">{{ totalSold }}</td>
              <td class="border-2 border-gray-300 px-4 py-3 text-center text-xl">{{ totalTodayLeftover }}</td>
              <td class="border-2 border-gray-300 px-4 py-3 text-center text-xl" :class="totalVariance !== 0 ? 'bg-red-600' : 'bg-green-600'">
                {{ totalVariance }}
              </td>
              <td class="border-2 border-gray-300 px-4 py-3 text-right text-2xl bg-green-700">{{ formatCurrency(totalRevenue) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Formula Explanation -->
      <div class="mt-4 p-4 bg-blue-50 rounded-lg border-2 border-blue-200">
        <p class="text-sm font-mono text-gray-700">
          <strong>Formula:</strong> Yesterday Leftover + Today Produced + Received = Total Available
        </p>
        <p class="text-sm font-mono text-gray-700 mt-1">
          <strong>Check:</strong> Sold Today + Today Leftover should = Total Available
        </p>
        <p class="text-sm font-mono text-red-600 font-bold mt-1">
          <strong>Variance:</strong> Total Available - (Sold + Leftover) = Theft/Waste if not zero
        </p>
      </div>
    </div>

    <!-- Expenses Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-4">Today's Expenses</h2>

      <div class="overflow-x-auto">
        <table class="w-full border-collapse border-2 border-gray-300">
          <thead>
            <tr class="bg-red-600 text-white">
              <th class="border-2 border-gray-300 px-4 py-3 text-left text-lg font-bold">Expense Type</th>
              <th class="border-2 border-gray-300 px-4 py-3 text-center text-lg font-bold">Amount (ETB)</th>
              <th class="border-2 border-gray-300 px-4 py-3 text-left text-lg font-bold">Notes</th>
              <th class="border-2 border-gray-300 px-4 py-3 text-center text-lg font-bold">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(expense, index) in expenses" :key="index" class="hover:bg-gray-50">
              <td class="border-2 border-gray-300 px-4 py-2">
                <select v-model="expense.type" class="w-full px-3 py-2 border-2 rounded text-lg">
                  <option value="">Select type...</option>
                  <option value="rent">Rent</option>
                  <option value="electricity">Electricity</option>
                  <option value="water">Water</option>
                  <option value="salary">Salary</option>
                  <option value="transport">Transport</option>
                  <option value="maintenance">Maintenance</option>
                  <option value="supplies">Supplies</option>
                  <option value="other">Other</option>
                </select>
              </td>
              <td class="border-2 border-gray-300 px-4 py-2 text-center">
                <input
                  v-model.number="expense.amount"
                  type="number"
                  class="w-32 px-3 py-2 text-center text-xl font-bold border-2 rounded"
                />
              </td>
              <td class="border-2 border-gray-300 px-4 py-2">
                <input
                  v-model="expense.notes"
                  type="text"
                  class="w-full px-3 py-2 border-2 rounded"
                  placeholder="Details..."
                />
              </td>
              <td class="border-2 border-gray-300 px-4 py-2 text-center">
                <button @click="removeExpense(index)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-bold">
                  Delete
                </button>
              </td>
            </tr>
            <tr class="bg-gray-800 text-white font-bold">
              <td class="border-2 border-gray-300 px-4 py-3 text-xl">TOTAL EXPENSES</td>
              <td class="border-2 border-gray-300 px-4 py-3 text-center text-2xl">{{ formatCurrency(totalExpenses) }}</td>
              <td colspan="2" class="border-2 border-gray-300 px-4 py-3"></td>
            </tr>
          </tbody>
        </table>
      </div>

      <button @click="addExpense" class="mt-4 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold text-lg">
        + Add Expense
      </button>
    </div>

    <!-- Summary -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-4">Daily Summary</h2>

      <div class="grid grid-cols-3 gap-6">
        <div class="text-center p-6 bg-green-100 rounded-lg border-2 border-green-300">
          <p class="text-sm font-medium text-gray-600">Total Revenue</p>
          <p class="text-4xl font-bold text-green-600 mt-2">{{ formatCurrency(totalRevenue) }}</p>
        </div>

        <div class="text-center p-6 bg-red-100 rounded-lg border-2 border-red-300">
          <p class="text-sm font-medium text-gray-600">Total Expenses</p>
          <p class="text-4xl font-bold text-red-600 mt-2">{{ formatCurrency(totalExpenses) }}</p>
        </div>

        <div class="text-center p-6 rounded-lg border-4" :class="dailyProfit >= 0 ? 'bg-blue-100 border-blue-500' : 'bg-red-100 border-red-500'">
          <p class="text-sm font-medium text-gray-600">Daily Profit/Loss</p>
          <p class="text-4xl font-bold mt-2" :class="dailyProfit >= 0 ? 'text-blue-600' : 'text-red-600'">
            {{ formatCurrency(dailyProfit) }}
          </p>
        </div>
      </div>

      <!-- Variance Alert -->
      <div v-if="totalVariance !== 0" class="mt-6 p-4 bg-red-50 border-2 border-red-300 rounded-lg">
        <p class="text-lg font-bold text-red-800">⚠️ VARIANCE DETECTED: {{ Math.abs(totalVariance) }} units unaccounted for</p>
        <p class="text-sm text-red-700 mt-1">This could indicate theft, waste, or data entry errors. Please verify counts.</p>
      </div>
    </div>

    <!-- Save Button -->
    <div class="flex gap-4">
      <button
        @click="saveDailySales"
        class="flex-1 px-8 py-6 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold text-2xl shadow-lg"
      >
        💾 SAVE DAILY SALES
      </button>
      <button
        @click="clearForm"
        class="px-8 py-6 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-bold text-xl"
      >
        Clear
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const selectedDate = ref(new Date().toISOString().split('T')[0])
const selectedBranch = ref('main')
const branchName = computed(() => {
  const names = { main: 'Main Branch', piassa: 'Piassa Branch', bole: 'Bole Branch' }
  return names[selectedBranch.value as keyof typeof names]
})

const products = ref([
  { id: 1, name: 'White Bread', price: 25, yesterdayLeftover: 15, produced: 0, received: 0, soldToday: 0, todayLeftover: 0, totalAvailable: 0, variance: 0, revenue: 0 },
  { id: 2, name: 'Brown Bread', price: 30, yesterdayLeftover: 10, produced: 0, received: 0, soldToday: 0, todayLeftover: 0, totalAvailable: 0, variance: 0, revenue: 0 },
  { id: 3, name: 'Cake (Whole)', price: 200, yesterdayLeftover: 3, produced: 0, received: 0, soldToday: 0, todayLeftover: 0, totalAvailable: 0, variance: 0, revenue: 0 },
  { id: 4, name: 'Donut', price: 15, yesterdayLeftover: 20, produced: 0, received: 0, soldToday: 0, todayLeftover: 0, totalAvailable: 0, variance: 0, revenue: 0 }
])

const expenses = ref([
  { type: '', amount: 0, notes: '' }
])

function calculateRow(product: any) {
  // Total Available = Yesterday + Produced + Received
  product.totalAvailable = (product.yesterdayLeftover || 0) + (product.produced || 0) + (product.received || 0)

  // Variance = Available - (Sold + Leftover)
  product.variance = product.totalAvailable - ((product.soldToday || 0) + (product.todayLeftover || 0))

  // Revenue = Sold * Price
  product.revenue = (product.soldToday || 0) * product.price
}

// Initialize calculations
products.value.forEach(p => calculateRow(p))

const totalYesterdayLeftover = computed(() => products.value.reduce((sum, p) => sum + (p.yesterdayLeftover || 0), 0))
const totalProduced = computed(() => products.value.reduce((sum, p) => sum + (p.produced || 0), 0))
const totalReceived = computed(() => products.value.reduce((sum, p) => sum + (p.received || 0), 0))
const totalAvailable = computed(() => products.value.reduce((sum, p) => sum + p.totalAvailable, 0))
const totalSold = computed(() => products.value.reduce((sum, p) => sum + (p.soldToday || 0), 0))
const totalTodayLeftover = computed(() => products.value.reduce((sum, p) => sum + (p.todayLeftover || 0), 0))
const totalVariance = computed(() => products.value.reduce((sum, p) => sum + p.variance, 0))
const totalRevenue = computed(() => products.value.reduce((sum, p) => sum + p.revenue, 0))

const totalExpenses = computed(() => expenses.value.reduce((sum, e) => sum + (e.amount || 0), 0))
const dailyProfit = computed(() => totalRevenue.value - totalExpenses.value)

function addExpense() {
  expenses.value.push({ type: '', amount: 0, notes: '' })
}

function removeExpense(index: number) {
  expenses.value.splice(index, 1)
}

function saveDailySales() {
  if (totalVariance.value !== 0) {
    if (!confirm(`There is a variance of ${Math.abs(totalVariance.value)} units. Do you want to save anyway?`)) {
      return
    }
  }

  // TODO: Send to API
  alert(`Daily sales saved!\nRevenue: ${formatCurrency(totalRevenue.value)}\nExpenses: ${formatCurrency(totalExpenses.value)}\nProfit: ${formatCurrency(dailyProfit.value)}`)
}

function clearForm() {
  if (!confirm('Are you sure you want to clear all data?')) return

  products.value.forEach(p => {
    p.produced = 0
    p.received = 0
    p.soldToday = 0
    p.todayLeftover = 0
    calculateRow(p)
  })

  expenses.value = [{ type: '', amount: 0, notes: '' }]
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
