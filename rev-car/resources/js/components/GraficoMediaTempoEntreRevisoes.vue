<template>
  <div>
    <h2>Média de Tempo Entre Revisões por Pessoa</h2>
    <canvas id="graficoMediaTempoEntreRevisoes"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js';

export default {
  async mounted() {
    const res = await fetch('/api/relatorios/media-tempo-entre-revisoes');
    const data = await res.json();
    const labels = data.map(item => item.nome);
    const values = data.map(item => Number(item.media_dias));

    new Chart(document.getElementById('graficoMediaTempoEntreRevisoes'), {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Dias',
          data: values,
          backgroundColor: '#FFCE56'
        }]
      },
      options: { responsive: true }
    });
  }
}
</script>