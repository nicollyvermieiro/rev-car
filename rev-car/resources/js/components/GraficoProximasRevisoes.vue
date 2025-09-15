<template>
  <div>
    <h2>Próximas Revisões por Veículo</h2>
    <canvas id="graficoProximasRevisoes"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js';

export default {
  async mounted() {
    const res = await fetch('/api/relatorios/proximas-revisoes');
    const data = await res.json();
    const labels = data.map(item => `${item.nome} - ${item.modelo}`);
    const values = data.map(item => new Date(item.proxima_revisao_aproximada).getTime());

    // Exemplo: transformar datas em ordem crescente
    const sorted = labels.map((label, i) => ({
      label, value: values[i]
    })).sort((a, b) => a.value - b.value);

    new Chart(document.getElementById('graficoProximasRevisoes'), {
      type: 'line',
      data: {
        labels: sorted.map(item => item.label),
        datasets: [{
          label: 'Próxima Revisão',
          data: sorted.map(item => item.value),
          borderColor: '#36A2EB'
        }]
      },
      options: {
        responsive: true,
        scales: {
          xAxes: [{
            ticks: { autoSkip: false }
          }]
        }
      }
    });
  }
}
</script>