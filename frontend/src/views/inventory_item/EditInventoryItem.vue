<template>
  <LayoutAuthenticated>
    <SectionMain>
      <Form v-if="initialData" :initial-values="initialData" :validation-schema="schema" @submit="onSubmit">
        <ValidatedFormControl name="item_name" placeholder="Enter item name" />
        <ValidatedFormControl name="brand_id" :options="formattedBrandOptions" type="select" option-label="name" />
        <ValidatedFormControl name="unit_id" :options="formattedUnitOptions" type="select" option-label="name" />
        <ValidatedFormControl name="price" type="number" placeholder="Enter price" />
        <ValidatedFormControl name="quantity" type="number" placeholder="Enter quantity" />
        <button type="submit" class="btn btn-primary">Update Inventory Item</button>
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
import { useInventoryItemStore } from '@/stores/inventoryItem';
import { useBrandStore } from '@/stores/brands';
import { useUnitStore } from '@/stores/unit';
import { useRouter } from 'vue-router';

const props = defineProps(['id']);
const inventoryItemStore = useInventoryItemStore();
const brandStore = useBrandStore();
const unitStore = useUnitStore();

const schema = Yup.object({
  item_name: Yup.string().required('Item name is required'),
  brand_id: Yup.mixed().required('Brand is required'),
  unit_id: Yup.mixed().required('Unit is required'),
  price: Yup.number().required('Price is required').min(0, 'Price must be positive'),
  quantity: Yup.number().required('Quantity is required').min(0, 'Quantity must be positive'),
});

onMounted(async () => {
  await Promise.all([
    inventoryItemStore.getInventoryItem(),
    brandStore.getBrands(),
    unitStore.getUnits()
  ]);
});

const initialData = computed(() => {
  const inventoryItem = inventoryItemStore.data.find(val => Number(val.id) === Number(props.id));
  if (inventoryItem) {
    return {
      item_name: inventoryItem.item_name,
      brand_id: inventoryItem.brand?.id || inventoryItem.brand_id,
      unit_id: inventoryItem.unit?.id || inventoryItem.unit_id,
      price: inventoryItem.price,
      quantity: inventoryItem.quantity
    };
  }
  return null;
});

// Format options for the brand select
const formattedBrandOptions = computed(() =>
  brandStore.data.map(brand => ({
    id: brand.id,
    name: brand.name
  }))
);

// Format options for the unit select
const formattedUnitOptions = computed(() =>
  unitStore.data.map(unit => ({
    id: unit.id,
    name: unit.name
  }))
);

const router = useRouter()
const onSubmit = async (values) => {
  const updateData = {
    id: props.id,
    item_name: values.item_name,
    brand_id: values.brand_id,
    unit_id: values.unit_id,
    price: values.price,
    quantity: values.quantity
  };

  console.log('Submitting form with data:', updateData);
  const response = await inventoryItemStore.putData(updateData, 'inventoryItem');
  if (response.id) {
    router.push({ name: 'InventoryItem' })
  }
};
</script>
