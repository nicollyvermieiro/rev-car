<template>
  <div>
    <h4 v-if="title">{{ title }}</h4>
    <canvas v-if="chartData && chartData.datasets.length" ref="canvas"></canvas>
    <div v-else class="alert alert-info">Carregando...</div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import Chart from 'chart.js/auto'

const props = defineProps({
  title: String,
  chartData: {
    type: Object,
    required: true
  },
  type: {
    type: String,
    default: 'bar' // bar, pie, line
  },
  options: Object
})

const canvas = ref(null)
let chartInstance = null

// --- Cores padrão ---
const defaultColors = [
  '#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF'
]

// --- Aplica cores aos datasets ---
function applyColors(data) {
  if (!data.datasets) return data

  const newDatasets = data.datasets.map((ds, index) => {
    if (!ds.backgroundColor) {
      if (props.type === 'pie' || props.type === 'doughnut') {
        ds.backgroundColor = ds.data.map((_, i) => defaultColors[i % defaultColors.length])
      } else {
        ds.backgroundColor = defaultColors[index % defaultColors.length]
      }
    }
    if (!ds.borderColor && props.type !== 'pie' && props.type !== 'doughnut') {
      ds.borderColor = ds.backgroundColor
      ds.borderWidth = 1
    }
    return ds
  })
  return { ...data, datasets: newDatasets }
}

// --- Renderiza gráfico ---
function renderChart() {
  if (!canvas.value || !props.chartData) return

  if (chartInstance) chartInstance.destroy()

  chartInstance = new Chart(canvas.value, {
    type: props.type,
    data: applyColors(props.chartData),
    options: props.options || {
      responsive: true,
      plugins: {
        legend: { display: true, position: 'top' },
        tooltip: { enabled: true, mode: 'nearest' }
      },
      scales: props.type === 'bar' || props.type === 'line' ? {
        x: { stacked: props.options?.scales?.x?.stacked || false },
        y: { stacked: props.options?.scales?.y?.stacked || false }
      } : {}
    }
  })
}

// --- Re-renderiza quando dados mudam ---
watch(() => props.chartData, renderChart, { deep: true })

onMounted(renderChart)
onBeforeUnmount(() => { if (chartInstance) chartInstance.destroy() })
</script>
