<template>
  <layout-authenticated>
    <SectionMain>
      <form @submit.prevent="submitForm">
        <FormField label="Name">
          <FormControl v-model="productType.name" :error="errors.name" />
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
import { useProductTypeStore } from '@/stores/productType'
import { useRouter, useRoute } from 'vue-router'
import { reactive, ref, computed } from 'vue'
import { storeToRefs } from 'pinia'

const router = useRouter()
const route = useRoute()
const productTypeStore = useProductTypeStore()
const productTypeId = computed(() => route.params.id)
const isEditing = computed(() => !!productTypeId.value)


const productType = reactive({
  name: ""
})

const errors = reactive({ name: "" })
const isSubmitting = ref(false)

const validateForm = () => {
  let isValid = true
  errors.name = productType.name.trim() ? '' : 'Name is required'

  if (errors.name) {
    isValid = false
  }

  return isValid
}

const submitForm = async (e) => {
  if (!validateForm()) return
  isSubmitting.value = true

  try {
    const response = await productTypeStore.createProductType(productType)
    // if (response.status === 200) {
    router.push('/productTypes')
    // }
  } catch (error) {
    console.log(error, 'ERROR')
  } finally {
    isSubmitting.value = false
  }
}

</script>
