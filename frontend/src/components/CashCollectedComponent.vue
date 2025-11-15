<template>
  <form @submit.prevent="submitForm">
    <FormField label="Branch">
      <FormControl v-model="cashCollected.branch_id" :error="errors" :options="branches" />
    </FormField>
    <FormField label="Amount">
      <FormControl v-model="cashCollected.amount" :error="errors" />
    </FormField>

    <BaseButton type="submit" label="Submit" :loading="isSubmitting" />
  </form>
</template>

<script setup>

import FormControl from '@/components/form/FormControl.vue'
import FormField from '@/components/form/FormField.vue'
import SectionMain from '@/components/section/SectionMain.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import { useRoute, useRouter } from 'vue-router'
import { useBranchStore } from '@/stores/branches'
import { computed, onMounted, reactive, ref } from 'vue'
import { useCashCollectedStore } from '@/stores/cashCollected'

const router = useRouter()
const route = useRoute()
const branchStore = useBranchStore()
const branchId = computed(() => route.params.id)
const isEditing = computed(() => !!branchId.value)
const branches = computed(() => branchStore.branches)
const cashCollectedStore = useCashCollectedStore()

onMounted(() => {
  branchStore.getBranches()
})

const cashCollected = reactive({
  branch_id: "", amount: ""
})
const errors = reactive({ name: "", type: "" })
const isSubmitting = ref(false)

const validateForm = () => {
  let isValid = true
  errors.name = cashCollected.branch_id.trim() ? '' : 'Name is required'

  if (errors.name) {
    isValid = false
  }

  return isValid
}

const submitForm = async (e) => {
  // if (!validateForm()) return
  isSubmitting.value = true

  try {
    const response = await cashCollectedStore.createCashCollected(cashCollected)
    console.log(response, 'CASH COLLECTED RAMADAN')
    // if (response.status === 200) {
    router.push('/cashCollected')
    // }
  } catch (error) {
    console.log(error, 'ERROR CASH COLLECTED RAMADAN')
  } finally {
    isSubmitting.value = false
  }
}
</script>
<style scoped></style>
