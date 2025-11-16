<template>
  <div>
    <div class="mb-6">
      <RouterLink to="/inventory/purchase-orders" class="text-sm text-primary-600 hover:text-primary-900">
        &larr; Back to Purchase Orders
      </RouterLink>
      <h1 class="mt-2 text-2xl font-semibold text-gray-900">Purchase Order Detail</h1>
    </div>

    <div class="card">
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Purchase Order Details</h3>
        <p class="mt-1 text-sm text-gray-500">This feature is coming soon.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>
