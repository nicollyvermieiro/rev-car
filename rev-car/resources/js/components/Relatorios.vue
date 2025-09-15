<template>
  <div>
    <h2>Relatórios</h2>
    <div class="mb-4">
      <h4>Veículos por Proprietário</h4>
      <BarChart v-if="veiculosPorPessoa.length" :chartData="chartDataVeiculosPorPessoa" label="Proprietário" />
      <div v-else class="alert alert-info">Carregando...</div>
    </div>

    <div class="mb-4">
      <h4>Veículos por Sexo do Proprietário</h4>
      <PieChart v-if="veiculosPorSexo.length" :chartData="chartDataVeiculosPorSexo" />
      <div v-else class="alert alert-info">Carregando...</div>
    </div>

    <div class="mb-4">
      <h4>Marcas por Quantidade</h4>
      <BarChart v-if="marcasPorQuantidade.length" :chartData="chartDataMarcasPorQuantidade" label="Marca" />
      <div v-else class="alert alert-info">Carregando...</div>
    </div>

    <div class="mb-4">
      <h4>Marcas por Sexo do Proprietário</h4>
      <BarChart v-if="marcasPorSexo.labels.length" :chartData="chartDataMarcasPorSexo" label="Marca" />
      <div v-else class="alert alert-info">Carregando...</div>
    </div>

    <div class="mb-4">
      <h4>Pessoas</h4>
      <BarChart v-if="pessoas.length" :chartData="chartDataPessoas" label="Pessoa" />
      <div v-else class="alert alert-info">Carregando...</div>
    </div>

    <div class="mb-4">
      <h4>Pessoas por Sexo e Idade Média</h4>
      <BarChart v-if="pessoasPorSexoIdadeMedia.length" :chartData="chartDataPessoasPorSexoIdadeMedia" label="Sexo" />
      <div v-else class="alert alert-info">Carregando...</div>
    </div>

    <div class="mb-4">
      <h4>Revisões por Período</h4>
      <LineChart v-if="revisoesPorPeriodo.labels.length" :chartData="chartDataRevisoesPorPeriodo" />
      <div v-else class="alert alert-info">Carregando...</div>
    </div>

    <div class="mb-4">
      <h4>Marcas com Mais Revisões</h4>
      <BarChart v-if="marcasComMaisRevisoes.length" :chartData="chartDataMarcasComMaisRevisoes" label="Marca" />
      <div v-else class="alert alert-info">Carregando...</div>
    </div>

    <div class="mb-4">
      <h4>Pessoas com Mais Revisões</h4>
      <BarChart v-if="pessoasComMaisRevisoes.length" :chartData="chartDataPessoasComMaisRevisoes" label="Pessoa" />
      <div v-else class="alert alert-info">Carregando...</div>
    </div>

    <div class="mb-4">
      <h4>Média de Tempo entre Revisões por Pessoa</h4>
      <BarChart v-if="mediaTempoEntreRevisoes.length" :chartData="chartDataMediaTempoEntreRevisoes" label="Pessoa" />
      <div v-else class="alert alert-info">Carregando...</div>
    </div>

    <div class="mb-4">
      <h4>Próximas Revisões</h4>
      <BarChart v-if="proximasRevisoes.length" :chartData="chartDataProximasRevisoes" label="Veículo" />
      <div v-else class="alert alert-info">Carregando...</div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import BarChart from './charts/BarChart.vue'
import PieChart from './charts/PieChart.vue'
import LineChart from './charts/LineChart.vue'

// Dados dos relatórios
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

