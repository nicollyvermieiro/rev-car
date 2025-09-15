<template>
  <div>
    <h2>Marcas com Mais Revisões</h2>
    <canvas id="graficoMarcasComMaisRevisoes"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js/auto';


export default {
  async mounted() {
    const res = await fetch('/api/relatorios/marcas-com-mais-revisoes');
    const data = await res.json();
    const labels = data.map(item => item.marca);
    const values = data.map(item => item.total_revisoes);

    new Chart(document.getElementById('graficoMarcasComMaisRevisoes'), {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Revisões',
          data: values,
          backgroundColor: '#36A2EB'
        }]
      },
      options: { responsive: true }
    });
  }
}
</script>