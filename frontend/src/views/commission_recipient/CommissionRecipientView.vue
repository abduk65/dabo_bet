<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Tables" main>

      </SectionTitleLineWithButton>
      <NotificationBar color="info" :icon="mdiMonitorCellphone">
        <b>Responsive table.</b> Collapses on mobile
      </NotificationBar>

      <CardBox class="mb-6" has-table>
        <AddRecordBtn label="Add Recipient" to="/addCommissionRecipient" />
        <TableSampleClients @edit="editRecord" @delete="deleteRecord" :columns="columns" :received="data" />
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
        <p>{{ modalMessage }}</p>
      </CardBoxModal>
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
import { onMounted, ref } from 'vue'
import TableSampleClients from '@/components/table/TableBase.vue'
import AddRecordBtn from '@/components/RecordBtnComponent.vue'
import { useCommissionRecipientStore } from '@/stores/CommissionRecipient'
import { useRouter } from 'vue-router'
import CardBoxModal from '@/components/cardbox/CardBoxModal.vue'

const dataStore = useCommissionRecipientStore();

const { data } = storeToRefs(dataStore)

const router = useRouter()

const editRecord = (record) => {
  router.push({ name: "EditCommissionRecipient", params: { id: record.original.id } })
}

const isModalActive = ref(false)
const modalMessage = ref('')
const recordToDelete = ref(null)

const deleteRecord = (record) => {
  isModalActive.value = true
  modalMessage.value = `Are you sure you want to delete ${record.original.name}?`
  recordToDelete.value = record.original.id
}

const confirmDelete = async () => {
  console.log("fdld", recordToDelete)
  const rr = await dataStore.deleteCommissionRecipient(recordToDelete.value)
  console.log(rr, "brok")
  isModalActive.value = false
  recordToDelete.value = null
}

onMounted(() => {
  dataStore.getCommission()
})

const columns = [
  {
    "accessorKey": "name",
    "header": "Name"
  },
  {
    "accessorKey": "branch.name",
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
<style scoped></style>