onMounted(async () => {
  veiculosPorPessoa.value = await (await fetch('/api/relatorios/veiculos-por-pessoa')).json()
  veiculosPorSexo.value = await (await fetch('/api/relatorios/veiculos-por-sexo')).json()
  marcasPorQuantidade.value = await (await fetch('/api/relatorios/marcas-por-quantidade')).json()
  marcasPorSexo.value = await (await fetch('/api/relatorios/marcas-por-sexo')).json()
  pessoas.value = await (await fetch('/api/relatorios/pessoas')).json()
  pessoasPorSexoIdadeMedia.value = await (await fetch('/api/relatorios/pessoas-por-sexo-idade-media')).json()
  revisoesPorPeriodo.value = await (await fetch('/api/relatorios/revisoes-por-periodo')).json()
  marcasComMaisRevisoes.value = await (await fetch('/api/relatorios/marcas-com-mais-revisoes')).json()
  pessoasComMaisRevisoes.value = await (await fetch('/api/relatorios/pessoas-com-mais-revisoes')).json()
  mediaTempoEntreRevisoes.value = await (await fetch('/api/relatorios/media-tempo-entre-revisoes')).json()
  proximasRevisoes.value = await (await fetch('/api/relatorios/proximas-revisoes')).json()
})

// Montagem dos dados para Chart.js
const chartDataVeiculosPorPessoa = computed(() => ({
  labels: veiculosPorPessoa.value.map(v => v.nome),
  datasets: [{
    label: 'Veículos',
    data: veiculosPorPessoa.value.map(v => v.veiculos.length),
    backgroundColor: 'rgba(54,162,235,0.6)'
  }]
}))

const chartDataVeiculosPorSexo = computed(() => ({
  labels: veiculosPorSexo.value.map(v => v.sexo),
  datasets: [{ label: 'Veículos', data: veiculosPorSexo.value.map(v => v.total), backgroundColor: ['#36A2EB','#FF6384'] }]
}))

const chartDataMarcasPorQuantidade = computed(() => ({
  labels: marcasPorQuantidade.value.map(m => m.marca),
  datasets: [{ label: 'Quantidade', data: marcasPorQuantidade.value.map(m => m.quantidade), backgroundColor: 'rgba(255,99,132,0.6)' }]
}))

const chartDataMarcasPorSexo = computed(() => ({
  labels: marcasPorSexo.value.labels,
  datasets: marcasPorSexo.value.datasets
}))

const chartDataPessoas = computed(() => ({
  labels: pessoas.value.map(p => p.nome),
  datasets: [{ label: 'Proprietários', data: pessoas.value.map(p => p.quantidade), backgroundColor: 'rgba(153,102,255,0.6)' }]
}))

const chartDataPessoasPorSexoIdadeMedia = computed(() => ({
  labels: pessoasPorSexoIdadeMedia.value.map(x => x.sexo),
  datasets: [{ label: 'Idade Média', data: pessoasPorSexoIdadeMedia.value.map(x => x.idade_media), backgroundColor: 'rgba(255,206,86,0.6)' }]
}))

const chartDataRevisoesPorPeriodo = computed(() => ({
  labels: revisoesPorPeriodo.value.labels,
  datasets: revisoesPorPeriodo.value.datasets
}))

const chartDataMarcasComMaisRevisoes = computed(() => ({
  labels: marcasComMaisRevisoes.value.map(m => m.marca),
  datasets: [{ label: 'Revisões', data: marcasComMaisRevisoes.value.map(m => m.total_revisoes), backgroundColor: 'rgba(75,192,192,0.6)' }]
}))

const chartDataPessoasComMaisRevisoes = computed(() => ({
  labels: pessoasComMaisRevisoes.value.map(p => p.nome),
  datasets: [{ label: 'Revisões', data: pessoasComMaisRevisoes.value.map(p => p.total_revisoes), backgroundColor: 'rgba(255,159,64,0.6)' }]
}))

const chartDataMediaTempoEntreRevisoes = computed(() => ({
  labels: mediaTempoEntreRevisoes.value.map(x => x.nome),
  datasets: [{ label: 'Dias', data: mediaTempoEntreRevisoes.value.map(x => x.media_dias), backgroundColor: 'rgba(54,162,235,0.6)' }]
}))

const chartDataProximasRevisoes = computed(() => ({
  labels: proximasRevisoes.value.map(x => `${x.nome} - ${x.modelo}`),
  datasets: [{ label: 'Próximas Revisões', data: proximasRevisoes.value.map(x => x.proxima_revisao_aproximada), backgroundColor: 'rgba(255,205,86,0.6)' }]
}))
</script>