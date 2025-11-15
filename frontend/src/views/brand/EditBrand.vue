<template>
  <LayoutAuthenticated>
    <SectionMain>
      <Form v-if="initialData" :initial-values="initialData" @submit="onSubmit" :validation-schema="schema">
        <ValidatedFormControl name="name" placeholder="Enter brand name" />
        <ValidatedFormControl name="product_type_id" :options="formattedSelectOptions" type="select"
          option-label="name" />

        <button type="submit" class="btn btn-primary">
          Update Brand
        </button>
      </Form>
      {{ initialData }}
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
import { useProductTypeStore } from '@/stores/productType';
import { useBrandStore } from '@/stores/brands';
import { useRouter } from 'vue-router';

const props = defineProps(['id']);
const productTypeStore = useProductTypeStore();
const brandStore = useBrandStore();

const schema = Yup.object({
  name: Yup.string().required('Brand name is required'),
  product_type_id: Yup.mixed().required('Product type is required'),
});

onMounted(async () => {
  await Promise.all([
    brandStore.getBrands(),
    productTypeStore.getProductType()
  ]);
});

const initialData = computed(() => {

  const brand = brandStore.data.find(val => Number(val.id) === Number(props.id));
  console.log('BRAND, ', brand)
  if (brand) {
    return {
      name: brand.name,
      product_type_id: brand.product_type?.id || brand.product_type_id || brand.product_type
    };
  }
  return null;
});

// Format options for the select
const formattedSelectOptions = computed(() =>
  productTypeStore.data.map(type => ({
    id: type.id,
    name: type.name
  }))
);

const router = useRouter()
const onSubmit = async (values) => {
  const updateData = {
    id: props.id,
    name: values.name,
    product_type_id: values.product_type_id
  };

  console.log('Submitting:', updateData);
  const response = await brandStore.putData(updateData, 'brands');
  console.log(response)
  if (response.data.id) {
    // Update the store data with the response
    const updatedBrand = response.data;
    const brandIndex = brandStore.data.findIndex(b => b.id === updatedBrand.id);
    if (brandIndex !== -1) {
      brandStore.data[brandIndex] = updatedBrand;
    }
    router.push('/brands');
  }
};
</script>

<style scoped></style>
