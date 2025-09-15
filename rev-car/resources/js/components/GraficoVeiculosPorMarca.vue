<template>
  <div>
    <h2>Veículos por Marca</h2>
    <canvas id="graficoVeiculosPorMarca"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js/auto';


export default {
  async mounted() {
    const res = await fetch('/api/relatorios/marcas-por-quantidade');
    const data = await res.json();
    const labels = data.map(item => item.marca);
    const values = data.map(item => item.quantidade);

    new Chart(document.getElementById('graficoVeiculosPorMarca'), {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Quantidade de Veículos',
          data: values,
          backgroundColor: 'rgba(54, 162, 235, 0.6)'
        }]
      },
      options: { responsive: true }
    });
  }
}

onBeforeUnmount(() => {
  if (chartInstance) {
    chartInstance.destroy()
    chartInstance = null
  }
})
</script>