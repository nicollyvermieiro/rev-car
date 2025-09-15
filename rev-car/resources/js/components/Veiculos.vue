<template>
  <div>
    <h2>Veículos</h2>
    <button class="btn btn-success mb-2" @click="openCreateForm">Novo Veículo</button>

    <!-- Formulário de criação/edição -->
    <div v-if="showForm" class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">{{ form.id ? 'Editar' : 'Novo' }} Veículo</h5>
        <form @submit.prevent="saveVeiculo">
          <div class="mb-2">
            <label>Marca:</label>
            <input v-model="form.marca" class="form-control" required />
          </div>
          <div class="mb-2">
            <label>Modelo:</label>
            <input v-model="form.modelo" class="form-control" required />
          </div>
          <div class="mb-2">
            <label>Ano:</label>
            <input v-model="form.ano" type="number" class="form-control" required />
          </div>
          <div class="mb-2">
            <label>Proprietário:</label>
            <select v-model="form.proprietario_id" class="form-control" required>
              <option :value="''" disabled>Selecione</option>
              <option v-for="p in proprietarios" :key="p.id" :value="p.id">{{ p.nome }}</option>
            </select>
          </div>
          <button class="btn btn-primary" type="submit">Salvar</button>
          <button class="btn btn-secondary ms-2" @click="closeForm" type="button">Cancelar</button>
        </form>
      </div>
    </div>

    <!-- Tabela de veículos -->
    <table class="table table-striped" v-if="veiculos.length">
      <thead>
        <tr>
          <th>ID</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>Ano</th>
          <th>Proprietário</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="v in veiculos" :key="v.id">
          <td>{{ v.id }}</td>
          <td>{{ v.marca }}</td>
          <td>{{ v.modelo }}</td>
          <td>{{ v.ano }}</td>
          <td>{{ v.proprietario?.nome }}</td>
          <td>
            <button class="btn btn-sm btn-info" @click="editVeiculo(v)">Editar</button>
            <button class="btn btn-sm btn-danger ms-2" @click="deleteVeiculo(v.id)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-else class="alert alert-info">Nenhum veículo cadastrado.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const veiculos = ref([])
const proprietarios = ref([])
const showForm = ref(false)
const form = ref({ id: null, marca: '', modelo: '', ano: '', proprietario_id: '' })

async function fetchVeiculos() {
  const res = await fetch('/api/veiculos')
  veiculos.value = await res.json()
}

async function fetchProprietarios() {
  const res = await fetch('/api/proprietarios')
  proprietarios.value = await res.json()
}

onMounted(async () => {
  await fetchVeiculos()
  await fetchProprietarios()
})

function openCreateForm() {
  form.value = { id: null, marca: '', modelo: '', ano: '', proprietario_id: '' }
  showForm.value = true
}

function closeForm() {
  showForm.value = false
}

function editVeiculo(v) {
  form.value = { ...v, proprietario_id: v.proprietario_id }
  showForm.value = true
}

async function saveVeiculo() {
  const method = form.value.id ? 'PUT' : 'POST'
  const url = form.value.id ? `/api/veiculos/${form.value.id}` : '/api/veiculos'
  const res = await fetch(url, {
    method,
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(form.value)
  })
  if (res.ok) {
    await fetchVeiculos()
    closeForm()
  } else {
    alert('Erro ao salvar veículo!')
  }
}

async function deleteVeiculo(id) {
  if (confirm('Confirma exclusão?')) {
    const res = await fetch(`/api/veiculos/${id}`, { method: 'DELETE' })
    if (res.ok) await fetchVeiculos()
    else alert('Erro ao excluir veículo!')
  }
}
</script>