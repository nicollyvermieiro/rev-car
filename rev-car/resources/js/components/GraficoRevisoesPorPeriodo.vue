<template>
  <div>
    <h2>Marcas por Sexo do Proprietário</h2>
    <canvas v-if="chartData.datasets.length" id="graficoMarcasPorSexo"></canvas>
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
    const res = await fetch('http://localhost/api/relatorios/marcas-por-sexo')
    const data = await res.json()
    const labelsSet = new Set()
    const datasetsMap = {}

    data.forEach(i => {
      labelsSet.add(i.marca)
      if (!datasetsMap[i.sexo]) datasetsMap[i.sexo] = []
    })

    chartData.value.labels = Array.from(labelsSet)
    Object.keys(datasetsMap).forEach(sexo => {
      datasetsMap[sexo] = chartData.value.labels.map(label => {
        const item = data.find(d => d.marca === label && d.sexo === sexo)
        return item ? item.total : 0
      })
      chartData.value.datasets.push({
        label: sexo,
        data: datasetsMap[sexo],
        backgroundColor: sexo === 'M' ? '#36A2EB' : '#FF6384'
      })
    })

    chartInstance = new Chart(document.getElementById('graficoMarcasPorSexo'), {
      type: 'bar',
      data: chartData.value,
      options: { responsive: true, scales: { x: { stacked: true }, y: { stacked: true } } }
    })
  } catch (err) {
    console.error(err)
  }
})

onBeforeUnmount(() => { if (chartInstance) chartInstance.destroy() })
</script>
