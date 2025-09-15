<template>
  <div>
    <h2>Veículos por Sexo</h2>
    <canvas id="graficoVeiculosPorSexo"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js';

export default {
  async mounted() {
    const res = await fetch('/api/relatorios/veiculos-por-sexo');
    const data = await res.json();
    const labels = data.map(item => item.sexo === 'M' ? 'Homens' : 'Mulheres');
    const values = data.map(item => item.total);

    new Chart(document.getElementById('graficoVeiculosPorSexo'), {
      type: 'pie',
      data: {
        labels,
        datasets: [{
          label: 'Total de Veículos',
          data: values,
          backgroundColor: ['#36A2EB', '#FF6384']
        }]
      },
      options: { responsive: true }
    });
  }
}
</script>