<script setup>
import { ref, onMounted } from 'vue'
import { useInventoryAdjustmentStore } from '@/stores/inventoryAdjustment'
import { useRouter } from 'vue-router'
import { mdiMonitorCellphone, mdiTableBorder, mdiTableOff } from '@mdi/js'
import SectionMain from '@/components/section/SectionMain.vue'
import NotificationBar from '@/components/NotificationBar.vue'
import CardBox from '@/components/cardbox/CardBox.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/components/section/SectionTitleLineWithButton.vue'
import CardBoxComponentEmpty from '@/components/cardbox/CardBoxComponentEmpty.vue'
import TableSampleClients from '@/components/table/TableBase.vue'
import AddRecordBtn from '@/components/RecordBtnComponent.vue'

const router = useRouter()
const inventoryAdjustmentStore = useInventoryAdjustmentStore()
const items = ref([])

onMounted(async () => {
  console.log('items.', items, inventoryAdjustmentStore)
  items.value = await inventoryAdjustmentStore.fetchInventoryAdjustments()
  console.log('items.', items)
})

const columns = [
  {
    "accessorKey": "id",
    "header": "ID"
  },
  {
    "accessorKey": "inventory_item.item_name",
    "header": "Item"
  },
  {
    "accessorKey": "quantity",
    "header": "Quantity"
  },
  {
    "accessorKey": "operation",
    "header": "Adjustment Type"
  },
  {
    "accessorKey": "created_at",
    "header": "Date"
  }
]

const handleEdit = (row) => {
  router.push({ name: 'EditInventoryAdjustment', params: { id: row.original.id } })
}

const handleDelete = async (row) => {
  try {
    await inventoryAdjustmentStore.deleteInventoryAdjustment(row.original.id)
    items.value = items.value.filter(item => item.id !== row.original.id)
  } catch (error) {
    console.error('Error deleting inventory adjustment:', error)
  }
}
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Inventory Adjustments" main>
      </SectionTitleLineWithButton>
      <NotificationBar color="info" :icon="mdiMonitorCellphone">
        <b>Responsive table.</b> Collapses on mobile
      </NotificationBar>

      <CardBox class="mb-6" has-table>
        <AddRecordBtn label="New Adjustment" to="/addInventoryAdjustment" />
        <TableSampleClients :columns="columns" :received="items" @edit="handleEdit" @delete="handleDelete" />
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
