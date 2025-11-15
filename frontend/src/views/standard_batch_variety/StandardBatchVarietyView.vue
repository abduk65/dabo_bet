<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Tables" main>

      </SectionTitleLineWithButton>
      <NotificationBar color="info" :icon="mdiMonitorCellphone">
        <b>Responsive table.</b> Collapses on mobile
      </NotificationBar>

      <CardBox class="mb-6" has-table>
        <AddRecordBtn label="label" to="/addStandardBatchVariety" />
        <TableSampleClients :columns="columns" :received="data" />
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
import { useStandardBatchVarietyStore } from '@/stores/standardBatchVariety'


const dataStore = useStandardBatchVarietyStore();

const { data } = storeToRefs(dataStore)


onMounted(() => {
  dataStore.getStandardBatchVariety()
});

const columns = [

  {
    "accessorKey": "name",
    "header": "Name"
  },
  {
    "accessorKey": "recipe.product.name",
    "header": "Recipe Id"
  },
  {
    "accessorKey": "single_factor_expected_output",
    "header": "Expected Output"
  },
  {
    "accessorKey": "updated_at",
    "header": "Modified"
  },

]

</script>
