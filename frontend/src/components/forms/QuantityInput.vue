<template>
  <div class="quantity-input-wrapper">
    <label v-if="label" :for="inputId" class="block text-sm font-medium text-gray-700 mb-1">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>

    <div class="flex gap-2">
      <!-- Quantity Input -->
      <div class="flex-1 relative">
        <input
          :id="inputId"
          ref="inputRef"
          type="number"
          inputmode="decimal"
          step="0.01"
          :value="modelValue"
          @input="handleInput"
          @blur="emit('blur')"
          @focus="emit('focus')"
          :placeholder="placeholder"
          :disabled="disabled"
          :readonly="readonly"
          :required="required"
          :min="min"
          :max="max"
          :class="[
            'block w-full px-3 py-3 border rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500',
            disabled ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : 'bg-white',
            error ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500' : 'border-gray-300',
            large ? 'text-xl font-semibold' : 'sm:text-sm'
          ]"
          :aria-invalid="!!error"
          :aria-describedby="error ? `${inputId}-error` : undefined"
        />

        <!-- Quick increment/decrement buttons -->
        <div v-if="showSteppers" class="absolute inset-y-0 right-0 flex flex-col border-l">
          <button
            type="button"
            @click="increment"
            :disabled="disabled || (max !== undefined && numericValue >= max)"
            class="flex-1 px-2 hover:bg-gray-100 focus:bg-gray-100 border-b disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <ChevronUpIcon class="h-4 w-4 text-gray-600" />
          </button>
          <button
            type="button"
            @click="decrement"
            :disabled="disabled || (min !== undefined && numericValue <= min)"
            class="flex-1 px-2 hover:bg-gray-100 focus:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <ChevronDownIcon class="h-4 w-4 text-gray-600" />
          </button>
        </div>
      </div>

      <!-- Unit Selector -->
      <div v-if="showUnitSelector" class="w-32">
        <select
          v-model="selectedUnitId"
          @change="handleUnitChange"
          :disabled="disabled || unitOptions.length === 0"
          class="block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
        >
          <option v-if="!selectedUnitId" value="">Unit</option>
          <option v-for="unit in unitOptions" :key="unit.id" :value="unit.id">
            {{ unit.abbreviation || unit.unit_name }}
          </option>
        </select>
      </div>
    </div>

    <!-- Stock/Availability Info -->
    <div v-if="showAvailability && availableQuantity !== undefined" class="mt-2 flex items-center justify-between text-xs">
      <span class="text-gray-600">
        Available: <span class="font-semibold">{{ formatNumber(availableQuantity) }}</span>
        {{ selectedUnit?.abbreviation }}
      </span>
      <span v-if="numericValue > availableQuantity" class="text-red-600 font-medium">
        Exceeds stock!
      </span>
      <span v-else-if="numericValue > 0" class="text-green-600">
        {{ formatNumber(availableQuantity - numericValue) }} remaining
      </span>
    </div>

    <!-- Conversion Info -->
    <div v-if="showConversion && conversionRate" class="mt-2 text-xs text-gray-600">
      = {{ formatNumber(numericValue * conversionRate) }} {{ conversionTargetUnit }}
    </div>

    <!-- Error message -->
    <p v-if="error" :id="`${inputId}-error`" class="mt-1 text-sm text-red-600">
      {{ error }}
    </p>
    <p v-else-if="hint" class="mt-1 text-sm text-gray-500">
      {{ hint }}
    </p>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { ChevronUpIcon, ChevronDownIcon } from '@heroicons/vue/24/outline'
import { formatNumber } from '@/utils/formatters'

interface Unit {
  id: number
  unit_name: string
  abbreviation: string
}

interface Props {
  modelValue: number | string | null
  unitId?: number | null
  unitOptions?: Unit[]
  label?: string
  placeholder?: string
  hint?: string
  error?: string
  disabled?: boolean
  readonly?: boolean
  required?: boolean
  large?: boolean
  min?: number
  max?: number
  step?: number
  showSteppers?: boolean
  showUnitSelector?: boolean
  showAvailability?: boolean
  showConversion?: boolean
  availableQuantity?: number
  conversionRate?: number
  conversionTargetUnit?: string
}

interface Emits {
  (e: 'update:modelValue', value: number | null): void
  (e: 'update:unitId', value: number | null): void
  (e: 'blur'): void
  (e: 'focus'): void
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: '0',
  step: 1,
  showSteppers: false,
  showUnitSelector: false,
  showAvailability: false,
  showConversion: false,
  unitOptions: () => []
})

const emit = defineEmits<Emits>()

// Refs
const inputRef = ref<HTMLInputElement>()
const inputId = ref(`quantity-input-${Math.random().toString(36).substr(2, 9)}`)
const selectedUnitId = ref<number | null>(props.unitId || null)

// Computed
const numericValue = computed(() => {
  if (props.modelValue === null || props.modelValue === undefined || props.modelValue === '') {
    return 0
  }
  const value = typeof props.modelValue === 'string' ? parseFloat(props.modelValue) : props.modelValue
  return isNaN(value) ? 0 : value
})

const selectedUnit = computed(() => {
  return props.unitOptions.find(u => u.id === selectedUnitId.value)
})

// Methods
function handleInput(event: Event) {
  const target = event.target as HTMLInputElement
  const value = target.value === '' ? null : parseFloat(target.value)

  // Validate min/max
  if (value !== null) {
    if (props.min !== undefined && value < props.min) {
      return // Don't update if below min
    }
    if (props.max !== undefined && value > props.max) {
      return // Don't update if above max
    }
  }

  emit('update:modelValue', value)
}

function increment() {
  const step = props.step || 1
  const newValue = numericValue.value + step

  if (props.max === undefined || newValue <= props.max) {
    emit('update:modelValue', newValue)
  }
}

function decrement() {
  const step = props.step || 1
  const newValue = numericValue.value - step

  if (props.min === undefined || newValue >= props.min) {
    emit('update:modelValue', newValue)
  }
}

function handleUnitChange() {
  emit('update:unitId', selectedUnitId.value)
}

// Focus method (exposed)
function focus() {
  inputRef.value?.focus()
}

defineExpose({ focus })
</script>

<style scoped>
/* Hide default number input spinners (we have custom ones) */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield;
}

/* Ensure touch-friendly buttons */
button {
  min-height: 24px;
  min-width: 32px;
}

/* Focus state */
input:focus,
select:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
