<template>
  <layout-authenticated>
    <SectionMain>
      <form @submit.prevent="submitForm">
        <FormField label="Name">
          <FormControl v-model="unit.name" :error="errors.name" />
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
import { useUnitStore } from '@/stores/unit'
import { useRouter, useRoute } from 'vue-router'
import { reactive, ref, computed } from 'vue'
import { storeToRefs } from 'pinia'

const router = useRouter()
const route = useRoute()
const unitStore = useUnitStore()

const { error, data } = storeToRefs(unitStore)

// const unitId = computed(() => route.params.id)
// const isEditing = computed(() => !!unitId.value)

const unit = reactive({
  name: ""
})

const errors = reactive({ name: "" })
const isSubmitting = ref(false)

const validateForm = () => {
  let isValid = true
  errors.name = unit.name.trim() ? '' : 'Name is required'

  if (errors.name) {
    isValid = false
  }

  return isValid
}

const submitForm = async (e) => {
  // if (!validateForm()) return
  isSubmitting.value = true

  try {
    const response = await unitStore.createUnit(unit)
    // if (response.status === 200) {
    router.push('/unit')
    // }
  } catch (error) {
    console.log(error, 'ERROR')
  } finally {
    isSubmitting.value = false
  }
}

</script>
