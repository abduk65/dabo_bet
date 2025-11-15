<template>
  <layout-authenticated>
    <SectionMain>
      <form @submit.prevent="submitForm">
        <FormField label="Commission Recipient">
          <FormControl v-model="commission.commission_recipient_id" :error="errors.commissionRecipient"
            :options="commissionRecipients" />
        </FormField>
        <FormField label="Product">
          <FormControl v-model="commission.product_id" :error="errors.product" :options="products" />
        </FormField>

        <FormField label="Discount Amount">
          <FormControl v-model="commission.discount_amount" :error="errors.discount" />
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
import { reactive, ref, computed, onMounted, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { useCommissionStore } from '@/stores/commissions'
import { useProductsStore } from '@/stores/products'
import { useCommissionRecipientStore } from '@/stores/CommissionRecipient'

const router = useRouter()
const route = useRoute()
const commissionStore = useCommissionStore()
const { isLoading, error } = storeToRefs(commissionStore)
const holderId = computed(() => route.params.id)
const isEditing = computed(() => !!holderId.value)

const commission = reactive({
  discount_amount: 0,
  product_id: 0,
  commission_recipient_id: 0
})

const errors = reactive({ discount: "", product_id: "", commission_recipient_id: "" })
const isSubmitting = ref(false)

const productStore = useProductsStore()
const commissionRecipientStore = useCommissionRecipientStore()

const { data: products } = storeToRefs(productStore)
const { data: commissionRecipients } = storeToRefs(commissionRecipientStore)


onMounted(async () => {
  await productStore.getProducts()
  console.log(products, "products")
  await commissionRecipientStore.getCommission()
  console.log(commissionRecipients, "recipe")
})

watch(products, (val, oldval, cleanup) => console.log(val, oldval, "fetty"))

const validateForm = () => {
  let isValid = true
  errors.discount = commission.discount.trim() ? '' : 'Discount is required'
  errors.product_id = commission.product_id.trim() ? '' : 'Product is required'
  errors.commission_recipient_id = commission.commission_recipient_id.trim() ? '' : 'Commission Recipient is required'

  if (errors.discount || errors.product_id || errors.commission_recipient_id) {
    isValid = false
  }

  return isValid
}

const submitForm = async (e) => {
  // if (!validateForm()) return
  isSubmitting.value = true
  console.log(commission, 'COMMISSION')

  try {
    const response = await commissionStore.createCommission(commission)
    // if (response.status === 200) {
    router.push('/commissions')
    // }
  } catch (error) {
    console.log(error, 'ERROR')
  } finally {
    isSubmitting.value = false
  }
}

</script>
