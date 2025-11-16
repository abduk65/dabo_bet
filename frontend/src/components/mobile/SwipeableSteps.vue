<template>
  <div class="swipeable-steps" ref="containerRef">
    <!-- Progress Bar -->
    <div class="progress-bar-container sticky top-0 bg-white z-10 px-4 py-3 border-b">
      <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-medium text-gray-700">
          Step {{ modelValue + 1 }} of {{ totalSteps }}
        </span>
        <span class="text-sm text-gray-500">{{ currentStepLabel }}</span>
      </div>
      <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
        <div
          class="h-full bg-primary-600 transition-all duration-300 ease-out"
          :style="{ width: `${progress}%` }"
        ></div>
      </div>
    </div>

    <!-- Step Content Container -->
    <div
      class="steps-container"
      @touchstart="handleTouchStart"
      @touchmove="handleTouchMove"
      @touchend="handleTouchEnd"
    >
      <div
        class="steps-wrapper"
        :style="{ transform: `translateX(-${modelValue * 100}%)`, transition: transitioning ? 'transform 0.3s ease-out' : 'none' }"
      >
        <div
          v-for="(step, index) in steps"
          :key="index"
          class="step-content"
        >
          <slot :name="`step-${index}`" :step="step" :index="index"></slot>
        </div>
      </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="navigation-buttons sticky bottom-0 bg-white border-t px-4 py-4 flex gap-3">
      <button
        v-if="modelValue > 0"
        @click="previousStep"
        type="button"
        class="btn flex-1"
        :disabled="disabled"
      >
        <ChevronLeftIcon class="h-5 w-5 mr-2" />
        Back
      </button>

      <button
        v-if="modelValue < totalSteps - 1"
        @click="nextStep"
        type="button"
        class="btn-primary flex-1"
        :disabled="disabled || !canProceed"
      >
        Next
        <ChevronRightIcon class="h-5 w-5 ml-2" />
      </button>

      <button
        v-else-if="showFinishButton"
        @click="handleFinish"
        type="button"
        class="btn-primary flex-1"
        :disabled="disabled || !canFinish"
      >
        {{ finishButtonText }}
        <CheckIcon class="h-5 w-5 ml-2" />
      </button>
    </div>

    <!-- Swipe Indicator (shows on touch) -->
    <Transition name="fade">
      <div
        v-if="showSwipeIndicator"
        class="swipe-indicator fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-black bg-opacity-75 text-white px-6 py-3 rounded-lg pointer-events-none"
      >
        <component :is="swipeDirection === 'left' ? ChevronRightIcon : ChevronLeftIcon" class="h-8 w-8" />
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { ChevronLeftIcon, ChevronRightIcon, CheckIcon } from '@heroicons/vue/24/outline'

export interface Step {
  label: string
  canProceed?: () => boolean
}

interface Props {
  modelValue: number
  steps: Step[]
  disabled?: boolean
  canFinish?: boolean
  showFinishButton?: boolean
  finishButtonText?: string
  swipeEnabled?: boolean
  swipeThreshold?: number
}

interface Emits {
  (e: 'update:modelValue', value: number): void
  (e: 'finish'): void
  (e: 'stepChange', current: number, previous: number): void
}

const props = withDefaults(defineProps<Props>(), {
  disabled: false,
  canFinish: true,
  showFinishButton: true,
  finishButtonText: 'Finish',
  swipeEnabled: true,
  swipeThreshold: 50 // pixels
})

const emit = defineEmits<Emits>()

// Refs
const containerRef = ref<HTMLElement>()
const touchStartX = ref(0)
const touchEndX = ref(0)
const showSwipeIndicator = ref(false)
const swipeDirection = ref<'left' | 'right'>('left')
const transitioning = ref(true)

// Computed
const totalSteps = computed(() => props.steps.length)

const progress = computed(() => {
  return ((props.modelValue + 1) / totalSteps.value) * 100
})

const currentStepLabel = computed(() => {
  return props.steps[props.modelValue]?.label || ''
})

const canProceed = computed(() => {
  const currentStep = props.steps[props.modelValue]
  if (currentStep?.canProceed) {
    return currentStep.canProceed()
  }
  return true
})

// Methods
function nextStep() {
  if (props.modelValue < totalSteps.value - 1 && canProceed.value) {
    const previous = props.modelValue
    emit('update:modelValue', props.modelValue + 1)
    emit('stepChange', props.modelValue + 1, previous)
  }
}

function previousStep() {
  if (props.modelValue > 0) {
    const previous = props.modelValue
    emit('update:modelValue', props.modelValue - 1)
    emit('stepChange', props.modelValue - 1, previous)
  }
}

function handleFinish() {
  if (props.canFinish) {
    emit('finish')
  }
}

// Touch Handlers for Swipe
function handleTouchStart(e: TouchEvent) {
  if (!props.swipeEnabled) return

  touchStartX.value = e.touches[0].clientX
  transitioning.value = false
}

function handleTouchMove(e: TouchEvent) {
  if (!props.swipeEnabled) return

  touchEndX.value = e.touches[0].clientX

  // Show indicator if swipe is significant
  const diff = touchEndX.value - touchStartX.value
  if (Math.abs(diff) > 20) {
    showSwipeIndicator.value = true
    swipeDirection.value = diff > 0 ? 'right' : 'left'
  }
}

function handleTouchEnd() {
  if (!props.swipeEnabled) return

  transitioning.value = true
  showSwipeIndicator.value = false

  const diff = touchStartX.value - touchEndX.value

  // Swipe left (next)
  if (diff > props.swipeThreshold) {
    nextStep()
  }
  // Swipe right (previous)
  else if (diff < -props.swipeThreshold) {
    previousStep()
  }

  // Reset
  touchStartX.value = 0
  touchEndX.value = 0
}

// Watch for external step changes
watch(() => props.modelValue, () => {
  transitioning.value = true
})
</script>

<style scoped>
.swipeable-steps {
  display: flex;
  flex-direction: column;
  height: 100%;
  min-height: 100vh;
  overflow: hidden;
}

.steps-container {
  flex: 1;
  overflow: hidden;
  position: relative;
  touch-action: pan-y; /* Allow vertical scroll, capture horizontal */
}

.steps-wrapper {
  display: flex;
  height: 100%;
  width: 100%;
}

.step-content {
  min-width: 100%;
  width: 100%;
  flex-shrink: 0;
  overflow-y: auto;
  padding: 1rem;
}

/* Smooth progress bar animation */
.progress-bar-container {
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Navigation buttons optimized for mobile */
.navigation-buttons {
  box-shadow: 0 -1px 3px rgba(0, 0, 0, 0.1);
}

.navigation-buttons button {
  min-height: 48px; /* Minimum touch target size */
  font-size: 1rem;
  font-weight: 500;
}

/* Swipe indicator animation */
.swipe-indicator {
  animation: pulse 0.3s ease-in-out;
}

@keyframes pulse {
  0%, 100% { transform: translate(-50%, -50%) scale(1); }
  50% { transform: translate(-50%, -50%) scale(1.1); }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Accessibility - focus indicators */
button:focus-visible {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}

/* Mobile optimizations */
@media (max-width: 640px) {
  .step-content {
    padding: 0.75rem;
  }

  .navigation-buttons {
    padding: 0.75rem;
  }
}

/* Prevent text selection during swipe */
.steps-container {
  -webkit-user-select: none;
  user-select: none;
}
</style>
