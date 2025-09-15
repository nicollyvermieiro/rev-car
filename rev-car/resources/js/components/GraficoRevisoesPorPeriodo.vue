<template>
  <div>
    <h2>Revisões por Período</h2>
    <canvas id="graficoRevisoesPorPeriodo"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js';

export default {
  async mounted() {
    // Exemplo com período fixo, pode ser dinâmico
    const res = await fetch('/api/relatorios/revisoes-por-periodo?inicio=2024-01-01&fim=2024-12-31');
    const data = await res.json();
    // Agrupa por mês
    const meses = {};
    data.forEach(item => {
      const mes = item.data_revisao.slice(0,7); // 'YYYY-MM'
      meses[mes] = (meses[mes] || 0) + 1;
    });
    const labels = Object.keys(meses);
    const values = Object.values(meses);

    new Chart(document.getElementById('graficoRevisoesPorPeriodo'), {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Revisões',
          data: values,
          borderColor: '#36A2EB'
        }]
      },
      options: { responsive: true }
    });
  }
}
</script>