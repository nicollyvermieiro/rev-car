<template>
  <div>
    <h2>Relatórios</h2>

    <ChartBase
      v-for="grafico in graficos"
      :key="grafico.title"
      :title="grafico.title"
      :chartData="grafico.chartData"
      :type="grafico.type"
      :options="grafico.options"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import ChartBase from './ChartBase.vue'

// --- Estados brutos da API ---
const veiculosPorPessoa = ref([])
const veiculosPorSexo = ref([])
const marcasPorQuantidade = ref([])
const marcasPorSexo = ref({ labels: [], datasets: [] })
const pessoas = ref([])
const pessoasPorSexoIdadeMedia = ref([])
const revisoesPorPeriodo = ref({ labels: [], datasets: [] })
const marcasComMaisRevisoes = ref([])
const pessoasComMaisRevisoes = ref([])
const mediaTempoEntreRevisoes = ref([])
const proximasRevisoes = ref([])

// --- Função genérica para fetch ---
async function fetchData(url, target) {
  try {
    const res = await fetch(url)
    if (!res.ok) throw new Error(`Erro HTTP ${res.status}`)
    target.value = await res.json()
  } catch (err) {
    console.error(`Erro ao buscar ${url}:`, err)
  }
}

// --- Busca os dados na API ---
onMounted(async () => {
  await Promise.all([
    fetchData('/api/relatorios/veiculos-por-pessoa', veiculosPorPessoa),
    fetchData('/api/relatorios/veiculos-por-sexo', veiculosPorSexo),
    fetchData('/api/relatorios/marcas-por-quantidade', marcasPorQuantidade),
    fetchData('/api/relatorios/marcas-por-sexo', marcasPorSexo),
    fetchData('/api/relatorios/pessoas', pessoas),
    fetchData('/api/relatorios/pessoas-por-sexo-idade-media', pessoasPorSexoIdadeMedia),
    fetchData('/api/relatorios/revisoes-por-periodo', revisoesPorPeriodo),
    fetchData('/api/relatorios/marcas-com-mais-revisoes', marcasComMaisRevisoes),
    fetchData('/api/relatorios/pessoas-com-mais-revisoes', pessoasComMaisRevisoes),
    fetchData('/api/relatorios/media-tempo-entre-revisoes', mediaTempoEntreRevisoes),
    fetchData('/api/relatorios/proximas-revisoes', proximasRevisoes)
  ])
})

// --- Computed para dados no formato Chart.js ---
const chartDataVeiculosPorPessoa = computed(() => ({
  labels: veiculosPorPessoa.value.map(v => v.nome),
  datasets: [{ label: 'Veículos', data: veiculosPorPessoa.value.map(v => v.quantidade) }]
}))

const chartDataVeiculosPorSexo = computed(() => ({
  labels: veiculosPorSexo.value.map(v => v.sexo),
  datasets: [{ label: 'Veículos', data: veiculosPorSexo.value.map(v => v.total) }]
}))

const chartDataMarcasPorQuantidade = computed(() => ({
  labels: marcasPorQuantidade.value.map(m => m.marca),
  datasets: [{ label: 'Quantidade', data: marcasPorQuantidade.value.map(m => m.quantidade) }]
}))

const chartDataMarcasPorSexo = computed(() => ({
  labels: marcasPorSexo.value.labels,
  datasets: marcasPorSexo.value.datasets
}))

const chartDataPessoas = computed(() => ({
  labels: pessoas.value.map(p => p.nome),
  datasets: [{ label: 'Proprietários', data: pessoas.value.map(p => p.quantidade) }]
}))

const chartDataPessoasPorSexoIdadeMedia = computed(() => ({
  labels: pessoasPorSexoIdadeMedia.value.map(x => x.sexo),
  datasets: [{ label: 'Idade Média', data: pessoasPorSexoIdadeMedia.value.map(x => x.idade_media) }]
}))

const chartDataRevisoesPorPeriodo = computed(() => revisoesPorPeriodo.value)
const chartDataMarcasComMaisRevisoes = computed(() => ({
  labels: marcasComMaisRevisoes.value.map(m => m.marca),
  datasets: [{ label: 'Revisões', data: marcasComMaisRevisoes.value.map(m => m.total_revisoes) }]
}))

const chartDataPessoasComMaisRevisoes = computed(() => ({
  labels: pessoasComMaisRevisoes.value.map(p => p.nome),
  datasets: [{ label: 'Revisões', data: pessoasComMaisRevisoes.value.map(p => p.total_revisoes) }]
}))

const chartDataMediaTempoEntreRevisoes = computed(() => ({
  labels: mediaTempoEntreRevisoes.value.map(x => x.nome),
  datasets: [{ label: 'Dias', data: mediaTempoEntreRevisoes.value.map(x => x.media_dias) }]
}))

const chartDataProximasRevisoes = computed(() => ({
  labels: proximasRevisoes.value.map(x => `${x.nome} - ${x.modelo}`),
  datasets: [{ label: 'Próximas Revisões', data: proximasRevisoes.value.map(x => new Date(x.proxima_revisao_aproximada).getTime() / (1000*60*60*24)) }]
}))

// --- Array de gráficos para renderização dinâmica ---
const graficos = computed(() => [
  { title: 'Veículos por Proprietário', chartData: chartDataVeiculosPorPessoa, type: 'bar' },
  { title: 'Veículos por Sexo do Proprietário', chartData: chartDataVeiculosPorSexo, type: 'pie' },
  { title: 'Marcas por Quantidade', chartData: chartDataMarcasPorQuantidade, type: 'bar' },
  { title: 'Marcas por Sexo do Proprietário', chartData: chartDataMarcasPorSexo, type: 'bar', options: { scales: { x: { stacked: true }, y: { stacked: true } } } },
  { title: 'Pessoas', chartData: chartDataPessoas, type: 'bar' },
  { title: 'Pessoas por Sexo e Idade Média', chartData: chartDataPessoasPorSexoIdadeMedia, type: 'bar' },
  { title: 'Revisões por Período', chartData: chartDataRevisoesPorPeriodo, type: 'line' },
  { title: 'Marcas com Mais Revisões', chartData: chartDataMarcasComMaisRevisoes, type: 'bar' },
  { title: 'Pessoas com Mais Revisões', chartData: chartDataPessoasComMaisRevisoes, type: 'bar' },
  { title: 'Média de Tempo entre Revisões por Pessoa', chartData: chartDataMediaTempoEntreRevisoes, type: 'bar' },
  { title: 'Próximas Revisões', chartData: chartDataProximasRevisoes, type: 'bar' }
])
</script>
