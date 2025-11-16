<template>
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th
            v-for="column in columns"
            :key="column.key"
            scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            :class="column.headerClass"
          >
            {{ column.label }}
          </th>
          <th v-if="hasActions" scope="col" class="relative px-6 py-3">
            <span class="sr-only">Actions</span>
          </th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-if="loading">
          <td :colspan="columns.length + (hasActions ? 1 : 0)" class="px-6 py-12 text-center text-sm text-gray-500">
            <div class="flex justify-center">
              <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
            </div>
            <p class="mt-2">{{ loadingText }}</p>
          </td>
        </tr>
        <tr v-else-if="data.length === 0">
          <td :colspan="columns.length + (hasActions ? 1 : 0)" class="px-6 py-12 text-center text-sm text-gray-500">
            {{ emptyText }}
          </td>
        </tr>
        <tr v-for="(row, index) in data" :key="row[rowKey] || index" class="hover:bg-gray-50">
          <td
            v-for="column in columns"
            :key="column.key"
            class="px-6 py-4"
            :class="column.cellClass"
          >
            <slot
              v-if="column.slot"
              :name="`cell-${column.key}`"
              :row="row"
              :value="getNestedValue(row, column.key)"
            >
              {{ getNestedValue(row, column.key) }}
            </slot>
            <component
              v-else-if="column.component"
              :is="column.component"
              :value="getNestedValue(row, column.key)"
              :row="row"
            />
            <span v-else>
              {{ column.formatter ? column.formatter(getNestedValue(row, column.key), row) : getNestedValue(row, column.key) }}
            </span>
          </td>
          <td v-if="hasActions" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <slot name="actions" :row="row"></slot>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

export interface TableColumn {
  key: string
  label: string
  slot?: boolean
  component?: any
  formatter?: (value: any, row: any) => string | number
  headerClass?: string
  cellClass?: string
}

interface Props {
  columns: TableColumn[]
  data: any[]
  rowKey?: string
  loading?: boolean
  loadingText?: string
  emptyText?: string
}

const props = withDefaults(defineProps<Props>(), {
  rowKey: 'id',
  loading: false,
  loadingText: 'Loading...',
  emptyText: 'No data available'
})

const slots = defineSlots<{
  actions?: (props: { row: any }) => any
  [key: `cell-${string}`]: (props: { row: any; value: any }) => any
}>()

const hasActions = computed(() => !!slots.actions)

function getNestedValue(obj: any, path: string): any {
  return path.split('.').reduce((value, key) => value?.[key], obj)
}
</script>
