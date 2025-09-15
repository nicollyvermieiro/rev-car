<template>
  <div>
    <h2>Veículos por Pessoa</h2>
    <canvas id="graficoVeiculosPorPessoa"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js';

export default {
  async mounted() {
    const res = await fetch('/api/relatorios/veiculos-por-pessoa');
    const data = await res.json();
    const labels = data.map(item => item.nome);
    const values = data.map(item => item.veiculos.length);

    new Chart(document.getElementById('graficoVeiculosPorPessoa'), {
      type: 'horizontalBar',
      data: {
        labels,
        datasets: [{
          label: 'Qtd. Veículos',
          data: values,
          backgroundColor: 'rgba(255, 99, 132, 0.5)'
        }]
      },
      options: { responsive: true }
    });
  }
}
</script>