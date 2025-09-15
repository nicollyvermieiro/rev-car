<template>
  <div>
    <h2>Proprietários</h2>
    <button class="btn btn-success mb-2" @click="openCreateForm">Novo Proprietário</button>

    <div v-if="showForm" class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">{{ form.id ? 'Editar' : 'Novo' }} Proprietário</h5>
        <form @submit.prevent="saveProprietario">
          <div class="mb-2">
            <label>Nome:</label>
            <input v-model="form.nome" class="form-control" required />
          </div>
          <div class="mb-2">
            <label>Sexo:</label>
            <select v-model="form.sexo" class="form-control" required>
              <option value="">Selecione</option>
              <option value="M">Masculino</option>
              <option value="F">Feminino</option>
            </select>
          </div>
          <div class="mb-2">
            <label>Idade:</label>
            <input v-model="form.idade" type="number" class="form-control" required />
          </div>
          <button class="btn btn-primary" type="submit">Salvar</button>
          <button class="btn btn-secondary ms-2" @click="closeForm" type="button">Cancelar</button>
        </form>
      </div>
    </div>

    <table class="table table-striped" v-if="lista.length">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Sexo</th>
          <th>Idade</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in lista" :key="p.id">
          <td>{{ p.id }}</td>
          <td>{{ p.nome }}</td>
          <td>{{ p.sexo }}</td>
          <td>{{ p.idade }}</td>
          <td>
            <button class="btn btn-sm btn-info" @click="editProprietario(p)">Editar</button>
            <button class="btn btn-sm btn-danger ms-2" @click="deleteProprietario(p.id)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-else class="alert alert-info">Nenhum proprietário cadastrado.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
const lista = ref([])
const showForm = ref(false)
const form = ref({ id: null, nome: '', sexo: '', idade: '' })

async function fetchProprietarios() {
  const res = await fetch('/api/proprietarios')
  lista.value = await res.json()
}

onMounted(fetchProprietarios)

function openCreateForm() {
  form.value = { id: null, nome: '', sexo: '', idade: '' }
  showForm.value = true
}

function closeForm() {
  showForm.value = false
}

function editProprietario(p) {
  form.value = { ...p }
  showForm.value = true
}

async function saveProprietario() {
  const method = form.value.id ? 'PUT' : 'POST'
  const url = form.value.id ? `/api/proprietarios/${form.value.id}` : '/api/proprietarios'
  const res = await fetch(url, {
    method,
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(form.value)
  })
  if (res.ok) {
    await fetchProprietarios()
    closeForm()
  } else {
    alert('Erro ao salvar proprietário!')
  }
}

async function deleteProprietario(id) {
  if (confirm('Confirma exclusão?')) {
    const res = await fetch(`/api/proprietarios/${id}`, { method: 'DELETE' })
    if (res.ok) await fetchProprietarios()
    else alert('Erro ao excluir proprietário!')
  }
}
</script>