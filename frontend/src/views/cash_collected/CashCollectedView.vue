<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Tables" main>

      </SectionTitleLineWithButton>
      <NotificationBar color="info" :icon="mdiMonitorCellphone">
        <b>Responsive table.</b> Collapses on mobile
      </NotificationBar>

      <CardBox class="mb-6" has-table>
        <AddRecordBtn label="Record Cash Collected" to="/addCashCollected" />
        <TableSampleClients :columns="columns" :received="data" @edit="editRecord" @delete="deleteRecord" />
      </CardBox>

      <SectionTitleLineWithButton :icon="mdiTableOff" title="Empty variation" />

      <NotificationBar color="danger" :icon="mdiTableOff">
        <b>Empty table.</b> When there's nothing to show
      </NotificationBar>

      <CardBox>
        <CardBoxComponentEmpty />
      </CardBox>

      <CardBoxModal v-model="isModalActive" title="Please confirm" button="danger" buttonLabel="Confirm" has-cancel
        @confirm="confirmDelete">
        <p>Are you sure you want to delete this cash collection record?</p>
      </CardBoxModal>
    </SectionMain>
  </LayoutAuthenticated>
</template>


<script setup>

import { mdiMonitorCellphone, mdiTableBorder, mdiTableOff } from '@mdi/js'
import SectionMain from '@/components/section/SectionMain.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import CardBox from '@/components/cardbox/CardBox.vue'
import CardBoxModal from '@/components/cardbox/CardBoxModal.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/components/section/SectionTitleLineWithButton.vue'
import CardBoxComponentEmpty from '@/components/cardbox/CardBoxComponentEmpty.vue'
import { storeToRefs } from 'pinia'
import { onMounted, ref } from 'vue'
import TableSampleClients from '@/components/table/TableBase.vue'
import AddRecordBtn from '@/components/RecordBtnComponent.vue'
import { useCashCollectedStore } from '@/stores/cashCollected'
import { useRoute, useRouter } from 'vue-router'

const dataStore = useCashCollectedStore();

const { data } = storeToRefs(dataStore)
const router = useRouter()

const isModalActive = ref(false)
const recordToDelete = ref(null)

const editRecord = (record) => router.push({ name: "EditCashCollected", params: { id: record.original.id } })

const deleteRecord = (record) => {
  recordToDelete.value = record.original
  isModalActive.value = true
}

const confirmDelete = in() => {
  if (recordToDelete.value) {
    console.log(recordToDelete.value.id, 'RECOR')
    dataStore.deleteCashCollected(recordToDelete.value.id)
    recordToDelete.value = null
    isModalActive.value = false
  }
}

onMounted(() => {
  dataStore.getCashCollected()
});

const columns = [
  {
    "accessorKey": "branch.name",
    "header": "Name"
  },
  {
    "accessorKey": "amount",
    "header": "Type"
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
