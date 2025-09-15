<template>
  <div>
    <h1>Dashboard de Relatórios</h1>
    <div class="dashboard-grid">
      <section>
        <GraficoVeiculosPorMarca/>
      </section>
      <section>
        <GraficoVeiculosPorPessoa/>
      </section>
      <section>
        <GraficoVeiculosPorSexo/>
      </section>
      <section>
        <GraficoMarcasPorSexo/>
      </section>
      <section>
        <GraficoPessoasPorSexoIdade/>
      </section>
      <section>
        <GraficoRevisoesPorPeriodo/>
      </section>
      <section>
        <GraficoMarcasComMaisRevisoes/>
      </section>
      <section>
        <GraficoPessoasComMaisRevisoes/>
      </section>
      <section>
        <GraficoMediaTempoEntreRevisoes/>
      </section>
      <section>
        <GraficoProximasRevisoes/>
      </section>
    </div>
  </div>
</template>

<script>
import GraficoVeiculosPorMarca from './GraficoVeiculosPorMarca.vue'
import GraficoVeiculosPorPessoa from './GraficoVeiculosPorPessoa.vue'
import GraficoVeiculosPorSexo from './GraficoVeiculosPorSexo.vue'
import GraficoMarcasPorSexo from './GraficoMarcasPorSexo.vue'
import GraficoPessoasPorSexoIdade from './GraficoPessoasPorSexoIdade.vue'
import GraficoRevisoesPorPeriodo from './GraficoRevisoesPorPeriodo.vue'
import GraficoMarcasComMaisRevisoes from './GraficoMarcasComMaisRevisoes.vue'
import GraficoPessoasComMaisRevisoes from './GraficoPessoasComMaisRevisoes.vue'
import GraficoMediaTempoEntreRevisoes from './GraficoMediaTempoEntreRevisoes.vue'
import GraficoProximasRevisoes from './GraficoProximasRevisoes.vue'

export default {
  components: {
    GraficoVeiculosPorMarca,
    GraficoVeiculosPorPessoa,
    GraficoVeiculosPorSexo,
    GraficoMarcasPorSexo,
    GraficoPessoasPorSexoIdade,
    GraficoRevisoesPorPeriodo,
    GraficoMarcasComMaisRevisoes,
    GraficoPessoasComMaisRevisoes,
    GraficoMediaTempoEntreRevisoes,
    GraficoProximasRevisoes
  }
}
</script>

<style>
.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(480px, 1fr));
  gap: 2rem;
}
section {
  background: #fff;
  padding: 1rem 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px #0001;
}
h1 {
  text-align: center;
  margin-bottom: 2rem;
}
</style>