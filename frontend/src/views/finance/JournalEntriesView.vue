<template>
  <div>
    <PageHeader title="Journal Entries" description="View and manage accounting journal entries">
      <template #actions>
        <button v-if="authStore.isManager" @click="createEntry" class="btn-primary">
          <PlusIcon class="h-5 w-5 mr-2" />
          New Entry
        </button>
      </template>
    </PageHeader>

    <!-- Status/Type Filters -->
    <div class="card mb-6">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
          <div class="flex gap-2">
            <button
              v-for="status in statuses"
              :key="status.value"
              @click="selectedStatus = status.value"
              :class="[
                'px-3 py-2 text-sm font-medium rounded-md',
                selectedStatus === status.value
                  ? 'bg-primary-600 text-white'
                  : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
              ]"
            >
              {{ status.label }}
            </button>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
          <div class="flex gap-2 flex-wrap">
            <button
              v-for="type in entryTypes"
              :key="type.value"
              @click="selectedType = type.value"
              :class="[
                'px-3 py-2 text-sm font-medium rounded-md',
                selectedType === type.value
                  ? 'bg-primary-600 text-white'
                  : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
              ]"
            >
              {{ type.label }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Journal Entries Table -->
    <div class="card overflow-hidden">
      <SimpleTable
        :columns="columns"
        :data="entries"
        :loading="loading"
        loading-text="Loading journal entries..."
        empty-text="No journal entries found."
      >
        <template #cell-entry_type="{ value }">
          <span :class="getTypeBadgeClass(value)">
            {{ value }}
          </span>
        </template>

        <template #cell-status="{ value }">
          <span :class="getStatusBadgeClass(value)">
            {{ value }}
          </span>
        </template>

        <template #actions="{ row }">
          <button @click="viewEntry(row)" class="text-primary-600 hover:text-primary-900 mr-4">View</button>
          <button v-if="row.status === 'draft' && authStore.isManager" @click="postEntry(row)" class="text-green-600 hover:text-green-900 mr-4">Post</button>
          <button v-if="row.status === 'posted' && authStore.isOwner" @click="reverseEntry(row)" class="text-yellow-600 hover:text-yellow-900">Reverse</button>
        </template>
      </SimpleTable>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { financeService, type JournalEntry } from '@/services/finance.service'
import PageHeader from '@/components/common/PageHeader.vue'
import SimpleTable from '@/components/common/SimpleTable.vue'
import { PlusIcon } from '@heroicons/vue/24/outline'

const authStore = useAuthStore()
const loading = ref(true)
const entries = ref<JournalEntry[]>([])
const selectedStatus = ref<string | undefined>(undefined)
const selectedType = ref<string | undefined>(undefined)

const statuses = [
  { value: undefined, label: 'All' },
  { value: 'draft', label: 'Draft' },
  { value: 'posted', label: 'Posted' },
  { value: 'reversed', label: 'Reversed' }
]

const entryTypes = [
  { value: undefined, label: 'All' },
  { value: 'general', label: 'General' },
  { value: 'purchase', label: 'Purchase' },
  { value: 'sales', label: 'Sales' },
  { value: 'payment', label: 'Payment' },
  { value: 'adjustment', label: 'Adjustment' }
]

const columns = [
  { key: 'entry_number', label: 'Entry #', cellClass: 'whitespace-nowrap text-sm font-medium text-gray-900' },
  { key: 'entry_date', label: 'Date', formatter: (v: string) => new Date(v).toLocaleDateString(), cellClass: 'whitespace-nowrap text-sm text-gray-500' },
  { key: 'entry_type', label: 'Type', slot: true, cellClass: 'whitespace-nowrap text-sm' },
  { key: 'description', label: 'Description', cellClass: 'text-sm text-gray-900' },
  { key: 'status', label: 'Status', slot: true, cellClass: 'whitespace-nowrap' }
]

onMounted(() => loadEntries())

async function loadEntries() {
  try {
    loading.value = true
    entries.value = await financeService.getJournalEntries({ status: selectedStatus.value, type: selectedType.value })
  } catch (err) {
    console.error('Failed to load journal entries:', err)
  } finally {
    loading.value = false
  }
}

function getTypeBadgeClass(type: string): string {
  const classes: Record<string, string> = {
    general: 'badge-gray',
    purchase: 'badge-blue',
    sales: 'badge-green',
    payment: 'badge-purple',
    adjustment: 'badge-yellow'
  }
  return classes[type] || 'badge-gray'
}

function getStatusBadgeClass(status: string): string {
  const classes: Record<string, string> = {
    draft: 'badge-gray',
    posted: 'badge-success',
    reversed: 'badge-red'
  }
  return classes[status] || 'badge-gray'
}

function createEntry() {
  console.log('Create new journal entry')
}

function viewEntry(entry: JournalEntry) {
  console.log('View entry:', entry)
}

async function postEntry(entry: JournalEntry) {
  if (confirm(`Post journal entry ${entry.entry_number}? This will lock the entry.`)) {
    try {
      await financeService.postJournalEntry(entry.id)
      await loadEntries()
    } catch (err: any) {
      alert(err.response?.data?.message || 'Failed to post entry')
    }
  }
}

function reverseEntry(entry: JournalEntry) {
  console.log('Reverse entry:', entry)
}
</script>
