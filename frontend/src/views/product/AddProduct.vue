<template>
  <layout-authenticated>
    <SectionMain>
      <form @submit.prevent="submitForm">
        <FormField label="Name">
          <FormControl v-model="product.name" :error="errors.name" />
        </FormField>
        <FormField label="Type">
          <FormControl v-model="product.type" :error="errors.type" :options="type" />
        </FormField>
        <FormField label="Unit Price">
          <FormControl v-model="product.unit_price" :error="errors.unit_price" />
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
import { useProductsStore } from '@/stores/products'
import { useRouter, useRoute } from 'vue-router'
import { reactive, ref, computed } from 'vue'
import { storeToRefs } from 'pinia'

const router = useRouter()
const route = useRoute()
const productStore = useProductsStore()
const { isLoading, error } = storeToRefs(productStore)
const productId = computed(() => route.params.id)
const isEditing = computed(() => !!productId.value)

const product = reactive({
  name: "",
  type: "",
  unit_price: "",
})

const type = ref([{id:"Bread",  name: 'Bread' }, {id:"Cake",   name: 'Cake' }, {id:"Others",     name: 'Others' }])


const errors = reactive({ name: "", type: "" })
const isSubmitting = ref(false)

const validateForm = () => {
  let isValid = true
  errors.name = product.name.trim() ? '' : 'Name is required'

  if (errors.name) {
    isValid = false
  }

  return isValid
}

const submitForm = async (e) => {
  // if (!validateForm()) return
  isSubmitting.value = true
  console.log(product, 'PRODUCT')
  try {
    const response = await productStore.createProduct(product)
    // if (response.status === 200) {
    router.push('/products')
    // }
  } catch (error) {
    console.log(error, 'ERROR')
  } finally {
    isSubmitting.value = false
  }
}

</script>
