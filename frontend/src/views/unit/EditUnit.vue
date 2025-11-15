<template>
  <LayoutAuthenticated>
    <SectionMain>
      <Form v-if="initialData" :initial-values="initialData" @submit="onSubmit">
        <ValidatedFormControl name="name"></ValidatedFormControl>
        <button type="submit" class="btn btn-primary">Update Unit</button>
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
import { useUnitStore } from '@/stores/unit';

const props = defineProps(['id']);
const unitStore = useUnitStore();

const schema = Yup.object({
  commission_recipient_id: Yup.mixed().required('Commission recipient is required'),
  product_id: Yup.mixed().required('Product is required'),
  discount_amount: Yup.number().required('Discount amount is required').min(0, 'Discount must be positive'),
});

onMounted(async () => {
  console.log('Mounted ID:', props.id); // Debug log
  await Promise.all([
    unitStore.getUnits()
  ]);
});

const initialData = computed(() => {
  const unit = unitStore.data.find(val => Number(val.id) === Number(props.id));
  console.log('Found Unit:', unit); // Debug log
  if (unit) {
    return {
      name: unit.name
    };
  }
  return null;
});

const onSubmit = async (values) => {
  const updateData = {
    id: props.id,

  };

  console.log('Submitting form with data:', updateData);

};
</script>
