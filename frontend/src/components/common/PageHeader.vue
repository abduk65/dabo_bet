<template>
  <div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
      <h1 class="text-2xl font-semibold text-gray-900">{{ title }}</h1>
      <p v-if="description" class="mt-2 text-sm text-gray-700">{{ description }}</p>
    </div>
    <div v-if="$slots.actions" class="mt-4 sm:mt-0">
      <slot name="actions"></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  title: string
  description?: string
}

defineProps<Props>()
</script>
