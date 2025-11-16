<template>
  <div>
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Customers</h1>
        <p class="mt-2 text-sm text-gray-700">Manage customer information and credit accounts</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <button class="btn-primary">
          <PlusIcon class="h-5 w-5 mr-2" />
          Add Customer
        </button>
      </div>
    </div>

    <div class="card">
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Customer Management</h3>
        <p class="mt-1 text-sm text-gray-500">This feature is coming soon.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { PlusIcon } from '@heroicons/vue/24/outline'
</script>
