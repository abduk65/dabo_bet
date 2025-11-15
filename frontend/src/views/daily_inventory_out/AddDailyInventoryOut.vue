<template>
  <layout-authenticated>
    <SectionMain>
      <form @submit.prevent="submitForm">
        <FormField label="Inventory Item">
          <FormControl v-model="dailyInventoryOut.inventory_item_id" :options="inventoryItems" :label="'name'"
            :error="errors.inventory_item_id" />
        </FormField>
        <FormField label="Quantity">
          <FormControl v-model="dailyInventoryOut.quantity" />
        </FormField>
        <FormField label="Unit">
          <FormControl v-model="dailyInventoryOut.unit_id" :options="units" />
        </FormField>
        <FormField label="Receiver User">
          <FormControl v-model="dailyInventoryOut.receiver_user_id" :options="users" />
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
import { useRouter, useRoute } from 'vue-router'
import { reactive, ref, computed, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useDailyInventoryOut } from '@/stores/dailyInventoryOut'
import { useInventoryItemStore } from '@/stores/inventoryItem'
import { useAuthStore } from '@/stores/authentication'
import { useUnitStore } from '@/stores/unit'
const router = useRouter()
const route = useRoute()
const dailyInventoryOutId = computed(() => route.params.id)
const isEditing = computed(() => dailyInventoryOutId.value ? true : false)

const dailyInventoryOutStore = useDailyInventoryOut()

const dailyInventoryOut = reactive({
  user_id: "", receiver_user_id: "", quantity: "", inventory_item_id: "", unit_id: ""
})

const errors = reactive({ user_id: "", product_id: "", receiver_user_id: "", quantity: "", inventory_item_id: "" })
const isSubmitting = ref(false)

const auth = useAuthStore()
const { data: users } = storeToRefs(auth)
const inventoryItemStore = useInventoryItemStore()
const { data: inventoryItems } = storeToRefs(inventoryItemStore)

const unitStore = useUnitStore()
const { data: units } = storeToRefs(unitStore)

onMounted(async () => {

  auth.getUser()
  inventoryItemStore.getInventoryItem()
  unitStore.getUnits()

  // console.log(users.value, 'user')
  console.log(inventoryItems, 'inventory')
})


const validateForm = () => {
  let isValid = true
  errors.user_id = dailyInventoryOut.user_id.trim() ? '' : 'User is required'
  errors.product_id = dailyInventoryOut.product_id.trim() ? '' : 'Product is required'
  errors.receiver_user_id = dailyInventoryOut.receiver_user_id.trim() ? '' : 'Receiver User is required'
  errors.quantity = dailyInventoryOut.quantity.trim() ? '' : 'Quantity is required'
  errors.inventory_item_id = dailyInventoryOut.inventory_item_id.trim() ? '' : 'Inventory Item is required'

  if (errors.user_id || errors.product_id || errors.receiver_user_id || errors.quantity || errors.inventory_item_id) {
    isValid = false
  }

  return isValid
}

const submitForm = async (e) => {
  // if (!validateForm()) return
  isSubmitting.value = true
  console.log(dailyInventoryOut, 'daily')
  try {
    const response = await dailyInventoryOutStore.createDailyInventoryOut(dailyInventoryOut)
    // if (response.status === 200) {
    router.push('/dailyInventoryOut')
    // }
  } catch (error) {
    console.log(error, 'ERROR')
  } finally {
    isSubmitting.value = false
  }
}

</script>
