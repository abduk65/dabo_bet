<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <Sidebar :open="sidebarOpen" @close="sidebarOpen = false" />

    <!-- Main content -->
    <div class="lg:pl-64 flex flex-col flex-1">
      <!-- Top navbar -->
      <Navbar @toggle-sidebar="sidebarOpen = !sidebarOpen" />

      <!-- Page content -->
      <main class="flex-1">
        <div class="py-6">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <RouterView />
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { RouterView } from 'vue-router'
import Sidebar from '@/components/layout/Sidebar.vue'
import Navbar from '@/components/layout/Navbar.vue'

const sidebarOpen = ref(false)
</script>
