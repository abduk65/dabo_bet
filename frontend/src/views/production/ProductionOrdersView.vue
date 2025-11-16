<template>
  <div>
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Production Orders</h1>
        <p class="mt-2 text-sm text-gray-700">Manage production planning and execution</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <button
          v-if="authStore.isManager"
          class="btn-primary"
        >
          <PlusIcon class="h-5 w-5 mr-2" />
          New Production Order
        </button>
      </div>
    </div>

    <div class="card">
      <div class="text-center py-12">
        <CogIcon class="mx-auto h-12 w-12 text-gray-400" />
        <h3 class="mt-2 text-sm font-medium text-gray-900">Production Orders Management</h3>
        <p class="mt-1 text-sm text-gray-500">This feature is coming soon.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { PlusIcon, CogIcon } from '@heroicons/vue/24/outline'

const authStore = useAuthStore()
</script>
