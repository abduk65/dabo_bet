<template>
  <div>
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Journal Entries</h1>
        <p class="mt-2 text-sm text-gray-700">View and create accounting journal entries</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <button
          v-if="authStore.isManager"
          class="btn-primary"
        >
          <PlusIcon class="h-5 w-5 mr-2" />
          New Entry
        </button>
      </div>
    </div>

    <div class="card">
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Journal Entries</h3>
        <p class="mt-1 text-sm text-gray-500">This feature is coming soon.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { PlusIcon } from '@heroicons/vue/24/outline'

const authStore = useAuthStore()
</script>
