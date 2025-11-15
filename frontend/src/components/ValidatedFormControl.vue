<script setup>
import { useField } from 'vee-validate';
import FormControl from './form/FormControl.vue';

const props = defineProps({
  name: {
    type: String,
    required: true
  },
  options: {
    type: Array,
    default: null
  },
  type: {
    type: String,
    default: 'text'
  },
  placeholder: {
    type: String,
    default: null
  },
  optionLabel: {
    type: String,
    default: 'name'
  }
});

const { value, errorMessage } = useField(props.name);
</script>

<template>
  <div>
    <FormControl
      v-model="value"
      v-bind="$props"
    />
    <span v-if="errorMessage" class="text-red-500 text-sm mt-1">
      {{ errorMessage }}
    </span>
  </div>
</template>
