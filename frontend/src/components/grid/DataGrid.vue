<template>
  <div class="data-grid-container">
    <!-- Toolbar -->
    <div v-if="showToolbar" class="grid-toolbar bg-white border-b border-gray-200 px-4 py-3 sm:px-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <!-- Left: Search and filters -->
        <div class="flex items-center gap-2 flex-1">
          <!-- Global search -->
          <div v-if="searchable" class="relative flex-1 max-w-xs">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
            </div>
            <input
              v-model="globalFilter"
              type="text"
              :placeholder="searchPlaceholder"
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
            />
          </div>

          <!-- Column filters toggle (mobile) -->
          <button
            v-if="filterable"
            @click="showFilters = !showFilters"
            type="button"
            class="sm:hidden inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
          >
            <FunnelIcon class="h-5 w-5" />
          </button>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2">
          <!-- Bulk actions -->
          <div v-if="selectable && selectedRows.length > 0" class="flex items-center gap-2">
            <span class="text-sm text-gray-700">
              {{ selectedRows.length }} selected
            </span>
            <slot name="bulk-actions" :selected="selectedRows">
              <button
                type="button"
                @click="handleBulkDelete"
                class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50"
              >
                <TrashIcon class="h-4 w-4 mr-1" />
                Delete
              </button>
            </slot>
          </div>

          <!-- Export -->
          <button
            v-if="exportable"
            @click="handleExport"
            type="button"
            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            <ArrowDownTrayIcon class="h-4 w-4 sm:mr-1" />
            <span class="hidden sm:inline">Export</span>
          </button>

          <!-- Refresh -->
          <button
            v-if="refreshable"
            @click="emit('refresh')"
            type="button"
            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            <ArrowPathIcon :class="['h-4 w-4', loading && 'animate-spin']" />
          </button>

          <!-- Custom actions slot -->
          <slot name="toolbar-actions"></slot>
        </div>
      </div>

      <!-- Column filters (collapsible on mobile) -->
      <div v-if="filterable && showFilters" class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div v-for="column in table.getAllColumns().filter(c => c.getCanFilter())" :key="column.id">
          <label :for="`filter-${column.id}`" class="block text-xs font-medium text-gray-700 mb-1">
            {{ column.columnDef.header }}
          </label>
          <input
            :id="`filter-${column.id}`"
            :value="column.getFilterValue() as string"
            @input="e => column.setFilterValue((e.target as HTMLInputElement).value)"
            type="text"
            placeholder="Filter..."
            class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500"
          />
        </div>
      </div>
    </div>

    <!-- Table container -->
    <div class="overflow-x-auto">
      <div class="inline-block min-w-full align-middle">
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
          <!-- Loading overlay -->
          <div v-if="loading" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10">
            <div class="flex flex-col items-center">
              <ArrowPathIcon class="h-8 w-8 text-primary-600 animate-spin" />
              <p class="mt-2 text-sm text-gray-600">Loading...</p>
            </div>
          </div>

          <!-- Table -->
          <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
              <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                <!-- Select all checkbox -->
                <th v-if="selectable" scope="col" class="relative px-4 sm:px-6 py-3 w-12">
                  <input
                    type="checkbox"
                    :checked="table.getIsAllRowsSelected()"
                    :indeterminate="table.getIsSomeRowsSelected()"
                    @change="table.toggleAllRowsSelected()"
                    class="h-5 w-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                  />
                </th>

                <!-- Column headers -->
                <th
                  v-for="header in headerGroup.headers"
                  :key="header.id"
                  scope="col"
                  class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                  :class="header.column.columnDef.meta?.headerClass"
                >
                  <div
                    v-if="!header.isPlaceholder"
                    :class="[
                      'flex items-center gap-2',
                      header.column.getCanSort() && 'cursor-pointer select-none'
                    ]"
                    @click="header.column.getCanSort() && header.column.toggleSorting()"
                  >
                    <FlexRender
                      :render="header.column.columnDef.header"
                      :props="header.getContext()"
                    />

                    <!-- Sort indicator -->
                    <span v-if="header.column.getCanSort()">
                      <ChevronUpDownIcon
                        v-if="!header.column.getIsSorted()"
                        class="h-4 w-4 text-gray-400"
                      />
                      <ChevronUpIcon
                        v-else-if="header.column.getIsSorted() === 'asc'"
                        class="h-4 w-4 text-primary-600"
                      />
                      <ChevronDownIcon
                        v-else
                        class="h-4 w-4 text-primary-600"
                      />
                    </span>
                  </div>
                </th>

                <!-- Actions column -->
                <th v-if="$slots.actions" scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                  <span class="sr-only">Actions</span>
                </th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
              <!-- Empty state -->
              <tr v-if="table.getRowModel().rows.length === 0 && !loading">
                <td :colspan="columnCount" class="px-6 py-12 text-center">
                  <slot name="empty">
                    <div class="flex flex-col items-center">
                      <InboxIcon class="h-12 w-12 text-gray-400" />
                      <h3 class="mt-2 text-sm font-medium text-gray-900">{{ emptyMessage }}</h3>
                      <p class="mt-1 text-sm text-gray-500">{{ emptyDescription }}</p>
                    </div>
                  </slot>
                </td>
              </tr>

              <!-- Data rows -->
              <tr
                v-for="row in table.getRowModel().rows"
                :key="row.id"
                :class="[
                  rowClickable && 'cursor-pointer hover:bg-gray-50',
                  selectedRows.includes(row.original) && 'bg-primary-50'
                ]"
                @click="handleRowClick(row)"
              >
                <!-- Select checkbox -->
                <td v-if="selectable" class="relative px-4 sm:px-6 py-4 w-12">
                  <input
                    type="checkbox"
                    :checked="row.getIsSelected()"
                    @change="row.toggleSelected()"
                    @click.stop
                    class="h-5 w-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                  />
                </td>

                <!-- Data cells -->
                <td
                  v-for="cell in row.getVisibleCells()"
                  :key="cell.id"
                  class="whitespace-nowrap px-3 py-4 text-sm text-gray-900"
                  :class="cell.column.columnDef.meta?.cellClass"
                >
                  <FlexRender
                    :render="cell.column.columnDef.cell"
                    :props="cell.getContext()"
                  />
                </td>

                <!-- Actions cell -->
                <td v-if="$slots.actions" class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                  <slot name="actions" :row="row.original" :index="row.index"></slot>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div
      v-if="paginated && table.getPageCount() > 0"
      class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
    >
      <!-- Mobile pagination -->
      <div class="flex-1 flex justify-between sm:hidden">
        <button
          @click="table.previousPage()"
          :disabled="!table.getCanPreviousPage()"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Previous
        </button>
        <button
          @click="table.nextPage()"
          :disabled="!table.getCanNextPage()"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Next
        </button>
      </div>

      <!-- Desktop pagination -->
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
          <p class="text-sm text-gray-700">
            Showing
            <span class="font-medium">{{ table.getState().pagination.pageIndex * table.getState().pagination.pageSize + 1 }}</span>
            to
            <span class="font-medium">{{ Math.min((table.getState().pagination.pageIndex + 1) * table.getState().pagination.pageSize, table.getFilteredRowModel().rows.length) }}</span>
            of
            <span class="font-medium">{{ table.getFilteredRowModel().rows.length }}</span>
            results
          </p>

          <!-- Page size selector -->
          <select
            :value="table.getState().pagination.pageSize"
            @change="table.setPageSize(Number(($event.target as HTMLSelectElement).value))"
            class="block rounded-md border-gray-300 py-1.5 pl-3 pr-10 text-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500"
          >
            <option v-for="size in pageSizes" :key="size" :value="size">
              {{ size }} per page
            </option>
          </select>
        </div>

        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <button
              @click="table.setPageIndex(0)"
              :disabled="!table.getCanPreviousPage()"
              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <ChevronDoubleLeftIcon class="h-5 w-5" />
            </button>
            <button
              @click="table.previousPage()"
              :disabled="!table.getCanPreviousPage()"
              class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <ChevronLeftIcon class="h-5 w-5" />
            </button>

            <!-- Page numbers -->
            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
              Page {{ table.getState().pagination.pageIndex + 1 }} of {{ table.getPageCount() }}
            </span>

            <button
              @click="table.nextPage()"
              :disabled="!table.getCanNextPage()"
              class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <ChevronRightIcon class="h-5 w-5" />
            </button>
            <button
              @click="table.setPageIndex(table.getPageCount() - 1)"
              :disabled="!table.getCanNextPage()"
              class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <ChevronDoubleRightIcon class="h-5 w-5" />
            </button>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts" generic="TData">
