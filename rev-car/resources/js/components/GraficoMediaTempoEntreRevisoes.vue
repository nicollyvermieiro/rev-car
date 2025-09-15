<template>
  <div>
    <h2>Média de Tempo entre Revisões</h2>
    <canvas v-if="chartData.datasets.length" id="graficoMediaTempoEntreRevisoes"></canvas>
    <div v-else class="alert alert-info">Carregando...</div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import Chart from 'chart.js/auto'

const chartData = ref({ labels: [], datasets: [] })
let chartInstance = null

onMounted(async () => {
  try {
    const res = await fetch('http://localhost/api/relatorios/media-tempo-entre-revisoes')
    const data = await res.json()
    chartData.value.labels = data.map(i => i.nome)
    chartData.value.datasets = [{
      label: 'Dias',
      data: data.map(i => parseFloat(i.media_dias)),
      backgroundColor: 'rgba(54, 162, 235, 0.6)'
    }]
    chartInstance = new Chart(document.getElementById('graficoMediaTempoEntreRevisoes'), {
      type: 'bar',
      data: chartData.value,
      options: { responsive: true }
    })
  } catch (err) {
    console.error(err)
  }
})

onBeforeUnmount(() => { if (chartInstance) chartInstance.destroy() })
</script>
