<template>
  <div>
    <div class="tabs">
      <button v-for="branch in branches" :key="branch.id" @click="selectBranch(branch.id || 0)"
        :class="{ active: branch.id === selectedBranchId }">
        {{ branch.name }}
      </button>
      <button @click="selectBranch(0)">All Branches</button>
      <VueDatePicker v-model="latestDate"></VueDatePicker>
      <!--      <input type="date" class="date picker rounded-3xl bg-stone-800" v-model="latestDate" @change="selectDate($event.target)" />-->
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
        <tr v-for="row in table.getRowModel().rows" :key="row.id">
          <td v-for="cell in row.getAllCells()" :key="cell.id">
            <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import VueDatePicker from '@vuepic/vue-datepicker'
import { useDailySalesStore } from '@/stores/dailySales'
import { storeToRefs } from 'pinia'
import { computed, isRef, onMounted, ref, toRef, watch } from 'vue'
import { getCoreRowModel, getPaginationRowModel, useVueTable, FlexRender } from '@tanstack/vue-table'
import { useBranchStore } from '@/stores/branches'
import { parseISO, format } from 'date-fns'
import '@vuepic/vue-datepicker/dist/main.css';

const conformDate = (date) => format(date, 'yyyy-MM-dd')

const latestDate = ref(conformDate(new Date()))

watch(latestDate, (newVal, oldVal) => {
  console.log(newVal, "turned from", oldVal)
  setTimeout(() => console.log(latestDate.value, typeof latestDate.value, 'LATEST VALUE NEW'), 1000)
})

const salesStore = useDailySalesStore()
const { sales } = storeToRefs(salesStore)
const selectedBranchId = ref(1)

const outputFormat = (record) => ({
  product_name: record.product.name,
  quantity: record.quantity,
  price: record.product.unit_price,
  branch: record.branch.name,
  created_at: record.created_at
})

const selectDate = (date) => { latestDate.value = conformDate(date) }

const filteredData = computed(() => {

  let result = sales.value

  if (selectedBranchId.value != 0) {
    result = result.filter(sale => sale.branch_id === selectedBranchId.value)
  }

  if (latestDate.value) {
    console.log('=-=-=-=-=-=-=-=-=-')
    result = result.filter(sale => conformDate(new Date(sale.created_at)) === conformDate(latestDate.value))
    console.log(result, "result")
  }


  return result.flatMap(record => outputFormat(record));
});

const branchStore = useBranchStore()

const { branches } = storeToRefs(branchStore)

onMounted(() => {
  salesStore.getDailySales()
  branchStore.getBranches()
})

watch(selectedBranchId, (newVal, oldVal) => { console.log(newVal, "turned from", oldVal) })

const columns = [
  {
    "accessorKey": "product_name",
    "header": "Name"
  },
  {
    "accessorKey": "quantity",
    "header": "Quantity",
  },
  {
    "accessorKey": "price",
    "header": "Price",
  },
  {
    "accessorKey": "branch",
    "header": "Branch",
  },
  {
    "accessorKey": "created_at",
    "header": "Branch ID",
  },
]


const selectBranch = (id) => {
  selectedBranchId.value = id
}

const tableOptions = {
  get data() {
    return filteredData.value
  },
  get columns() {
    return columns
  },
  getCoreRowModel: getCoreRowModel(),
}

const table = useVueTable(tableOptions)

</script>

<style>
.tabs button {
  padding: 10px;
  margin: 5px;
  cursor: pointer;
}

.tabs .active {
  background-color: #007BFF;
  color: white;
}
</style>