import { ref, computed, watch } from 'vue'
import {
  useVueTable,
  getCoreRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  FlexRender,
  type ColumnDef,
  type SortingState,
  type RowSelectionState,
  type PaginationState
} from '@tanstack/vue-table'
import {
  MagnifyingGlassIcon,
  FunnelIcon,
  TrashIcon,
  ArrowDownTrayIcon,
  ArrowPathIcon,
  InboxIcon,
  ChevronUpDownIcon,
  ChevronUpIcon,
  ChevronDownIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  ChevronDoubleLeftIcon,
  ChevronDoubleRightIcon
} from '@heroicons/vue/24/outline'

interface Props {
  data: TData[]
  columns: ColumnDef<TData, any>[]
  loading?: boolean
  selectable?: boolean
  paginated?: boolean
  searchable?: boolean
  filterable?: boolean
  exportable?: boolean
  refreshable?: boolean
  rowClickable?: boolean
  showToolbar?: boolean
  searchPlaceholder?: string
  emptyMessage?: string
  emptyDescription?: string
  initialPageSize?: number
  pageSizes?: number[]
}

interface Emits {
  (e: 'row-click', row: TData): void
  (e: 'selection-change', rows: TData[]): void
  (e: 'bulk-delete', rows: TData[]): void
  (e: 'export'): void
  (e: 'refresh'): void
}

