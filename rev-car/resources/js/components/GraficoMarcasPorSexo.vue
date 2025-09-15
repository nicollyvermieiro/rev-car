<template>
  <div>
    <h2>Veículos por Marca e Sexo</h2>
    <canvas id="graficoMarcasPorSexo"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js/auto';


export default {
  async mounted() {
    const res = await fetch('/api/relatorios/marcas-por-sexo');
    const data = await res.json();
    const marcas = [...new Set(data.map(item => item.marca))];
    // Separando por sexo
    const homens = marcas.map(marca => {
      const obj = data.find(d => d.marca === marca && d.sexo === 'M');
      return obj ? obj.total : 0;
    });
    const mulheres = marcas.map(marca => {
      const obj = data.find(d => d.marca === marca && d.sexo === 'F');
      return obj ? obj.total : 0;
    });

    new Chart(document.getElementById('graficoMarcasPorSexo'), {
      type: 'bar',
      data: {
        labels: marcas,
        datasets: [
          { label: 'Homens', data: homens, backgroundColor: '#36A2EB' },
          { label: 'Mulheres', data: mulheres, backgroundColor: '#FF6384' }
        ]
      },
      options: { responsive: true }
    });
  }
}
</script>