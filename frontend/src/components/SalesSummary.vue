<template>
  <div class="modal" v-if="isOpen">
    <div class="modal-content">
      <span class="close" @click="closeModal">&times;</span>
      <h2>Sales Data Summary</h2>
      <div class="tabs">
        <button
          v-for="branch in branches"
          :key="branch"
          :class="{ active: activeTab === branch }"
          @click="activeTab = branch"
        >
          {{ branch }}
        </button>
      </div>
      <div class="tab-content">
        <div v-if="filteredCompleteSalesData[activeTab]">
          <h3>{{ activeTab }}</h3>
          <p><strong>Cash Collected:</strong> {{ filteredCompleteSalesData[activeTab].cashCollected }}</p>
          <p><strong>Expenses:</strong> {{ filteredCompleteSalesData[activeTab].expenses }}</p>
          <h4>Products:</h4>
          <ul>
            <li v-for="(product, index) in filteredCompleteSalesData[activeTab].salesData" :key="index">
              <strong>{{ product.name }}</strong>:
              Price: {{ product.price }} | Quantity: {{ product.quantity }} |
              Carryover: {{ product.carryover }} |
              Commission Quantity CSV: {{ product.commissionQuantityCSV }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, isReactive, ref } from 'vue'

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
  completeSalesData: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['close']);
console.log(isReactive(props.completeSalesData), 'ISrEACTIVE');
const filterNumericKeys = (obj)=>Object.fromEntries(Object.entries(obj).filter((key) => !isNaN(Number(key[0]))))

const filteredCompleteSalesData = computed(()=> {
  console.log(isReactive(props.completeSalesData), 'ISRRReEACTIVE');
  return filterNumericKeys(props.completeSalesData)
})

const activeTab = ref(); // Initialize with the first branch

const branches = computed(() => Object.keys(filteredCompleteSalesData.value)); // Get branch names

const closeModal = () => {
  emit('close'); // Emit event to close modal
};
</script>

<style scoped>
.modal {
  display: flex;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.5);
}
.modal-content {
  background-color: black;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}
.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
.tabs {
  display: flex;
  margin-bottom: 20px;
}
.tabs button {
  margin-right: 10px;
  padding: 10px;
}
.tabs button.active {
  background-color: #007bff;
  color: white;
}
.tab-content {
  margin-top: 10px;
}
</style>
