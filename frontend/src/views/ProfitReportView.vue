
<template>
  <LayoutAuthenticated>
    <SectionMain>
      <LineChart :data="chartData" :options="chartOptions"></LineChart>
         </SectionMain>
  </LayoutAuthenticated>
</template>
<script setup>

import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
import SectionMain from '@/components/section/SectionMain.vue'
import { computed, ref } from 'vue'
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import LineChart from '@/components/Charts/LineChart.vue'
import { profitReport } from '@/offlineData'
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const weeklyData = ref([
  { "week": "2024-W33", "total_sales": 5000, "total_production_cost": 3000, "profit": 2000 },
  { "week": "2024-W34", "total_sales": 6000, "total_production_cost": 3500, "profit": 2500 }
])

const lineData = {labels: ['week1', ], datasets: weeklyData.value }

const monthlyData = ref([
    { "month": "2024-07", "total_sales": 20000, "total_production_cost": 15000, "profit": 5000 }
  ]
)
console.time('weeklyData')
const labels = profitReport['dailySales'].map(data => data.created_at)
console.timeEnd('weeklyData')

// const startTime = performance.now();

const dataset = profitReport['dailySales'].map(data => {
  // console.log(data.quantity, data.adari, data.product.name, data.created_at, "sikenes")
 return (data.quantity - data.adari)
})

// const endTime = performance.now();
// const timeTaken = endTime - startTime;
// console.log(`Map Query Time: ${timeTaken} ms, ${endTime} ms End from ${startTime}ms`);

const largeArray = new Array(1000000).fill(1); // An array of a million elements

const startTime = performance.now();

const result = largeArray.map(item => {
  // Simulate more complex processing
  for (let i = 0; i < 100; i++) {
    item *= 1.0001;
  }
  return item;
});
const endTime = performance.now();
console.log(`Map Query Time: ${endTime - startTime} ms`);

const chartData = {
  labels: labels,
  datasets: [
    {
      data: dataset,
      label: 'Data One',
      backgroundColor: '#f87979',
    },
  ]
}


const chartOptions = {
  responsive: true
}

</script>
