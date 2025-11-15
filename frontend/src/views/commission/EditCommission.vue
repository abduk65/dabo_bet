<template>
  <LayoutAuthenticated>
    <SectionMain>
      <Form v-if="initialData" :initial-values="initialData" @submit="onSubmit" :validation-schema="schema">
        <ValidatedFormControl name="commission_recipient_id" :options="formattedRecipientOptions" type="select"
          option-label="name" />
        <ValidatedFormControl name="product_id" :options="formattedProductOptions" type="select" option-label="name" />
        <ValidatedFormControl name="discount_amount" type="number" placeholder="Enter discount amount" />

        <button type="submit" class="btn btn-primary">
          Update Commission
        </button>
      </Form>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { Form } from 'vee-validate';
import * as Yup from 'yup';
import { computed, onMounted } from 'vue';
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue';
import SectionMain from '@/components/section/SectionMain.vue';
import ValidatedFormControl from '@/components/ValidatedFormControl.vue';
import { useProductsStore } from '@/stores/products';
import { useCommissionStore } from '@/stores/commissions';
import { useCommissionRecipientStore } from '@/stores/CommissionRecipient';
import { useRouter } from 'vue-router';

const props = defineProps(['id']);
const productStore = useProductsStore();
const commissionStore = useCommissionStore();
const commissionRecipientStore = useCommissionRecipientStore();

const schema = Yup.object({
  commission_recipient_id: Yup.mixed().required('Commission recipient is required'),
  product_id: Yup.mixed().required('Product is required'),
  discount_amount: Yup.number().required('Discount amount is required').min(0, 'Discount must be positive'),
});

onMounted(async () => {
  console.log('Mounted ID:', props.id); // Debug log
  await Promise.all([
    commissionStore.getCommission(),
    commissionRecipientStore.getCommission()
  ]);
  productStore.getProducts()
});

const initialData = computed(() => {
  const commission = commissionStore.data.find(val => Number(val.id) === Number(props.id));
  console.log('Found commission:', commission); // Debug log
  if (commission) {
    return {
      commission_recipient_id: commission.commission_recipient_id,
      product_id: commission.product?.id || commission.product_id, // Handle both nested and flat structure
      discount_amount: commission.discount_amount
    };
  }
  return null;
});

const formattedProductOptions = computed(() => {
  console.log('Product store data:', productStore.data); // Debug log
  return productStore.data.map(product => ({
    id: product.id,
    name: `${product.name} ${product.brand?.name ? `- ${product.brand.name}` : ''}`
  }));
});

const formattedRecipientOptions = computed(() =>
  commissionRecipientStore.data.map(recipient => ({
    id: recipient.id,
    name: recipient.name
  }))
);

const router = useRouter()

const onSubmit = async (values) => {
  const updateData = {
    id: props.id,
    commission_recipient_id: values.commission_recipient_id,
    product_id: values.product_id,
    discount_amount: values.discount_amount
  };

  const response = await commissionStore.updateCommission(updateData)
  if (response.success == true) {
    return router.push('/commissions')
  }
  console.log('Submitting form with data:', updateData);

};
</script>
