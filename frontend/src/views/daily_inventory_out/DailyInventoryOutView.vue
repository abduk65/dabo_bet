<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Tables" main>

      </SectionTitleLineWithButton>
      <NotificationBar color="info" :icon="mdiMonitorCellphone">
        <b>Responsive table.</b> Collapses on mobile
      </NotificationBar>

      <CardBox class="mb-6" has-table>
        <AddRecordBtn label="Add Daily Inventory Out" to="/addDailyInventoryOut" />
        <TableSampleClients @edit="editRecord" @delete="deleteRecord" :columns="columns" :received="data" />
      </CardBox>

      <SectionTitleLineWithButton :icon="mdiTableOff" title="Empty variation" />

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
import { useDailyInventoryOut } from '@/stores/dailyInventoryOut'
import { useRouter } from 'vue-router'

const dataStore = useDailyInventoryOut();

const { data } = storeToRefs(dataStore)

const router = useRouter()

const editRecord = (record) => router.push({ name: "EditDailyInventoryOut", params: { id: record.original.id } })
const deleteRecord = (id) => console.log(id, "to be deleted inventory out")

onMounted(() => {
  dataStore.getDailyInventoryOut()
});

const columns = [
  {
    "accessorKey": "user.name",
    "header": "Given By"
  },
  {
    "accessorKey": "inventory_item.brand.name",
    "header": "Product"
  },
  {
    "accessorKey": "receiver_user.name",
    "header": "Received By"
  },
  {
    "accessorKey": "quantity",
    "header": "Quantity"
  },
  {
    "accessorKey": "unit.name",
    "header": "Unit"
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