const props = withDefaults(defineProps<Props>(), {
  loading: false,
  selectable: false,
  paginated: true,
  searchable: true,
  filterable: false,
  exportable: false,
  refreshable: false,
  rowClickable: false,
  showToolbar: true,
  searchPlaceholder: 'Search...',
  emptyMessage: 'No data found',
  emptyDescription: 'Try adjusting your search or filters',
  initialPageSize: 20,
  pageSizes: () => [10, 20, 50, 100]
})

const emit = defineEmits<Emits>()

// State
const globalFilter = ref('')
const sorting = ref<SortingState>([])
const rowSelection = ref<RowSelectionState>({})
const pagination = ref<PaginationState>({
  pageIndex: 0,
  pageSize: props.initialPageSize
})
const showFilters = ref(false)

// Table instance
const table = useVueTable({
  get data() {
    return props.data
  },
  get columns() {
    return props.columns
  },
  state: {
    get sorting() {
      return sorting.value
    },
    get globalFilter() {
      return globalFilter.value
    },
    get rowSelection() {
      return rowSelection.value
    },
    get pagination() {
      return pagination.value
    }
  },
  onSortingChange: updaterOrValue => {
    sorting.value = typeof updaterOrValue === 'function'
      ? updaterOrValue(sorting.value)
      : updaterOrValue
  },
  onGlobalFilterChange: updaterOrValue => {
    globalFilter.value = typeof updaterOrValue === 'function'
      ? updaterOrValue(globalFilter.value)
      : updaterOrValue
  },
  onRowSelectionChange: updaterOrValue => {
    rowSelection.value = typeof updaterOrValue === 'function'
      ? updaterOrValue(rowSelection.value)
      : updaterOrValue
  },
  onPaginationChange: updaterOrValue => {
    pagination.value = typeof updaterOrValue === 'function'
      ? updaterOrValue(pagination.value)
      : updaterOrValue
  },
  getCoreRowModel: getCoreRowModel(),
  getFilteredRowModel: getFilteredRowModel(),
  getSortedRowModel: getSortedRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
  enableRowSelection: props.selectable,
  enableSorting: true,
  enableFilters: props.filterable
})

// Computed
const selectedRows = computed(() => {
  return table.getSelectedRowModel().rows.map(row => row.original)
})

const columnCount = computed(() => {
  let count = props.columns.length
  if (props.selectable) count++
  if (props.$slots?.actions) count++
  return count
})

// Watch for selection changes
watch(selectedRows, (newSelection) => {
  emit('selection-change', newSelection)
})

// Methods
function handleRowClick(row: any) {
  if (props.rowClickable) {
    emit('row-click', row.original)
  }
}

function handleBulkDelete() {
  emit('bulk-delete', selectedRows.value)
}

function handleExport() {
  emit('export')
}

// Expose methods for parent component
defineExpose({
  clearSelection: () => table.resetRowSelection(),
  selectAll: () => table.toggleAllRowsSelected(true),
  getSelectedRows: () => selectedRows.value,
  resetFilters: () => {
    globalFilter.value = ''
    table.resetColumnFilters()
  }
})
</script>

<style scoped>
/* Touch-friendly on mobile */
@media (max-width: 640px) {
  .data-grid-container table {
    font-size: 0.875rem;
  }

  .data-grid-container td,
  .data-grid-container th {
    min-height: 48px; /* Touch target */
  }
}

/* Ensure table scrolls horizontally on small screens */
.data-grid-container {
  position: relative;
}

/* Loading overlay */
.data-grid-container > div:has(.overflow-x-auto) {
  position: relative;
}

/* Smooth transitions */
tbody tr {
  transition: background-color 150ms ease-in-out;
}

/* Checkbox indeterminate state */
input[type="checkbox"]:indeterminate {
  background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M4 8h8'/%3e%3c/svg%3e");
}
</style>
