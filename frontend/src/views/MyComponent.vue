<template>
  <ul>
    <li v-for="item in itemsList" :key="item.id">
      <slot name="customSlot" :itemData="item"></slot>
      <slot name="anotherSlot" :itemData="item"></slot>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    itemsList: {
      type: Array,
      required: true
    }
  }
};
</script>
