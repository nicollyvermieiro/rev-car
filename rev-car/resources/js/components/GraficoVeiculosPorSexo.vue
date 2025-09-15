<template>
  <div>
    <h2>Veículos por Sexo do Proprietário</h2>
    <canvas v-if="chartData.datasets.length" id="graficoVeiculosPorSexo"></canvas>
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
    const res = await fetch('http://localhost/api/relatorios/veiculos-por-sexo')
    const data = await res.json()
    chartData.value.labels = data.map(i => i.sexo)
    chartData.value.datasets = [{
      label: 'Veículos',
      data: data.map(i => i.total),
      backgroundColor: ['#36A2EB','#FF6384']
    }]
    chartInstance = new Chart(document.getElementById('graficoVeiculosPorSexo'), {
      type: 'pie',
      data: chartData.value,
      options: { responsive: true }
    })
  } catch (err) {
    console.error(err)
  }
})

onBeforeUnmount(() => { if (chartInstance) chartInstance.destroy() })
</script>
