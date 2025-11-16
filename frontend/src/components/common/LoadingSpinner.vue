<template>
  <div class="flex items-center justify-center" :class="containerClass">
    <div
      class="inline-block animate-spin rounded-full border-b-2"
      :class="[sizeClass, colorClass]"
    ></div>
    <p v-if="text" class="ml-3 text-sm" :class="textColorClass">{{ text }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  size?: 'sm' | 'md' | 'lg' | 'xl'
  color?: 'primary' | 'white' | 'gray'
  text?: string
  containerClass?: string
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
  color: 'primary'
})

const sizeClass = computed(() => {
  const sizes = {
    sm: 'h-4 w-4',
    md: 'h-8 w-8',
    lg: 'h-12 w-12',
    xl: 'h-16 w-16'
  }
  return sizes[props.size]
})

const colorClass = computed(() => {
  const colors = {
    primary: 'border-primary-600',
    white: 'border-white',
    gray: 'border-gray-600'
  }
  return colors[props.color]
})

const textColorClass = computed(() => {
  const colors = {
    primary: 'text-gray-700',
    white: 'text-white',
    gray: 'text-gray-600'
  }
  return colors[props.color]
})
</script>
