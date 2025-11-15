<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Edit Inventory Adjustment">
        <BaseButton to="/inventoryAdjustments" label="Back" />
      </SectionTitleLineWithButton>

      <CardBox class="mb-6" has-table>
        <Form v-if="initialData" :initial-values="initialData" @submit="onSubmit" :validation-schema="schema">
          <FormControl name="inventory_item_id" :options="formattedInventoryItems"
            placeholder="Select inventory item" />
          <ValidatedFormControl name="quantity" type="number" placeholder="Enter quantity" />
          <ValidatedFormControl name="adjustment_type" type="select" :options="adjustmentTypeOptions"
            placeholder="Select adjustment type" />
          <ValidatedFormControl name="date" type="date" />
          <ValidatedFormControl name="notes" type="textarea" placeholder="Enter notes" />

          <button type="submit" class="btn btn-primary">
            Update Adjustment
          </button>
        </Form>
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useInventoryAdjustmentStore } from '@/stores/inventoryAdjustment'
import { useRouter } from 'vue-router'
import CardBox from '@/components/cardbox/CardBox.vue'
// import TitleBar from '@/components/TitleBar.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import FormField from '@/components/form/FormField.vue'
import FormControl from '@/components/form/FormControl.vue'
import { useInventoryItemStore } from '@/stores/inventoryItem'
import { Form } from 'vee-validate'
import * as Yup from 'yup'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionMain from '@/components/section/SectionMain.vue'
import ValidatedFormControl from '@/components/ValidatedFormControl.vue'
import SectionTitleLineWithButton from '@/components/section/SectionTitleLineWithButton.vue'
import { mdiTableBorder } from '@mdi/js'

const props = defineProps(['id'])
const router = useRouter()
const store = useInventoryAdjustmentStore()
const inventoryItemStore = useInventoryItemStore()

const schema = Yup.object({
  inventory_item_id: Yup.mixed().required('Inventory item is required'),
  quantity: Yup.number().required('Quantity is required').min(0, 'Quantity must be positive'),
  adjustment_type: Yup.string()
    .oneOf(['increase', 'decrease'], 'Please select a valid adjustment type')
    .required('Adjustment type is required'),
  date: Yup.date().required('Date is required'),
  notes: Yup.string()
})

const adjustmentTypeOptions = [
  { id: 'addition', name: 'addition' },
  { id: 'subtraction', name: 'subtraction' }
]

onMounted(async () => {
  await Promise.all([
    store.fetchInventoryAdjustments(),
    inventoryItemStore.getInventoryItem()
  ])
})

const formattedInventoryItems = computed(() =>
  inventoryItemStore.data.map(item => ({
    id: item.id,
    name: item.item_name
  }))
)

const initialData = computed(() => {
  const adjustment = store.inventoryAdjustments.find(val => Number(val.id) === Number(props.id))

  if (adjustment) {
    return {
      inventory_item_id: adjustment.inventory_item_id,
      quantity: adjustment.quantity,
      adjustment_type: adjustment.adjustment_type,
      date: adjustment.date,
      notes: adjustment.notes
    }
  }
  return null
})

const onSubmit = async (values) => {
  const updateData = {
    id: props.id,
    ...values
  }

  const response = await store.updateInventoryAdjustment(props.id, updateData)
  if (response.data) {
    router.push('/inventoryAdjustments')
  }
}
</script>
