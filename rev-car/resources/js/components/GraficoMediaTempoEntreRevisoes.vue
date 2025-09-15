<template>
  <div>
    <h2>Média de Tempo Entre Revisões por Pessoa</h2>
    <canvas id="graficoMediaTempoEntreRevisoes"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js/auto';

let chartInstance = null

export default {
  async mounted() {
    const res = await fetch('/api/relatorios/media-tempo-entre-revisoes');
    if (!res.ok) {
      console.error("Erro no backend:", await res.text());
      return;
    }

    const data = await res.json();
    const labels = data.map(item => item.nome);
    const values = data.map(item => Number(item.media_dias));

    if (chartInstance) {
      chartInstance.destroy();
    }

    chartInstance = new Chart(document.getElementById('graficoMediaTempoEntreRevisoes'), {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Dias',
          data: values,
          backgroundColor: '#FFCE56'
        }]
      },
      options: { 
        responsive: true,
        scales: {
          x: { beginAtZero: true },
          y: { beginAtZero: true }
        }
      }
    });
  }
}
</script>
