<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Tables" main>

      </SectionTitleLineWithButton>
      <NotificationBar color="info" :icon="mdiMonitorCellphone">
        <b>Responsive table.</b> Collapses on mobile
      </NotificationBar>

      <CardBox class="mb-6" has-table>
        <AddRecordBtn label="label" to="/addInventoryItem" />
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
import { storeToRefs } from 'pinia'
import { onMounted } from 'vue'
import TableSampleClients from '@/components/table/TableBase.vue'
import AddRecordBtn from '@/components/RecordBtnComponent.vue'
import { useInventoryItemStore } from '@/stores/inventoryItem'
import { useBrandStore } from '@/stores/brands'
import { useRouter } from 'vue-router'


const dataStore = useInventoryItemStore();
const { data } = storeToRefs(dataStore)

const router = useRouter()
const editRecord = (record) => router.push({ name: "EditInventoryItem", params: { id: record.original.id } })

onMounted(() => {
  dataStore.getInventoryItem()
});

const columns = [
  {
    "accessorKey": "brand.name",
    "header": "Name"
  },
  {
    "accessorKey": "item_name",
    "header": "Type"
  },
  {
    "accessorKey": "user.name",
    "header": "Created"
  },
  {
    "accessorKey": "updated_at",
    "header": "Modified"
  },

]

</script>
