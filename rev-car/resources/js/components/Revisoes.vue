<template>
  <div>
    <h2>Revisões</h2>
    <button class="btn btn-success mb-2" @click="openCreateForm">Nova Revisão</button>

    <div v-if="showForm" class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">{{ form.id ? 'Editar' : 'Nova' }} Revisão</h5>
        <form @submit.prevent="saveRevisao">
          <div class="mb-2">
            <label>Veículo:</label>
            <select v-model="form.veiculo_id" class="form-control" required>
              <option value="">Selecione</option>
              <option v-for="v in veiculos" :key="v.id" :value="v.id">{{ v.modelo }}</option>
            </select>
          </div>
          <div class="mb-2">
            <label>Data:</label>
            <input v-model="form.data_revisao" type="date" class="form-control" required />
          </div>
          <div class="mb-2">
            <label>Descrição:</label>
            <input v-model="form.descricao" class="form-control" required />
          </div>
          <button class="btn btn-primary" type="submit">Salvar</button>
          <button class="btn btn-secondary ms-2" @click="closeForm" type="button">Cancelar</button>
        </form>
      </div>
    </div>

    <table class="table table-striped" v-if="revisoes.length">
      <thead>
        <tr>
          <th>ID</th>
          <th>Veículo</th>
          <th>Data</th>
          <th>Descrição</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="r in revisoes" :key="r.id">
          <td>{{ r.id }}</td>
          <td>{{ r.veiculo?.modelo }}</td>
          <td>{{ r.data }}</td>
          <td>{{ r.descricao }}</td>
          <td>
            <button class="btn btn-sm btn-info" @click="editRevisao(r)">Editar</button>
            <button class="btn btn-sm btn-danger ms-2" @click="deleteRevisao(r.id)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-else class="alert alert-info">Nenhuma revisão cadastrada.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const revisoes = ref([])
const veiculos = ref([])
const showForm = ref(false)
const form = ref({ id: null, veiculo_id: '', data_revisao: '', descricao: '' })

async function fetchRevisoes() {
  const res = await fetch('/api/revisoes')
  revisoes.value = await res.json()
}

async function fetchVeiculos() {
  const res = await fetch('/api/veiculos')
  veiculos.value = await res.json()
}

onMounted(async () => {
  await fetchRevisoes()
  await fetchVeiculos()
})

function openCreateForm() {
  form.value = { id: null, veiculo_id: '', data: '', descricao: '' }
  showForm.value = true
}

function closeForm() {
  showForm.value = false
}

function editRevisao(r) {
  form.value = { ...r, veiculo_id: r.veiculo_id, data_revisao: r.data_revisao }
  showForm.value = true
}


async function saveRevisao() {
  const method = form.value.id ? 'PUT' : 'POST'
  const url = form.value.id ? `/api/revisoes/${form.value.id}` : '/api/revisoes'
  const res = await fetch(url, {
    method,
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(form.value)
  })
  if (res.ok) {
    await fetchRevisoes()
    closeForm()
  } else {
    alert('Erro ao salvar revisão!')
  }
}

async function deleteRevisao(id) {
  if (confirm('Confirma exclusão?')) {
    const res = await fetch(`/api/revisoes/${id}`, { method: 'DELETE' })
    if (res.ok) await fetchRevisoes()
    else alert('Erro ao excluir revisão!')
  }
}
</script>