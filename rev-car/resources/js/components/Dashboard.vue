<template>
  <div>
    <h1>Dashboard de Relatórios</h1>

    <div class="mb-4" v-for="grafico in graficos" :key="grafico.title">
      <ChartBase
        :title="grafico.title"
        :chartData="grafico.data"
        :type="grafico.type"
        :options="grafico.options"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ChartBase from '@/components/ChartBase.vue'

const graficos = ref([
  {
    title: 'Veículos por Marca',
    apiUrl: '/api/relatorios/marcas-por-quantidade',
    type: 'bar',
    data: null
  },
  {
    title: 'Veículos por Sexo do Proprietário',
    apiUrl: '/api/relatorios/veiculos-por-sexo',
    type: 'pie',
    data: null
  },
  {
    title: 'Veículos por Pessoa',
    apiUrl: '/api/relatorios/veiculos-por-pessoa',
    type: 'bar',
    data: null
  },
  {
    title: 'Marcas por Sexo do Proprietário',
    apiUrl: '/api/relatorios/marcas-por-sexo',
    type: 'bar',
    options: { responsive: true, scales: { x: { stacked: true }, y: { stacked: true } } },
    data: null
  },
  {
    title: 'Marcas com Mais Revisões',
    apiUrl: '/api/relatorios/marcas-com-mais-revisoes',
    type: 'bar',
    data: null
  },
  {
    title: 'Pessoas com Mais Revisões',
    apiUrl: '/api/relatorios/pessoas-com-mais-revisoes',
    type: 'bar',
    data: null
  },
  {
    title: 'Média de Tempo entre Revisões',
    apiUrl: '/api/relatorios/media-tempo-entre-revisoes',
    type: 'bar',
    data: null
  },
  {
    title: 'Próximas Revisões',
    apiUrl: '/api/relatorios/proximas-revisoes',
    type: 'bar',
    data: null
  },
  {
    title: 'Revisões por Período',
    apiUrl: '/api/relatorios/revisoes-por-periodo',
    type: 'line',
    data: null
  }
])

function formatData(title, type, rawData) {
  if (!rawData) return { labels: [], datasets: [] }

  switch (title) {
    case 'Veículos por Marca':
    case 'Veículos por Pessoa':
    case 'Marcas com Mais Revisões':
    case 'Pessoas com Mais Revisões':
    case 'Média de Tempo entre Revisões':
    case 'Próximas Revisões':
      return {
        labels: rawData.map(item => item.nome || item.marca || `${item.nome} - ${item.modelo}`),
        datasets: [
          {
            label: Object.keys(rawData[0]).includes('quantidade') ? 'Quantidade' :
                   Object.keys(rawData[0]).includes('total_revisoes') ? 'Revisões' :
                   Object.keys(rawData[0]).includes('proxima_revisao_aproximada') ? 'Próximas Revisões' : 'Valor',
            data: rawData.map(item => item.quantidade ?? item.total_revisoes ?? item.proxima_revisao_aproximada ?? item.media_dias ?? 0)
          }
        ]
      }

    case 'Veículos por Sexo do Proprietário':
      return {
        labels: rawData.map(item => item.sexo),
        datasets: [
          { label: 'Veículos', data: rawData.map(item => item.total ?? item.quantidade ?? 0) }
        ]
      }

    case 'Marcas por Sexo do Proprietário':
      return {
        labels: rawData.labels ?? [],
        datasets: rawData.datasets ?? []
      }

    case 'Revisões por Período':
      return {
        labels: rawData.labels ?? [],
        datasets: rawData.datasets ?? []
      }

    default:
      return { labels: [], datasets: [] }
  }
}

onMounted(async () => {
  for (let grafico of graficos.value) {
    try {
      const res = await fetch(grafico.apiUrl)
      const rawData = await res.json()
      grafico.data = formatData(grafico.title, grafico.type, rawData)
    } catch (err) {
      console.error(`Erro ao carregar ${grafico.title}:`, err)
      grafico.data = { labels: [], datasets: [] }
    }
  }
})
</script>
