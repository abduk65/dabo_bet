<template>
  <LayoutAuthenticated>
    <SectionMain>
      {{ initialData }}
      <Form v-if="initialData" :initial-values="initialData" @submit="onSubmit" :validation-schema="schema">
        <ValidatedFormControl name="inventory_item_id" :options="formattedInventoryOptions" type="select"
          option-label="name" />
        <ValidatedFormControl name="quantity" type="number" placeholder="Enter quantity" />
        <ValidatedFormControl name="unit_id" :options="formattedUnitOptions" type="select" option-label="name" />
        <ValidatedFormControl name="receiver_user_id" :options="formattedUserOptions" type="select"
          option-label="name" />
        <button type="submit" class="btn btn-primary">
          Update Daily Inventory Out
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
import { useDailyInventoryOut } from '@/stores/dailyInventoryOut';
import { useInventoryItemStore } from '@/stores/inventoryItem';
import { useUnitStore } from '@/stores/unit';
import { useAuthStore } from '@/stores/authentication';
import { useRouter } from 'vue-router';

const props = defineProps(['id']);
const dailyInventoryOutStore = useDailyInventoryOut();
const inventoryItemStore = useInventoryItemStore();
const unitStore = useUnitStore();
const userStore = useAuthStore();

const schema = Yup.object({
  inventory_item_id: Yup.mixed().required('Inventory item is required'),
  quantity: Yup.number().required('Quantity is required').min(0, 'Quantity must be positive'),
  unit_id: Yup.mixed().required('Unit is required'),
  receiver_user_id: Yup.mixed().required('Receiver user is required'),
});

onMounted(async () => {
  await Promise.all([
    dailyInventoryOutStore.getDailyInventoryOut(),
    inventoryItemStore.getInventoryItem(),
    unitStore.getUnits(),
    userStore.getUser()
  ]);
});

const initialData = computed(() => {
  const dailyInventoryOut = dailyInventoryOutStore.data.find(val => Number(val.id) === Number(props.id));
  if (dailyInventoryOut) {
    return {
      inventory_item_id: dailyInventoryOut.inventory_item?.id || dailyInventoryOut.inventory_item_id,
      quantity: dailyInventoryOut.quantity,
      unit_id: dailyInventoryOut.unit?.id || dailyInventoryOut.unit_id,
      receiver_user_id: dailyInventoryOut.receiver_user?.id || dailyInventoryOut.receiver_user_id
    };
  }
  return null;
});

// Format options for the inventory items select
const formattedInventoryOptions = computed(() =>
  inventoryItemStore.data.map(item => ({
    id: item.id,
    name: item.item_name
  }))
);

// Format options for the units select
const formattedUnitOptions = computed(() =>
  unitStore.data.map(unit => ({
    id: unit.id,
    name: unit.name
  }))
);

// Format options for the users select
const formattedUserOptions = computed(() =>
  userStore.data.map(user => ({
    id: user.id,
    name: user.name // Adjust this based on your user object structure
  }))
);

const router = useRouter()

const onSubmit = async (values) => {
  const updateData = {
    id: props.id,
    inventory_item_id: values.inventory_item_id,
    quantity: values.quantity,
    unit_id: values.unit_id,
    receiver_user_id: values.receiver_user_id
  };

  console.log('Submitting:', updateData);
  const response = await dailyInventoryOutStore.putData(updateData, 'dailyInventoryOut');
  console.log(response, 'RRRRRRR-R--------------R-R-R-R-')
  if (response.data.id) {
    router.push('/dailyInventoryOut')
  }
};
</script>
