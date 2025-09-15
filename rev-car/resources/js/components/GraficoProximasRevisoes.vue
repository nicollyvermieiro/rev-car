<template>
  <div>
    <h2>Próximas Revisões</h2>
    <canvas v-if="chartData.datasets.length" id="graficoProximasRevisoes"></canvas>
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
    const res = await fetch('http://localhost/api/relatorios/proximas-revisoes')
    const data = await res.json()
    chartData.value.labels = data.map(i => `${i.nome} - ${i.modelo}`)
    chartData.value.datasets = [{
      label: 'Próximas Revisões (dias)',
      data: data.map(i => new Date(i.proxima_revisao_aproximada).getTime() / (1000*60*60*24)), // converte para dias
      backgroundColor: 'rgba(255, 205, 86, 0.6)'
    }]
    chartInstance = new Chart(document.getElementById('graficoProximasRevisoes'), {
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
