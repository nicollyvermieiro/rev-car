<template>
  <div>
    <h2>Pessoas com Mais Revisões</h2>
    <canvas id="graficoPessoasComMaisRevisoes"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js';

export default {
  async mounted() {
    const res = await fetch('/api/relatorios/pessoas-com-mais-revisoes');
    const data = await res.json();
    const labels = data.map(item => item.nome);
    const values = data.map(item => item.total_revisoes);

    new Chart(document.getElementById('graficoPessoasComMaisRevisoes'), {
      type: 'horizontalBar',
      data: {
        labels,
        datasets: [{
          label: 'Revisões',
          data: values,
          backgroundColor: '#FF6384'
        }]
      },
      options: { responsive: true }
    });
  }
}
</script>