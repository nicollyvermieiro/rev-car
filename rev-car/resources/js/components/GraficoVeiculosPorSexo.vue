<template>
  <div>
    <h2>Veículos por Sexo</h2>
    <div v-if="loading" class="alert alert-info">Carregando...</div>
    <div v-else-if="labels.length === 0" class="alert alert-info">Nenhum dado disponível.</div>
    <canvas v-else ref="canvasVeiculosPorSexo"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import Chart from 'chart.js/auto'

const canvasVeiculosPorSexo = ref(null)
const labels = ref([])
const values = ref([])
const loading = ref(true)
let chartVeiculosPorSexo = null

const fetchData = async () => {
  try {
    const res = await fetch('/api/relatorios/veiculos-por-sexo')
    if (!res.ok) throw new Error(`HTTP ${res.status}`)
    const data = await res.json()

    labels.value = data.map(item => item.sexo === 'M' ? 'Homens' : 'Mulheres')
    values.value = data.map(item => item.total)
  } catch (err) {
    console.error('Erro ao carregar gráfico Veículos por Sexo:', err)
  } finally {
    loading.value = false
  }
}

const createChart = () => {
  if (!canvasVeiculosPorSexo.value || labels.value.length === 0) return

  if (chartVeiculosPorSexo) chartVeiculosPorSexo.destroy()

  chartVeiculosPorSexo = new Chart(canvasVeiculosPorSexo.value.getContext('2d'), {
    type: 'pie',
    data: {
      labels: labels.value,
      datasets: [{
        label: 'Total de Veículos',
        data: values.value,
        backgroundColor: ['#36A2EB', '#FF6384']
      }]
    },
    options: { responsive: true }
  })
}

watch([labels, values], createChart)

onMounted(fetchData)
onBeforeUnmount(() => {
  if (chartVeiculosPorSexo) {
    chartVeiculosPorSexo.destroy()
    chartVeiculosPorSexo = null
  }
})
</script>
