<template>
  <layout-authenticated>
    <SectionMain>
      <form @submit.prevent="submitForm">
        <FormField label="Name">
          <FormControl v-model="workOrder.standard_batch_variety_id" :options="mappedStandardBatchVarieties"
            :error="errors.standard_batch_variety_id" @update:modelValue="handleStandardBatchVarietyChange" />
        </FormField>
        <FormField label="Quantity">
          <FormControl v-model="workOrder.output_count" :error="errors.output_count" />
        </FormField>

        <FormField label="Variety Factor - Triple click on value to edit">
          <FormControl v-model="workOrder.variety_factor" :error="errors.variety_factor"
            :readonly="!isVarietyFactorEditable" @click="handleVarietyFactorClick" />
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
import { useStandardBatchVarietyStore } from '@/stores/standardBatchVariety'
import { useWorkOrderStore } from '@/stores/workOrder'
import { storeToRefs } from 'pinia'

const router = useRouter()
const route = useRoute()
const standardBatchVarietyStore = useStandardBatchVarietyStore()
const holderId = computed(() => route.params.id)
const isEditing = computed(() => !!holderId.value)

const { data: standardBatchVarieties } = storeToRefs(standardBatchVarietyStore)

const mappedStandardBatchVarieties = computed(() => {
  console.log(standardBatchVarieties.value, 'STANDARD BATCH VARIETIES')

  return standardBatchVarieties.value.map(variety => ({
    id: variety.id,
    name: `${variety.name} - ${variety.recipe.name} - ${variety.recipe.product.name}`
  }))
})

console.log(mappedStandardBatchVarieties, 'MAPPED STANDARD BATCH VARIETIES')
const clickCount = ref(0)
const clickTimer = ref(null)

const handleVarietyFactorClick = () => {
  clickCount.value++
  if (clickCount.value === 1) {
    clickTimer.value = setTimeout(() => {
      clickCount.value = 0
    }, 400)
  } else if (clickCount.value === 3) {

    clearTimeout(clickTimer.value)
    clickCount.value = 0
    isVarietyFactorEditable.value = true

  }
}

onMounted(() => {
  useStandardBatchVarietyStore.getStandardBatchVariety()
})

const workOrder = reactive({
  standard_batch_variety_id: null, // Change to null instead of empty string
  output_count: '',
  variety_factor: 1
})

const isVarietyFactorEditable = ref(false)


const enableVarietyFactorEdit = () => {
  isVarietyFactorEditable.value = true
}

const errors = reactive({
  standard_batch_variety_id: "",
  output_count: "",
  variety_factor: ""
})

const isSubmitting = ref(false)

const validateForm = () => {
  let isValid = true
  errors.standard_batch_variety_id = workOrder.standard_batch_variety_id ? '' : 'Standard Batch Variety is required'
  errors.output_count = workOrder.output_count.trim() ? '' : 'Quantity is required'

  if (errors.standard_batch_variety_id || errors.output_count) {
    isValid = false
  }

  return isValid
}

const handleStandardBatchVarietyChange = (value) => {
  workOrder.standard_batch_variety_id = value
}

const submitForm = async (e) => {
  // if (!validateForm()) return
  isSubmitting.value = true

  console.log(workOrder, 'WORK ORDER')
  try {
    const response = await useWorkOrderStore().createWorkOrder(workOrder)
    router.push('/workOrders')
  } catch (error) {
    console.log(error, 'ERROR')
  } finally {
    isSubmitting.value = false
  }
}

</script>
