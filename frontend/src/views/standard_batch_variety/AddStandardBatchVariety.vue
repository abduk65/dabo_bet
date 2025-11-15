<template>
  <layout-authenticated>
    <SectionMain>
      <form @submit.prevent="submitForm">
        <FormField label="Name">
          <FormControl v-model="standardBatchVariety.name" :error="errors.name" />
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
import { useStandardBatchVarietyStore } from '@/stores/standardBatchVariety'
import { useRouter, useRoute } from 'vue-router'
import { reactive, ref, computed } from 'vue'
import { storeToRefs } from 'pinia'

const router = useRouter()
const route = useRoute()
const standardBatchVarietyStore = useStandardBatchVarietyStore()
const { isLoading, error } = storeToRefs(standardBatchVarietyStore)
const standardBatchVarietyId = computed(() => route.params.id)
const isEditing = computed(() => !!standardBatchVarietyId.value)

const standardBatchVariety = reactive({
  name: "", type: ""
})

const errors = reactive({ name: "", type: "" })
const isSubmitting = ref(false)

const validateForm = () => {
  let isValid = true
  errors.name = standardBatchVariety.name.trim() ? '' : 'Name is required'

  if (errors.name) {
    isValid = false
  }

  return isValid
}

const submitForm = async (e) => {
  // if (!validateForm()) return
  isSubmitting.value = true

  try {
    const response = await standardBatchVarietyStore.createStandardBatchVariety(standardBatchVariety)
    // if (response.status === 200) {
    router.push('/StandardBatchVarietyes')
    // }
  } catch (error) {
    console.log(error, 'ERROR')
  } finally {
    isSubmitting.value = false
  }
}

</script>
