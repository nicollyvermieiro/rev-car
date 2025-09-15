<template>
  <div>
    <h2>Pessoas com Mais Revisões</h2>
    <canvas v-if="chartData.datasets.length" id="graficoPessoasComMaisRevisoes"></canvas>
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
    const res = await fetch('http://localhost/api/relatorios/pessoas-com-mais-revisoes')
    const data = await res.json()
    chartData.value.labels = data.map(i => i.nome)
    chartData.value.datasets = [{
      label: 'Revisões',
      data: data.map(i => i.total_revisoes),
      backgroundColor: 'rgba(255, 159, 64, 0.6)'
    }]
    chartInstance = new Chart(document.getElementById('graficoPessoasComMaisRevisoes'), {
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
