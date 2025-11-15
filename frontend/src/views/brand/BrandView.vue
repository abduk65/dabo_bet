<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Tables" main>

      </SectionTitleLineWithButton>
      <NotificationBar color="info" :icon="mdiMonitorCellphone">
        <b>Responsive table.</b> Collapses on mobile
      </NotificationBar>


      <CardBox class="mb-6" has-table>
        <AddRecordBtn label="Add New Brand" to="/addBrand" />
        <TableSampleClients @delete="deleteRecord" @edit="editRecord" :columns="columns" :received="data" />
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
        <p>Are you sure you want to delete this brand?</p>
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
import AddRecordBtn from '@/components/RecordBtnComponent.vue'
import { onMounted, ref } from 'vue'
import TableSampleClients from '@/components/table/TableBase.vue'
import { useBranchStore } from '@/stores/branches'
import { storeToRefs } from 'pinia'
import { useBrandStore } from '@/stores/brands'
import { useRoute, useRouter } from 'vue-router'
import CardBoxModal from '@/components/cardbox/CardBoxModal.vue'

const dataStore = useBrandStore();
const { data } = storeToRefs(dataStore)
const router = useRouter()

onMounted(() => {
  dataStore.getBrands()
});

const editRecord = (record) => {
  router.push({ name: "EditBrand", params: { id: record.original.id } })
}

const isModalActive = ref(false)
const brandToDelete = ref(null)

const deleteRecord = (record) => {
  brandToDelete.value = record.original
  isModalActive.value = true
}

const confirmDelete = () => {
  if (brandToDelete.value) {
    dataStore.deleteData(brandToDelete.value.id)
    brandToDelete.value = null
  }
}

const columns = [
  {
    "accessorKey": "name",
    "header": "Name"
  },
  {
    "accessorKey": "product_type.name",
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
