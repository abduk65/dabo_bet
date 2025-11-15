<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Tables" main>

      </SectionTitleLineWithButton>
      <NotificationBar color="info" :icon="mdiMonitorCellphone">
        <b>Responsive table.</b> Collapses on mobile
      </NotificationBar>

      <CardBox class="mb-6" has-table>
        <AddRecordBtn label="Add Expense" to="/addExpense" />
        <TableSampleClients @edit="editRecord" @delete="deleteRecord" :columns="columns" :received="expenses" />
      </CardBox>

      <NotificationBar color="danger" :icon="mdiTableOff">
        <b>Empty table.</b> When there's nothing to show
      </NotificationBar>

      <CardBox>
        <CardBoxComponentEmpty />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>


<script setup>

import { mdiMonitorCellphone, mdiTableBorder, mdiTableOff } from '@mdi/js'
import SectionMain from '@/components/section/SectionMain.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import CardBox from '@/components/cardbox/CardBox.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/components/section/SectionTitleLineWithButton.vue'
import CardBoxComponentEmpty from '@/components/cardbox/CardBoxComponentEmpty.vue'
import { useBranchStore } from '@/stores/branches'
import { storeToRefs } from 'pinia'
import { onMounted } from 'vue'
import TableSampleClients from '@/components/table/TableBase.vue'
import AddRecordBtn from '@/components/RecordBtnComponent.vue'
import { useExpensesStore } from '@/stores/expenses'
import { useRouter } from 'vue-router'

const dataStore = useExpensesStore();

const { data: expenses } = storeToRefs(dataStore)

onMounted(() => {
  dataStore.getExpenses()
});

const router = useRouter();

const editRecord = (record) => {
  console.log(record.original.id)
  router.push(`/editExpense/${record.original.id}`)
}

const deleteRecord = (id) => {
  console.log(id)
}

const columns = [
  {
    "accessorKey": "type",
    "header": "Name"
  },
  {
    "accessorKey": "user.name",
    "header": "Type"
  },
  {
    "accessorKey": "branch.name",
    "header": "Branch"
  },
  {
    "accessorKey": "created_at",
    "header": "Created"
  },
  {
    "accessorKey": "updated_at",
    "header": "Modified"
  },

]

</script>
