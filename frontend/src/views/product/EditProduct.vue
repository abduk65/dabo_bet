<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Edit Product">
        <BaseButton to="/products" label="Back" />
      </SectionTitleLineWithButton>

      <CardBox class="mb-6" has-table>
        <Form v-if="initialData" :initial-values="initialData" @submit="onSubmit" :validation-schema="schema">
          <ValidatedFormControl name="name" label="Name" />
          <ValidatedFormControl name="unit_price" type="number" label="Unit Price" />
          <ValidatedFormControl
            name="type"
            label="Type"
            :options="[
              { id: 'Bread', name: 'Bread' },
              { id: 'Cake', name: 'Cake' },
              { id: 'Others', name: 'Others' }
            ]"
            type="select"
            option-label="name"
            option-value="id"
          />
          <BaseButton type="submit" label="Update Product" />
        </Form>
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<script setup>
import { Form } from 'vee-validate';
import * as Yup from 'yup';
import { computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue';
import SectionMain from '@/components/section/SectionMain.vue';
import ValidatedFormControl from '@/components/ValidatedFormControl.vue';
import CardBox from '@/components/cardbox/CardBox.vue';
import BaseButton from '@/components/base/BaseButton.vue';
import SectionTitleLineWithButton from '@/components/section/SectionTitleLineWithButton.vue';
import { mdiTableBorder } from '@mdi/js';
import { useProductsStore } from '@/stores/products';

const router = useRouter();
const route = useRoute();
const productsStore = useProductsStore();

const schema = Yup.object({
  name: Yup.string().required('Name is required'),
  unit_price: Yup.number().required('Unit price is required').min(0, 'Unit price must be positive'),
  type: Yup.string().required('Type is required'),
});

const initialData = computed(() => {
  return productsStore.data.find(product => product.id == route.params.id);
});

onMounted(async () => {
  await productsStore.getProducts();
});

const onSubmit = async (values) => {
  try {
    await productsStore.updateProduct(values);
    router.push('/products');
  } catch (error) {
    console.error('Failed to update product:', error);
  }
};
</script>
