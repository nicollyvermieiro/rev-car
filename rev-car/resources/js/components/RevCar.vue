<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
      <div class="container-fluid">
        <a class="navbar-brand" href="#" @click.prevent="goTo('home')">Rev-Car</a>
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="#" @click.prevent="goTo('veiculos')">Veículos</a></li>
          <li class="nav-item"><a class="nav-link" href="#" @click.prevent="goTo('proprietarios')">Proprietários</a></li>
          <li class="nav-item"><a class="nav-link" href="#" @click.prevent="goTo('revisoes')">Revisões</a></li>
          <li class="nav-item"><a class="nav-link" href="#" @click.prevent="goTo('relatorios')">Relatórios</a></li>
        </ul>
      </div>
    </nav>

    <component :is="currentPage" />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Home from './Home.vue'
import Veiculos from './Veiculos.vue'
import Proprietarios from './Proprietarios.vue'
import Revisoes from './Revisoes.vue'
import Relatorios from './Relatorios.vue'

const pages = { home: Home, veiculos: Veiculos, proprietarios: Proprietarios, revisoes: Revisoes, relatorios: Relatorios }
const currentPage = ref(Home)

function goTo(page) {
  currentPage.value = pages[page]
}
</script>
