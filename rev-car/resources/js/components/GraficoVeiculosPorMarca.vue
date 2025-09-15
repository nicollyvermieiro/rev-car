<template>
  <div>
    <h2>Veículos por Marca</h2>
    <canvas v-if="chartData.datasets.length" ref="chartCanvas"></canvas>
    <div v-else class="alert alert-info">Carregando...</div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import Chart from 'chart.js/auto'

const chartData = ref({ labels: [], datasets: [] })
const chartCanvas = ref(null)
let chartInstance = null

// Função genérica para fetch seguro
async function fetchJSON(url) {
  try {
    const res = await fetch(url)
    if (!res.ok) throw new Error(`Erro HTTP ${res.status}`)
    const data = await res.json()
    if (!Array.isArray(data)) {
      console.warn('Dados recebidos não são um array:', data)
      return []
    }
    return data
  } catch (err) {
    console.error('Erro ao buscar dados:', err)
    return []
  }
}

onMounted(async () => {
  const data = await fetchJSON('http://localhost/api/relatorios/marcas-por-quantidade')

  chartData.value.labels = data.map(i => i.marca || 'Sem Marca')
  chartData.value.datasets = [{
    label: 'Quantidade de Veículos',
    data: data.map(i => i.quantidade || 0),
    backgroundColor: 'rgba(54, 162, 235, 0.6)'
  }]

  // Destrói gráfico anterior antes de criar um novo
  if (chartInstance) chartInstance.destroy()
  if (chartCanvas.value) {
    chartInstance = new Chart(chartCanvas.value, {
      type: 'bar',
      data: chartData.value,
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top' },
          tooltip: { mode: 'index', intersect: false }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    })
  }
})

onBeforeUnmount(() => {
  if (chartInstance) chartInstance.destroy()
})
</script>
