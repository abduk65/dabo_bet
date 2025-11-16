<template>
  <div class="revenue-chart">
    <div v-if="loading" class="flex items-center justify-center" :style="{ height: `${height}px` }">
      <ArrowPathIcon class="h-8 w-8 text-primary-600 animate-spin" />
    </div>

    <div v-else-if="!chartData || chartData.labels.length === 0" class="flex flex-col items-center justify-center text-gray-500" :style="{ height: `${height}px` }">
      <ChartBarIcon class="h-12 w-12 text-gray-400 mb-2" />
      <p class="text-sm">No data available for the selected period</p>
    </div>

    <Line
      v-else
      :data="chartData"
      :options="chartOptions"
      :height="height"
    />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler,
  type ChartOptions
} from 'chart.js'
import { ArrowPathIcon, ChartBarIcon } from '@heroicons/vue/24/outline'
import { formatCurrency, formatDate } from '@/utils/formatters'

// Register Chart.js components
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
)

interface RevenueDataPoint {
  date: string
  revenue: number
  target?: number
  orders?: number
}

interface Props {
  data: RevenueDataPoint[]
  loading?: boolean
  height?: number
  showTarget?: boolean
  showOrders?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  loading: false,
  height: 300,
  showTarget: true,
  showOrders: false
})

// Computed
const chartData = computed(() => {
  if (!props.data || props.data.length === 0) {
    return null
  }

  const labels = props.data.map(d => formatDate(new Date(d.date), 'short'))
  const revenueData = props.data.map(d => d.revenue)
  const targetData = props.data.map(d => d.target || 0)

  const datasets: any[] = [
    {
      label: 'Revenue',
      data: revenueData,
      borderColor: 'rgb(59, 130, 246)', // primary-500
      backgroundColor: 'rgba(59, 130, 246, 0.1)',
      fill: true,
      tension: 0.4,
      borderWidth: 2,
      pointRadius: 4,
      pointHoverRadius: 6,
      pointBackgroundColor: 'rgb(59, 130, 246)',
      pointBorderColor: '#fff',
      pointBorderWidth: 2
    }
  ]

  if (props.showTarget && targetData.some(t => t > 0)) {
    datasets.push({
      label: 'Target',
      data: targetData,
      borderColor: 'rgb(156, 163, 175)', // gray-400
      backgroundColor: 'transparent',
      borderDash: [5, 5],
      borderWidth: 2,
      pointRadius: 0,
      tension: 0.4
    })
  }

  return {
    labels,
    datasets
  }
})

const chartOptions = computed<ChartOptions<'line'>>(() => ({
  responsive: true,
  maintainAspectRatio: false,
  interaction: {
    mode: 'index',
    intersect: false
  },
  plugins: {
    legend: {
      display: true,
      position: 'top',
      align: 'end',
      labels: {
        usePointStyle: true,
        padding: 15,
        font: {
          size: 12,
          family: 'Inter, system-ui, sans-serif'
        }
      }
    },
    tooltip: {
      backgroundColor: 'rgba(0, 0, 0, 0.8)',
      padding: 12,
      titleFont: {
        size: 13,
        weight: 'bold'
      },
      bodyFont: {
        size: 12
      },
      bodySpacing: 4,
      usePointStyle: true,
      callbacks: {
        label: (context) => {
          const label = context.dataset.label || ''
          const value = formatCurrency(context.parsed.y)
          return `${label}: ${value}`
        }
      }
    }
  },
  scales: {
    x: {
      grid: {
        display: false
      },
      ticks: {
        font: {
          size: 11
        },
        color: '#6b7280' // gray-500
      }
    },
    y: {
      beginAtZero: true,
      grid: {
        color: 'rgba(0, 0, 0, 0.05)'
      },
      ticks: {
        font: {
          size: 11
        },
        color: '#6b7280', // gray-500
        callback: function(value) {
          return formatCurrency(Number(value), true)
        }
      }
    }
  }
}))
</script>

<style scoped>
.revenue-chart {
  position: relative;
}

/* Ensure chart is responsive */
canvas {
  max-width: 100%;
}
</style>
