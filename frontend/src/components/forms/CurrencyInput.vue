<template>
  <div class="currency-input-wrapper">
    <label v-if="label" :for="inputId" class="block text-sm font-medium text-gray-700 mb-1">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>

    <div class="relative">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <span class="text-gray-500 sm:text-sm">ETB</span>
      </div>

      <input
        :id="inputId"
        ref="inputRef"
        type="text"
        inputmode="decimal"
        :value="displayValue"
        @input="handleInput"
        @blur="handleBlur"
        @focus="handleFocus"
        :placeholder="placeholder"
        :disabled="disabled"
        :readonly="readonly"
        :required="required"
        :class="[
          'block w-full pl-12 pr-12 py-3 border rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500',
          disabled ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : 'bg-white',
          error ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300',
          large ? 'text-2xl font-bold' : 'sm:text-sm',
          inputClass
        ]"
        :aria-invalid="!!error"
        :aria-describedby="error ? `${inputId}-error` : undefined"
      />

      <!-- Clear button -->
      <div v-if="clearable && displayValue && !disabled && !readonly" class="absolute inset-y-0 right-0 pr-3 flex items-center">
        <button
          type="button"
          @click="handleClear"
          class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded"
        >
          <XMarkIcon class="h-5 w-5" />
        </button>
      </div>
    </div>

    <!-- Helper text or error -->
    <p v-if="error" :id="`${inputId}-error`" class="mt-1 text-sm text-red-600">
      {{ error }}
    </p>
    <p v-else-if="hint" class="mt-1 text-sm text-gray-500">
      {{ hint }}
    </p>

    <!-- Formatted value display (for large amounts) -->
    <p v-if="showFormattedValue && numericValue > 0" class="mt-1 text-xs text-gray-500 font-medium">
      {{ formatCurrency(numericValue) }}
    </p>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { useCurrency } from '@/composables/useCurrency'

interface Props {
  modelValue: number | string | null
  label?: string
  placeholder?: string
  hint?: string
  error?: string
  disabled?: boolean
  readonly?: boolean
  required?: boolean
  clearable?: boolean
  large?: boolean
  showFormattedValue?: boolean
  min?: number
  max?: number
  allowNegative?: boolean
  inputClass?: string
}

interface Emits {
  (e: 'update:modelValue', value: number | null): void
  (e: 'blur'): void
  (e: 'focus'): void
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: '0.00',
  clearable: true,
  large: false,
  showFormattedValue: false,
  allowNegative: false
})

const emit = defineEmits<Emits>()

const { format: formatCurrency, parse: parseCurrency } = useCurrency()

// Refs
const inputRef = ref<HTMLInputElement>()
const inputId = ref(`currency-input-${Math.random().toString(36).substr(2, 9)}`)
const isFocused = ref(false)
const rawInput = ref('')

// Computed
const numericValue = computed(() => {
  if (props.modelValue === null || props.modelValue === undefined || props.modelValue === '') {
    return 0
  }
  const value = typeof props.modelValue === 'string' ? parseFloat(props.modelValue) : props.modelValue
  return isNaN(value) ? 0 : value
})

const displayValue = computed(() => {
  if (isFocused.value) {
    // Show raw input while focused (easier to edit)
    return rawInput.value
  }

  // Show formatted value when not focused
  if (numericValue.value === 0) {
    return ''
  }

  return formatInputValue(numericValue.value)
})

// Methods
function formatInputValue(value: number): string {
  // Format with thousands separator but no currency symbol (for input)
  return new Intl.NumberFormat('en-ET', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(value)
}

function handleInput(event: Event) {
  const target = event.target as HTMLInputElement
  let value = target.value

  // Remove non-numeric characters except decimal point and minus
  value = value.replace(/[^\d.-]/g, '')

  // Handle negative values
  if (!props.allowNegative) {
    value = value.replace(/-/g, '')
  } else {
    // Allow only one minus sign at the start
    const minusCount = (value.match(/-/g) || []).length
    if (minusCount > 1) {
      value = value.replace(/-/g, '')
      value = '-' + value
    }
  }

  // Allow only one decimal point
  const parts = value.split('.')
  if (parts.length > 2) {
    value = parts[0] + '.' + parts.slice(1).join('')
  }

  // Limit decimal places to 2
  if (parts.length === 2 && parts[1].length > 2) {
    value = parts[0] + '.' + parts[1].substring(0, 2)
  }

  rawInput.value = value

  // Convert to number and emit
  const numValue = value === '' || value === '-' ? null : parseFloat(value)

  // Validate min/max
  if (numValue !== null) {
    if (props.min !== undefined && numValue < props.min) {
      return // Don't update if below min
    }
    if (props.max !== undefined && numValue > props.max) {
      return // Don't update if above max
    }
  }

  emit('update:modelValue', numValue)
}

function handleFocus() {
  isFocused.value = true
  rawInput.value = numericValue.value === 0 ? '' : numericValue.value.toString()
  emit('focus')
}

function handleBlur() {
  isFocused.value = false

  // Ensure proper formatting on blur
  if (rawInput.value !== '') {
    const value = parseFloat(rawInput.value)
    if (!isNaN(value)) {
      emit('update:modelValue', value)
    }
  }

  emit('blur')
}

function handleClear() {
  rawInput.value = ''
  emit('update:modelValue', null)
  inputRef.value?.focus()
}

// Focus method (exposed)
function focus() {
  inputRef.value?.focus()
}

// Watch for external value changes
watch(() => props.modelValue, (newValue) => {
  if (!isFocused.value && newValue !== null && newValue !== undefined) {
    rawInput.value = newValue.toString()
  }
})

// Initialize rawInput
onMounted(() => {
  if (numericValue.value !== 0) {
    rawInput.value = numericValue.value.toString()
  }
})

defineExpose({ focus })
</script>

<style scoped>
/* Prevent spinner on number input */
input[type="text"]::-webkit-outer-spin-button,
input[type="text"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Large input variant for cashier interface */
.currency-input-wrapper input.text-2xl {
  letter-spacing: 0.02em;
}

/* Ensure touch-friendly on mobile */
input {
  -webkit-tap-highlight-color: transparent;
}

/* Focus state enhancement for accessibility */
input:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Error state */
input[aria-invalid="true"] {
  animation: shake 0.3s ease-in-out;
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-4px); }
  75% { transform: translateX(4px); }
}
</style>
