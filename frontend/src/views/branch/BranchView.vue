<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Tables" main>

      </SectionTitleLineWithButton>
      <NotificationBar color="info" :icon="mdiMonitorCellphone">
        <b>Responsive table.</b> Collapses on mobile
      </NotificationBar>

      <CardBox class="mb-6" has-table>
        <RouterLink to="/addBranch">
          <BaseButton label="Add Branch" color="brown"></BaseButton>
        </RouterLink>
        <TableSampleClients :columns="columns" @delete="deleteRecord" @edit="editRecord" :received="branches" />
      </CardBox>
      <h1>{{ $t('message.hello') }}</h1>

      <SectionTitleLineWithButton :icon="mdiTableOff" title="Empty variation" />

      <NotificationBar color="danger" :icon="mdiTableOff">
        <b>Empty table.</b> When there's nothing to show
      </NotificationBar>

      <CardBox>
        <CardBoxComponentEmpty />
      </CardBox>
      <CardBoxModal
        v-model="isModalActive"
        title="Please confirm"
        button="danger"
        buttonLabel="Confirm"
        has-cancel
        @confirm="confirmDelete"
      >
        <p>Are you sure you want to delete this branch?</p>
      </CardBoxModal>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { mdiMonitorCellphone, mdiTableBorder, mdiTableOff, mdiGithub } from '@mdi/js'
import SectionMain from '@/components/section/SectionMain.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import TableSampleClients from '@/components/table/TableBase.vue'
import CardBox from '@/components/cardbox/CardBox.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/components/section/SectionTitleLineWithButton.vue'
import CardBoxComponentEmpty from '@/components/cardbox/CardBoxComponentEmpty.vue'
import CardBoxModal from '@/components/cardbox/CardBoxModal.vue'
import { useBranchStore } from '@/stores/branches'
import { storeToRefs } from 'pinia'
import { isReactive, isRef, onMounted, ref } from 'vue'
import BaseButton from '@/components/base/BaseButton.vue'
import { RouterLink, useRouter } from 'vue-router'

const branchStore = useBranchStore();
const router = useRouter()
const { branches } = storeToRefs(branchStore)

const editRecord = (record) => {
  router.push({ name: "EditBranch", params: { id: record.original.id } })
}

const isModalActive = ref(false)
const branchToDelete = ref(null)

const deleteRecord = (record) => {
  branchToDelete.value = record.original
  isModalActive.value = true
}

const confirmDelete = () => {
  if (branchToDelete.value) {
    branchStore.deleteBranch(branchToDelete.value.id)
    branchToDelete.value = null
  }
}

onMounted(() => {
  branchStore.getBranches()
});

const columns = [
  {
    "accessorKey": "name",
    "header": "Name"
  },
  {
    "accessorKey": "type",
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
