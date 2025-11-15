<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Tables" main>

      </SectionTitleLineWithButton>
      <NotificationBar color="info" :icon="mdiMonitorCellphone">
        <b>Responsive table.</b> Collapses on mobile
      </NotificationBar>

      <CardBox class="mb-6" has-table>
        <AddRecordBtn label="Add New Commission" to="/addCommission" />
        <TableSampleClients @edit="editRecord" @delete="deleteRecord" :columns="columns" :received="data" />
      </CardBox>

      <SectionTitleLineWithButton :icon="mdiTableOff" title="Empty variation" />

      <NotificationBar color="danger" :icon="mdiTableOff">
        <b>Empty table.</b> When there's nothing to show
      </NotificationBar>

      <CardBoxModal v-model='isModalActive' title="Please Confirm" button="danger" buttonLabel="Confirm" has-cancel
        @confirm="confirmDelete">
        <p>Are you sure you want to delete this record?</p>
      </CardBoxModal>

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
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

import TableSampleClients from '@/components/table/TableBase.vue'
import AddRecordBtn from '@/components/RecordBtnComponent.vue'
import CardBoxModal from '@/components/cardbox/CardBoxModal.vue'
import { useCommissionStore } from '@/stores/commissions'

const dataStore = useCommissionStore();
const { data } = storeToRefs(dataStore)
const router = useRouter()
const isModalActive = ref(false)

const recordToDelete = ref(null)

const editRecord = (record) => {
  console.log(record.original, "record")
  router.push({ name: "EditCommission", params: { id: record.original.id } })
}

const deleteRecord = (record) => {

}

const confirmDelete = () => {
  if (recordToDelete.value) {
    // dataStore.deleteMM(recordToDelete.value.id)
    recordToDelete.value = null
  }
}

onMounted(() => {
  dataStore.getCommission()
});

const columns = [
  {
    "accessorKey": "product.name",
    "header": "Name"
  },
  {
    "accessorKey": "commission_recipient.name",
    "header": "Recipient"
  },
  {
    "accessorKey": "discount_amount",
    "header": "Discount",
  },
  {
    "accessorKey": "status",
    "header": "Status"
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
