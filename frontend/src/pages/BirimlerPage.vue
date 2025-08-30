<template>
  <q-page padding>
    <div class="row items-center q-col-gutter-md q-mb-md">
      <div class="col">
        <div class="text-h5">Birimler <q-chip color="primary" text-color="white" dense>{{ rows.length }}</q-chip></div>
      </div>
      <div class="col-auto">
        <q-btn flat icon="refresh" label="Yenile" @click="fetchAll" :loading="loading" />
      </div>
    </div>

    <q-form @submit.prevent="createBirim" class="q-mb-lg">
      <div class="row q-col-gutter-md">
        <div class="col-12 col-md-4">
          <q-input v-model="form.birim_adi" label="Birim Adı" dense outlined required />
        </div>
        <div class="col-12 col-md-4">
          <q-input v-model="form.konumu" label="Konumu" dense outlined />
        </div>
        <div class="col-12 col-md-4 flex items-end">
          <q-btn color="primary" type="submit" label="Ekle" :loading="loading" />
        </div>
      </div>
    </q-form>

    <q-table
      :rows="rows"
      :columns="columns"
      row-key="id"
      flat bordered
      :loading="loading"
      :pagination="{ rowsPerPage: 10 }"
    >
      <template #body-cell-actions="props">
        <q-td :props="props">
          <q-btn color="primary" flat dense icon="edit" class="q-mr-sm" @click="openEdit(props.row)" />
          <q-btn color="negative" flat dense icon="delete" @click="remove(props.row.id)" />
        </q-td>
      </template>
    </q-table>

    <!-- Edit Dialog -->
    <q-dialog v-model="editDialog">
      <q-card style="min-width: 500px; max-width: 90vw">
        <q-card-section class="row items-center q-gutter-sm">
          <q-icon name="edit" color="primary" size="24px" />
          <div class="text-h6">Birim Düzenle</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-separator />
        <q-card-section>
          <div class="row q-col-gutter-md">
            <div class="col-12 col-md-6">
              <q-input v-model="editForm.birim_adi" label="Birim Adı" dense outlined required />
            </div>
            <div class="col-12 col-md-6">
              <q-input v-model="editForm.konumu" label="Konumu" dense outlined />
            </div>
          </div>
        </q-card-section>
        <q-separator />
        <q-card-actions align="right">
          <q-btn flat label="İptal" v-close-popup />
          <q-btn color="primary" label="Kaydet" :loading="loading" @click="updateBirim" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { Notify } from 'quasar'

const loading = ref(false)
const rows = ref([])
const form = ref({ birim_adi: '', konumu: '' })

// Edit dialog state
const editDialog = ref(false)
const editForm = ref({ id: null, birim_adi: '', konumu: '' })

const columns = [
  { name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true },
  { name: 'birim_adi', label: 'Birim Adı', field: 'birim_adi', align: 'left' },
  { name: 'konumu', label: 'Konumu', field: 'konumu', align: 'left' },
  { name: 'created_at', label: 'Oluşturma', field: 'created_at', align: 'left' },
  { name: 'actions', label: 'İşlemler', field: 'actions', align: 'right' },
]

async function fetchAll() {
  loading.value = true
  try {
    const { data } = await axios.get('http://127.0.0.1:8000/api/birimler')
    rows.value = data
  } finally {
    loading.value = false
  }
}

async function createBirim() {
  loading.value = true
  try {
    await axios.post('http://127.0.0.1:8000/api/birimler', form.value)
    form.value = { birim_adi: '', konumu: '' }
    Notify.create({ type: 'positive', message: 'Birim eklendi' })
    fetchAll()
  } finally {
    loading.value = false
  }
}

async function remove(id) {
  loading.value = true
  try {
    await axios.delete(`http://127.0.0.1:8000/api/birimler/${id}`)
    Notify.create({ type: 'positive', message: 'Birim silindi' })
    fetchAll()
  } finally {
    loading.value = false
  }
}

function openEdit(row) {
  editForm.value = {
    id: row.id,
    birim_adi: row.birim_adi || '',
    konumu: row.konumu || ''
  }
  editDialog.value = true
}

async function updateBirim() {
  if (!editForm.value.id) return
  loading.value = true
  try {
    const payload = {
      birim_adi: editForm.value.birim_adi,
      konumu: editForm.value.konumu,
    }
    await axios.put(`http://127.0.0.1:8000/api/birimler/${editForm.value.id}`, payload)
    Notify.create({ type: 'positive', message: 'Birim güncellendi' })
    editDialog.value = false
    fetchAll()
  } finally {
    loading.value = false
  }
}

onMounted(fetchAll)
</script>
