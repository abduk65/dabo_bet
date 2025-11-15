<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Edit Product Type">
        <BaseButton to="/productType" label="Back" />
      </SectionTitleLineWithButton>

      <CardBox class="mb-6" has-table>
        <Form v-if="initialData" :initial-values="initialData" @submit="onSubmit" :validation-schema="schema">
          <ValidatedFormControl name="name" label="Name" />
          <BaseButton type="submit" label="Update Product Type" />
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
import { useProductTypeStore } from '@/stores/productType';

const router = useRouter();
const route = useRoute();
const productTypeStore = useProductTypeStore();

const schema = Yup.object({
  name: Yup.string().required('Name is required'),
});

const initialData = computed(() => {
  return productTypeStore.data.find(productType => productType.id == route.params.id);
});

onMounted(async () => {
  await productTypeStore.getProductType();
});

const onSubmit = async (values) => {
  try {
    await productTypeStore.updateProductType(values);
    router.push('/productType');
  } catch (error) {
    console.error('Failed to update product type:', error);
  }
};
</script>
