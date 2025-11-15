<template>
  <div>
    <RouterLink :to="props.to">
      <BaseButton :label="props.label" color="brown"  ></BaseButton>
    </RouterLink>
  </div>
</template>
<script setup>
import { RouterLink } from 'vue-router'
import BaseButton from '@/components/base/BaseButton.vue'

const props = defineProps({
  to: { type: String, required: true },
  label: { type: String, required: true },
})

</script>
