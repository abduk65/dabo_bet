<template>
  <div class="editable-cell">
    <!-- View mode -->
    <div v-if="!isEditing" class="flex items-center justify-between group">
      <span :class="valueClass">{{ displayValue }}</span>

      <button
        v-if="editable && !disabled"
        @click="startEditing"
        type="button"
        class="ml-2 opacity-0 group-hover:opacity-100 transition-opacity p-1 rounded hover:bg-gray-100"
      >
        <PencilIcon class="h-4 w-4 text-gray-400" />
      </button>
    </div>

    <!-- Edit mode -->
    <div v-else class="flex items-center gap-2">
      <!-- Text input -->
      <input
        v-if="type === 'text'"
        ref="inputRef"
        v-model="editValue"
        type="text"
        @blur="handleBlur"
        @keydown.enter="save"
        @keydown.esc="cancel"
        class="block w-full px-2 py-1 border border-primary-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
      />

      <!-- Number input -->
      <input
        v-else-if="type === 'number'"
        ref="inputRef"
        v-model.number="editValue"
        type="number"
        :step="step"
        :min="min"
        :max="max"
        @blur="handleBlur"
        @keydown.enter="save"
        @keydown.esc="cancel"
        class="block w-full px-2 py-1 border border-primary-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
      />

      <!-- Currency input -->
      <input
        v-else-if="type === 'currency'"
        ref="inputRef"
        v-model.number="editValue"
        type="number"
        step="0.01"
        :min="min"
        :max="max"
        @blur="handleBlur"
        @keydown.enter="save"
        @keydown.esc="cancel"
        class="block w-full px-2 py-1 border border-primary-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
      />

      <!-- Select -->
      <select
        v-else-if="type === 'select'"
        ref="inputRef"
        v-model="editValue"
        @blur="handleBlur"
        @keydown.enter="save"
        @keydown.esc="cancel"
        class="block w-full px-2 py-1 border border-primary-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
      >
        <option v-for="option in options" :key="option.value" :value="option.value">
          {{ option.label }}
        </option>
      </select>

      <!-- Date input -->
      <input
        v-else-if="type === 'date'"
        ref="inputRef"
        v-model="editValue"
        type="date"
        @blur="handleBlur"
        @keydown.enter="save"
        @keydown.esc="cancel"
        class="block w-full px-2 py-1 border border-primary-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
      />

      <!-- Action buttons -->
      <div class="flex items-center gap-1">
        <button
          @click="save"
          type="button"
          class="p-1 rounded hover:bg-green-100 text-green-600"
        >
          <CheckIcon class="h-4 w-4" />
        </button>
        <button
          @click="cancel"
          type="button"
          class="p-1 rounded hover:bg-red-100 text-red-600"
        >
          <XMarkIcon class="h-4 w-4" />
        </button>
      </div>
    </div>

    <!-- Saving indicator -->
    <div v-if="saving" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
      <ArrowPathIcon class="h-4 w-4 text-primary-600 animate-spin" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, nextTick, watch } from 'vue'
import { PencilIcon, CheckIcon, XMarkIcon, ArrowPathIcon } from '@heroicons/vue/24/outline'
import { formatCurrency, formatDate } from '@/utils/formatters'

type InputType = 'text' | 'number' | 'currency' | 'select' | 'date'

interface SelectOption {
  value: string | number
  label: string
}

interface Props {
  modelValue: any
  type?: InputType
  editable?: boolean
  disabled?: boolean
  options?: SelectOption[]
  step?: number | string
  min?: number
  max?: number
  valueClass?: string
  validate?: (value: any) => boolean | string
  format?: (value: any) => string
}

interface Emits {
  (e: 'update:modelValue', value: any): void
  (e: 'save', value: any): Promise<void> | void
  (e: 'cancel'): void
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  editable: true,
  disabled: false,
  step: 1,
  options: () => []
})

const emit = defineEmits<Emits>()

// State
const isEditing = ref(false)
const editValue = ref<any>(props.modelValue)
const saving = ref(false)
const inputRef = ref<HTMLInputElement | HTMLSelectElement>()

// Computed
const displayValue = computed(() => {
  if (props.format) {
    return props.format(props.modelValue)
  }

  if (props.type === 'currency') {
    return formatCurrency(props.modelValue)
  }

  if (props.type === 'date' && props.modelValue) {
    return formatDate(new Date(props.modelValue))
  }

  if (props.type === 'select') {
    const option = props.options.find(opt => opt.value === props.modelValue)
    return option?.label || props.modelValue
  }

  return props.modelValue
})

// Watch for external value changes
watch(() => props.modelValue, (newValue) => {
  if (!isEditing.value) {
    editValue.value = newValue
  }
})

// Methods
async function startEditing() {
  if (props.disabled || !props.editable) return

  isEditing.value = true
  editValue.value = props.modelValue

  await nextTick()
  inputRef.value?.focus()
}

async function save() {
  // Validate
  if (props.validate) {
    const result = props.validate(editValue.value)
    if (result !== true) {
      alert(typeof result === 'string' ? result : 'Invalid value')
      return
    }
  }

  // Check if value changed
  if (editValue.value === props.modelValue) {
    cancel()
    return
  }

  saving.value = true

  try {
    await emit('save', editValue.value)
    emit('update:modelValue', editValue.value)
    isEditing.value = false
  } catch (error) {
    console.error('Failed to save:', error)
    // Revert value
    editValue.value = props.modelValue
  } finally {
    saving.value = false
  }
}

function cancel() {
  editValue.value = props.modelValue
  isEditing.value = false
  emit('cancel')
}

function handleBlur() {
  // Don't auto-save on blur to avoid accidental changes
  // User must explicitly click save button
  if (!saving.value) {
    cancel()
  }
}

defineExpose({
  startEditing,
  save,
  cancel
})
</script>

<style scoped>
.editable-cell {
  position: relative;
  min-height: 32px;
  display: flex;
  align-items: center;
}

/* Ensure inputs don't overflow */
.editable-cell input,
.editable-cell select {
  max-width: 200px;
}

/* Touch-friendly buttons */
.editable-cell button {
  min-width: 32px;
  min-height: 32px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

/* Smooth transitions */
.editable-cell > div {
  transition: opacity 150ms ease-in-out;
}
</style>
