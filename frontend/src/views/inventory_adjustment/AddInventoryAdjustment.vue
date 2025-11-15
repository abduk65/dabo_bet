<template>
  <!-- <TitleBar :title-stack="['Inventory Management', 'Add Inventory Adjustment']" /> -->
  <LayoutAuthenticated>
    <SectionMain>

      <form @submit.prevent="submit">
        <FormField label="Inventory Item">
          <FormControl v-model="form.inventory_item_id" :options="inventoryItems">
          </FormControl>
        </FormField>

        <FormField label="Quantity">
          <FormControl v-model="form.quantity" type="number" placeholder="Enter quantity" />
        </FormField>

        <FormField label="Unit">
          <FormControl v-model="form.unit_id" :options="units">
          </FormControl>
        </FormField>

        <FormField label="Adjustment Type">
          <FormControl v-model="form.operation"
            :options="[{ name: 'Addition', value: 'addition' }, { name: 'Subtraction', value: 'subtraction' }]">
          </FormControl>
        </FormField>

        <FormField label="Notes">
          <FormControl v-model="form.remark" type="textarea" placeholder="Enter notes" />
        </FormField>

        <div class="flex justify-end space-x-2">
          <BaseButton type="button" color="info" outline label="Cancel" @click="router.back()" />
          <BaseButton type="submit" color="info" label="Save" />
        </div>
      </form>
    </SectionMain>
  </LayoutAuthenticated>

</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useInventoryAdjustmentStore } from '@/stores/inventoryAdjustment'
import { useRouter } from 'vue-router'
// import CardComponent from '@/components/CardComponent.vue'
import SectionMain from '@/components/section/SectionMain.vue'
// import TitleBar from '@/components/TitleBar.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import FormField from '@/components/form/FormField.vue'
import FormControl from '@/components/form/FormControl.vue'
import { useInventoryItemStore } from '@/stores/inventoryItem'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import { useUnitStore } from '@/stores/unit'
const router = useRouter()
const store = useInventoryAdjustmentStore()
const inventoryItemStore = useInventoryItemStore()
const unitStore = useUnitStore()

const inventoryItems = ref([])
const form = ref({
  inventory_item_id: '',
  quantity: 0,
  operation: '',
  remark: '',
  unit_id: 0,
})

const units = ref([])

const loadInventoryItems = async () => {
  await inventoryItemStore.getInventoryItem()
  inventoryItems.value = inventoryItemStore.data
  await unitStore.getUnits()
  units.value = unitStore.data

  console.log(units, 'IIIIIIIIIIIIIIIIIIIIIIIIII')
}
onMounted(() =>
  loadInventoryItems()
)


const submit = async () => {
  console.log(form.value, 'form')
  try {
    await store.createInventoryAdjustment(form.value)
    router.push({ name: 'InventoryAdjustment' })
  } catch (error) {
    console.error('Failed to create inventory adjustment:', error)
  }
}
</script>
