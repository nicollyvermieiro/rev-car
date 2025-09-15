<template>
  <div>
    <nav class="navbar">
      <button :class="{active: aba==='proprietarios'}" @click="aba='proprietarios'">Proprietários</button>
      <button :class="{active: aba==='veiculos'}" @click="aba='veiculos'">Veículos</button>
      <button :class="{active: aba==='revisoes'}" @click="aba='revisoes'">Revisões</button>
      <button :class="{active: aba==='relatorios'}" @click="aba='relatorios'">Relatórios</button>
    </nav><template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
      <div class="container-fluid">
        <a class="navbar-brand" href="#" @click.prevent="goTo('home')">Rev-Car</a>
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="#" @click.prevent="goTo('veiculos')">Veículos</a></li>
            <li class="nav-item"><a class="nav-link" href="#" @click.prevent="goTo('proprietarios')">Proprietários</a></li>
            <li class="nav-item"><a class="nav-link" href="#" @click.prevent="goTo('revisoes')">Revisões</a></li>
            <li class="nav-item"><a class="nav-link" href="#" @click.prevent="goTo('relatorios')">Relatórios</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
      <component :is="currentPage" />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Home from './Home.vue'
import Veiculos from './Veiculos.vue'
import Proprietarios from './Proprietarios.vue'
import Revisoes from './Revisoes.vue'
import Relatorios from './Relatorios.vue'

const pages = {
  home: Home,
  veiculos: Veiculos,
  proprietarios: Proprietarios,
  revisoes: Revisoes,
  relatorios: Relatorios
}

const currentPage = ref('home')
function goTo(page) {
  currentPage.value = page
}
</script>

    <section>
      <Proprietarios v-if="aba==='proprietarios'"/>
      <Veiculos v-if="aba==='veiculos'"/>
      <Revisoes v-if="aba==='revisoes'"/>
      <Dashboard v-if="aba==='relatorios'"/>
    </section>
  </div>
</template>

<script>
import Proprietarios from './Proprietarios.vue'
import Veiculos from './Veiculos.vue'
import Revisoes from './Revisoes.vue'
import Dashboard from './Dashboard.vue'

export default {
  data() {
    return {
      aba: 'proprietarios'
    }
  },
  components: {
    Proprietarios,
    Veiculos,
    Revisoes,
    Dashboard
  }
}
</script>

<style>
.navbar {
  background: #164863;
  padding: 1rem;
  display: flex;
  gap: 2rem;
  justify-content: center;
}
.navbar button {
  background: #427d9d;
  color: #fff;
  border: none;
  padding: 0.7rem 2rem;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
}
.navbar button.active,
.navbar button:hover {
  background: #9bbec8;
  color: #164863;
}
section {
  margin: 2rem;
}
</style>