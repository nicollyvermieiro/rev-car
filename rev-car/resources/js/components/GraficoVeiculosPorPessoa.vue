<template>
  <div>
    <h2>Veículos por Pessoa</h2>

    <div v-if="loading" class="alert alert-info">Carregando...</div>
    <div v-else-if="labels.length === 0" class="alert alert-info">Nenhum dado disponível.</div>

    <canvas v-else ref="canvasVeiculosPorPessoa"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import Chart from 'chart.js/auto'

const canvasVeiculosPorPessoa = ref(null)
const labels = ref([])
const values = ref([])
const loading = ref(true)
let chartVeiculosPorPessoa = null

const fetchData = async () => {
  try {
    const res = await fetch('/api/relatorios/veiculos-por-pessoa')
    if (!res.ok) throw new Error(`HTTP ${res.status}`)

    const data = await res.json()
    labels.value = data.map(item => item.nome ?? `#${item.id}`)
    values.value = data.map(item =>
      Array.isArray(item.veiculos) ? item.veiculos.length : 0
    )
  } catch (err) {
    console.error('Erro ao carregar gráfico Veículos por Pessoa:', err)
  } finally {
    loading.value = false
  }
}

const createChart = () => {
  if (!canvasVeiculosPorPessoa.value || labels.value.length === 0) return

  if (chartVeiculosPorPessoa) chartVeiculosPorPessoa.destroy()

  chartVeiculosPorPessoa = new Chart(canvasVeiculosPorPessoa.value.getContext('2d'), {
    type: 'bar',
    data: {
      labels: labels.value,
      datasets: [{
        label: 'Qtd. Veículos',
        data: values.value,
        backgroundColor: labels.value.map(() => 'rgba(54,162,235,0.6)')
      }]
    },
    options: {
      responsive: true,
      indexAxis: 'y',
      scales: { x: { beginAtZero: true }, y: { beginAtZero: true } },
      plugins: { legend: { display: true } }
    }
  })
}

watch([labels, values], createChart)

onMounted(fetchData)
onBeforeUnmount(() => {
  if (chartVeiculosPorPessoa) {
    chartVeiculosPorPessoa.destroy()
    chartVeiculosPorPessoa = null
  }
})
</script>
