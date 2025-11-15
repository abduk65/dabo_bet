<template>
  <CardBoxModal v-model="isModalActive" title="Sample modal">
    <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
    <p>This is sample modal</p>
  </CardBoxModal>
  <CardBoxModal v-model="isModalDangerActive" title="Please confirm" button="danger" has-cancel>
    <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
    <p>This is sample modal</p>
  </CardBoxModal>

  <div class="flex justify-between">
    <div class="search-container">
      <input v-model="searchQuery" type="text" placeholder="Search..." @input="handleSearch" />
    </div>

    <!-- Filter Dropdown -->
    <div class="filter-container">
      <select v-model="selectedFilter" @change="handleFilterChange">
        <option value="">All</option>
        <option value="filter1">Filter 1</option>
        <option value="filter2">Filter 2</option>
        <!-- Add more filter options as needed -->
      </select>
    </div>

    <!-- Date Range Filter -->
    <div class="date-range-container">
      <input v-model="startDate" type="date" @change="handleDateRangeChange" />
      <input v-model="endDate" type="date" @change="handleDateRangeChange" />
    </div>
  </div>

  <table>
    <thead>
      <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
        <th v-for="header in headerGroup.headers" :key="header.id">
          <FlexRender :render="header.column.columnDef.header" :props="header.getContext()"></FlexRender>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="row in table.getRowModel().rows" 
          :key="row.id"
          class="cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/50"
          @click="$emit('row-click', row)">
        <td v-for="cell in row.getVisibleCells()" :key="cell.id">
          <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()"> </FlexRender>
        </td>
        <td class="flex gap-2">
          <BaseButton :key="'edit-'+row.id" label="Edit" type="submit" color="info" @click.stop="handleEdit(row)" />
          <BaseButton :key="'delete-'+row.id" label="Delete" type="submit" color="danger" @click.stop="handleDelete(row)" />
        </td>
      </tr>
    </tbody>
  </table>
  <div class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
    <BaseLevel>
      <BaseButtons>
        <BaseButton v-for="page in pagesList" :key="page" :active="page === currentPage" :label="page + 1"
          :color="page === currentPage ? 'lightDark' : 'whiteDark'" small @click="handlePagination(page)" />
      </BaseButtons>
      <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
    </BaseLevel>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, computed, reactive, isReactive, isRef } from 'vue'
import CardBoxModal from '@/components/cardbox/CardBoxModal.vue'
import BaseLevel from '@/components/base/BaseLevel.vue'
import BaseButtons from '@/components/base/BaseButtons.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import { useBranchStore } from '@/stores/branches'
import { useRouter } from 'vue-router'
import { FlexRender, getCoreRowModel, useVueTable, getPaginationRowModel } from '@tanstack/vue-table'

const props = defineProps(
  {
    received: {
      type: Array,
      required: true
    },
    columns: {
      type: Array,
      required: true
    }
  },
)

const handlePagination = (page) => table.setPageIndex(Number(page))
const searchQuery = ref('')

const endDate = ref('')
const startDate = ref('')
const selectedFilter = ref('')

const filteredData = computed(() => {
  return props.received.filter(row => {
    // console.log('row data', row)
    const matchesSearch = Object.values(row).some(term => {
      return term.toString().toLowerCase().includes(searchQuery.value.toLowerCase())
    })

    const matchesDateRange = (!startDate.value || new Date(row.value.created_at) >= new Date(startDate.value)) &&
      (!endDate.value || new Date(row.value.created_at) <= new Date(endDate.value))
    return matchesSearch && matchesDateRange
  })
})

onMounted(() => console.log(filteredData, "IS IT REACTIVE"))

const items = computed(() => props.received)

// watch([props.received, searchQuery, startDate, endDate], filteredData, { immediate: true });

const tableOptions = {
  get data() {
    return filteredData.value
  },
  get columns() {
    return props.columns
  },
  getCoreRowModel: getCoreRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
};

const table = useVueTable(tableOptions)

const isModalActive = ref(false)

const isModalDangerActive = ref(false)

const perPage = ref(10)

const currentPage = ref(0)

const itemsPaginated = computed(() =>
  items.value.slice(perPage.value * currentPage.value, perPage.value * (currentPage.value + 1))
)

const numPages = computed(() => Math.ceil(items.value.length / perPage.value))
console.log(numPages, "NUMBER OF PAGES")
const currentPageHuman = computed(() => currentPage.value + 1)

const pagesList = computed(() => {
  const pagesList = []

  for (let i = 0; i < numPages.value; i++) {
    pagesList.push(i)
  }

  return pagesList
})

const router = useRouter()
const emit = defineEmits(['delete', 'edit', 'row-click'])

const handleDelete = (record) => {
  // router.push(`/branches/${record.id}`)
  emit('delete', record)
}

const handleEdit = (record) => {
  emit('edit', record)
}

</script>
