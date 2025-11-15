<template>
  <layout-authenticated>
    <SectionMain>
      <AddCashCollectedComponent></AddCashCollectedComponent>
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
import { useBranchStore } from '@/stores/branches'
import { useCashCollectedStore } from '@/stores/cashCollected'
import AddCashCollectedComponent from '@/components/CashCollectedComponent.vue'

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
    // if (response.status === 200) {
    router.push('/cashCollected')
    // }
  } catch (error) {
    console.log(error, 'ERROR')
  } finally {
    isSubmitting.value = false
  }
}

</script>
