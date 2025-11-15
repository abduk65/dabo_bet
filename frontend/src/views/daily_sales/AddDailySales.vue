<template>
    <LayoutAuthenticated>
      <SectionMain>
        <CardBox class="mb-6" has-table>
          <div class="tabs">
            <button v-for="branch in reversedBranches" :key="branch.id"
              :class="{ active: activeBranch === branch.id }" @click="branchStore.switchBranch(branch.id || 0)">
              {{ branch.name }}
            </button>
          </div>
            <div v-if="activeBranch && branchData" class="branch-content" :style="{ background: bgBranch }">
              <h2 class="px-4 py-4 font-bold ">Currently Viewing: {{ activeBranch.name }}</h2>
              <h1 class="px-5 py-4 font-bold text-2xl">DAILY SALES</h1>
              <DailySalesComponent :branchData="branchData"></DailySalesComponent>
              <SectionTitleLineWithButton class="p-5" title="Expenses"></SectionTitleLineWithButton>
              <AddDailyExpenses :branchData="branchData" class="p-5"></AddDailyExpenses>
              <SectionTitleLineWithButton class="p-5" title="Cash Collected"></SectionTitleLineWithButton>
              <CashSnippet :branchData="branchData"></CashSnippet>
              <BaseButton label="SaveData" @click="submitSales" ></BaseButton>
            </div>
        </CardBox>
        <SalesSummary  :is-open="modalOpen" :completeSalesData="branchStore.completeSalesData" @close="toggleModal"></SalesSummary>
<!--        <div class="absolute">-->
<!--          <CardBoxModal v-model="modalOpen" title="Please confirm action" button-label="Confirm" has-cancel>-->
<!--            Sales summary-->
<!--              <table>-->
<!--                <thead>-->
<!--                  <tr>-->
<!--                    <th>PRODUCT</th>-->
<!--                    <th>Quant</th>-->
<!--                    <th>Adari</th>-->
<!--                    <th>Commission</th>-->
<!--                  </tr>-->
<!--                </thead>-->
<!--                <tbody>-->
<!--                  <tr><td></td></tr>-->
<!--                </tbody>-->

<!--                <thead>-->
<!--                <tr>-->
<!--                  <th>Expenses</th>-->
<!--                  <th>CashCollected</th>-->
<!--                  <th>Difference</th>-->
<!--                </tr>-->
<!--                </thead>-->
<!--              </table>-->
<!--          </CardBoxModal>-->
<!--        </div>-->
      </SectionMain>
    </LayoutAuthenticated>
</template>

<script setup>
import SectionMain from '@/components/section/SectionMain.vue'
import DailySalesComponent from '@/components/DailySalesComponent.vue'
import AddDailyExpenses from '@/components/DailyExpensesComponent.vue'
import CardBox from '@/components/cardbox/CardBox.vue'
import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/components/section/SectionTitleLineWithButton.vue'
import AddCashCollectedComponent from '@/components/CashCollectedComponent.vue'
import { storeToRefs } from 'pinia'
import { useBranchStore } from '@/stores/branches'
import { computed, ref, toRaw, watch } from 'vue'
import CashSnippet from '@/views/cash_collected/CashSnippet.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import CardBoxModal from '@/components/cardbox/CardBoxModal.vue'
import { useDailySalesStore } from '@/stores/dailySales'
import SalesSummary from '@/components/SalesSummary.vue'

const branchStore = useBranchStore()
const { activeBranch, branches, bgBranch} = storeToRefs(branchStore)
const branchData = ref()
const reversedBranches = computed(() => branches.value.slice().reverse())

watch(activeBranch,  (newValue, oldValue) => {
  if (newValue && newValue.id) {
    try {
      branchData.value = branchStore.getBranchData(newValue.id);
      console.log(branchStore.completeSalesData, 'KEMESHE MISERA SIRA')
    } catch (error) {
      console.error('Error fetching branch data:', error);
    }
  }
})

const modalOpen = ref(false)
const toggleModal = () =>{
  modalOpen.value = !modalOpen.value
}
const dailySalesStore = useDailySalesStore()
const submitSales = () => {
  const raw = JSON.stringify(branchStore.completeSalesData)
    // console.log(raw, branchStore.completeSalesData, JSON.stringify(branchStore.completeSalesData), 'TO RAW FOR THE COMPLETE SALES DATA ')
  dailySalesStore.createDailySales(raw)
  toggleModal()
}

</script>

<style scoped>
.tabs button {
  padding: 10px;
  cursor: pointer;
}

.tabs button.active {
  font-weight: bold;
  color: blue;
}

.branch-content {
  margin-top: 20px;
}

.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
