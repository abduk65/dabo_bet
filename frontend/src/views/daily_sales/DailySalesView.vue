<template>
  <LayoutAuthenticated>
    <SectionMain>
      <div class="mb-6">
        <SectionTitleLineWithButton 
          :icon="mdiTableBorder" 
          title="Daily Sales Management" 
          main
          class="text-2xl font-bold"
        >
        </SectionTitleLineWithButton>
        
        <NotificationBar 
          color="info" 
          :icon="mdiMonitorCellphone"
          class="mt-4 shadow-sm"
        >
          <b>Responsive table.</b> Optimized for all screen sizes
        </NotificationBar>
      </div>

      <div class="flex justify-between items-center mb-6">
        <AddRecordBtn 
          label="Add Daily Sales" 
          to="/addDailySales"
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-colors duration-200"
        />
      </div>

      <CardBox class="mb-6 shadow-lg rounded-lg overflow-hidden" has-table>
        <TableDailySales class="w-full"></TableDailySales>
      </CardBox>

      <div class="mt-12">
        <SectionTitleLineWithButton 
          :icon="mdiTableOff" 
          title="No Records" 
          class="text-xl font-semibold"
        />

        <NotificationBar 
          color="danger" 
          :icon="mdiTableOff"
          class="mt-4 shadow-sm"
        >
          <b>Empty table.</b> No records found
        </NotificationBar>

        <CardBox class="mt-4 shadow-md">
          <CardBoxComponentEmpty />
        </CardBox>
      </div>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { mdiMonitorCellphone, mdiTableBorder, mdiTableOff, mdiGithub } from '@mdi/js'
import SectionMain from '@/components/section/SectionMain.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import TableSampleClients from '@/components/table/TableBase.vue'
import CardBox from '@/components/cardbox/CardBox.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/components/section/SectionTitleLineWithButton.vue'
import CardBoxComponentEmpty from '@/components/cardbox/CardBoxComponentEmpty.vue'
import { useDailySalesStore } from '@/stores/dailySales'
import { isReactive, isRef, onMounted, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { RouterLink } from 'vue-router'
import TableDailySales from '@/components/table/TableDailySales.vue'
import AddRecordBtn from '@/components/RecordBtnComponent.vue'

const dailySales = useDailySalesStore()
const { sales } = storeToRefs(dailySales)

const columns = [
  {
    "accessorKey": "product.name",
    "header": "Name"
  },
  {
    "accessorKey": "product.type",
    "header": "Type"
  },
  {
    "accessorKey": "created_at",
    "header": "Created"
  },
  {
    "accessorKey": "updated_at",
    "header": "modified"
  },
]
</script>

<style scoped>
.section-main {
  @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8;
}

:deep(.notification-bar) {
  @apply rounded-lg border-l-4;
}

:deep(.card-box) {
  @apply bg-white dark:bg-gray-800;
}

:deep(.table-wrapper) {
  @apply overflow-x-auto;
}
</style>
