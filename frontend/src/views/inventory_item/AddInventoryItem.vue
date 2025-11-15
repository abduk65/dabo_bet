<template>
  <layout-authenticated>
    <SectionMain>
      <form @submit.prevent="submitForm">
        <FormField label="Name">
          <FormControl v-model="inventoryItem.item_name" :error="errors.name" />
        </FormField>
        <FormField label="Brand">
          <FormControl v-model="inventoryItem.brand_id" :options="brands" :error="errors.brand" />
        </FormField>
        <FormField label="Unit">
          <FormControl v-model="inventoryItem.unit_id" :options="units" :error="errors.unit" />
        </FormField>
        <FormField label="Price">
          <FormControl v-model="inventoryItem.price" :error="errors.price" />
        </FormField>
        <FormField label="Quantity">
          <FormControl v-model="inventoryItem.quantity" :error="errors.quantity" />
        </FormField>
        <BaseButton type="submit" label="Submit" :loading="isSubmitting" />
      </form>
    </SectionMain>
  </layout-authenticated>
</template>
<script setup>
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionMain from '@/components/section/SectionMain.vue'
import FormField from '@/components/form/FormField.vue'
import FormControl from '@/components/form/FormControl.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import { useInventoryItemStore } from '@/stores/inventoryItem'
import { useRouter, useRoute } from 'vue-router'
import { reactive, ref, computed, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useBrandStore } from '@/stores/brands'
import { useUnitStore } from '@/stores/unit'

const router = useRouter()
const route = useRoute()
const inventoryItemStore = useInventoryItemStore()
const inventoryItemId = computed(() => route.params.id)
const isEditing = computed(() => !!inventoryItemId.value)
const brandStore = useBrandStore()
const unitStore = useUnitStore()
const { data: unitData } = storeToRefs(unitStore)
const { data } = storeToRefs(brandStore)

onMounted(() => {
  brandStore.getBrands()
  unitStore.getUnits()
})

const brands = computed(() => data.value)
const units = computed(() => unitData.value)
const inventoryItem = reactive({
  item_name: "",
  brand_id: "",
  unit_id: "",
  price: "",
  quantity: ""
})

const errors = reactive({
  name: ""
})
const isSubmitting = ref(false)

const validateForm = () => {
  let isValid = true
  errors.name = inventoryItem.name.trim() ? '' : 'Name is required'

  if (errors.name) {
    isValid = false
  }

  return isValid
}

const submitForm = async (e) => {
  // if (!validateForm()) return
  isSubmitting.value = true
  console.log(inventoryItem, 'INVENTORY ITEM')

  try {
    const response = await inventoryItemStore.createInventoryItem(inventoryItem)
    console.log(response, 'RESPONSE TEMELSULA')
    if (response) {
      router.push('/inventoryItem')
    }
  } catch (error) {
    console.log(error, 'ERROR')
  } finally {
    isSubmitting.value = false
  }
}

</script>
