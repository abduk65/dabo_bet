<template>
  <div class="text-center" :class="containerClass">
    <component v-if="icon" :is="icon" class="mx-auto text-gray-400" :class="iconSizeClass" />
    <h3 v-if="title" class="mt-2 text-sm font-medium text-gray-900">{{ title }}</h3>
    <p v-if="description" class="mt-1 text-sm text-gray-500">{{ description }}</p>
    <div v-if="$slots.action" class="mt-6">
      <slot name="action"></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  icon?: any
  title?: string
  description?: string
  iconSize?: 'sm' | 'md' | 'lg'
  containerClass?: string
}

const props = withDefaults(defineProps<Props>(), {
  iconSize: 'md',
  containerClass: 'py-12'
})

const iconSizeClass = computed(() => {
  const sizes = {
    sm: 'h-8 w-8',
    md: 'h-12 w-12',
    lg: 'h-16 w-16'
  }
  return sizes[props.iconSize]
})
</script>
