<template>
  <q-page padding>
    <div class="row items-center q-col-gutter-md q-mb-md">
      <div class="col">
        <div class="text-h5">Personel <q-chip color="primary" text-color="white" dense>{{ rows.length }}</q-chip></div>
      </div>
      <div class="col-auto">
        <q-btn flat icon="refresh" label="Yenile" @click="fetchPersonel" :loading="loading" />
      </div>
    </div>

    <q-form @submit.prevent="createPersonel" class="q-mb-lg">
      <div class="row q-col-gutter-md">
        <div class="col-12 col-md-3">
          <q-input v-model="form.adi_soyadi" label="Adı Soyadı" dense outlined required />
        </div>
        <div class="col-12 col-md-3">
          <q-select
            v-model="form.unvan_id"
            :options="unvanOptions"
            option-value="id"
            option-label="unvan_adi"
            emit-value map-options
            label="Ünvan"
            dense outlined
            :loading="loadingUnvan"
            clearable
            required
          />
        </div>
        <div class="col-12 col-md-3">
          <q-select
            v-model="form.birim_id"
            :options="birimOptions"
            option-value="id"
            option-label="birim_adi"
            emit-value map-options
            label="Birim"
            dense outlined
            :loading="loadingBirim"
            clearable
            required
          />
        </div>
        <div class="col-12 col-md-3">
          <q-input v-model="form.notlar" label="Notlar" dense outlined />
        </div>
        <div class="col-12 flex items-end">
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
      <template #body-cell-unvan="props">
        <q-td :props="props">{{ props.row.unvan?.unvan_adi || '-' }}</q-td>
      </template>
      <template #body-cell-birim="props">
        <q-td :props="props">{{ props.row.birim?.birim_adi || '-' }}</q-td>
      </template>
      <template #body-cell-actions="props">
        <q-td :props="props">
          <q-btn color="primary" flat dense icon="edit" class="q-mr-sm" @click="openEdit(props.row)" />
          <q-btn color="negative" flat dense icon="delete" @click="remove(props.row.id)" />
        </q-td>
      </template>
    </q-table>

    <!-- Edit Dialog -->
    <q-dialog v-model="editDialog">
      <q-card style="min-width: 520px; max-width: 90vw">
        <q-card-section class="row items-center q-gutter-sm">
          <q-icon name="edit" color="primary" size="24px" />
          <div class="text-h6">Personel Düzenle</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-separator />
        <q-card-section>
          <div class="row q-col-gutter-md">
            <div class="col-12 col-md-6">
              <q-input v-model="editForm.adi_soyadi" label="Adı Soyadı" dense outlined required />
            </div>
            <div class="col-12 col-md-6">
              <q-input v-model="editForm.notlar" label="Notlar" dense outlined />
            </div>
            <div class="col-12 col-md-6">
              <q-select
                v-model="editForm.unvan_id"
                :options="unvanOptions"
                option-value="id"
                option-label="unvan_adi"
                emit-value map-options
                label="Ünvan"
                dense outlined
                :loading="loadingUnvan"
                clearable
                required
              />
            </div>
            <div class="col-12 col-md-6">
              <q-select
                v-model="editForm.birim_id"
                :options="birimOptions"
                option-value="id"
                option-label="birim_adi"
                emit-value map-options
                label="Birim"
                dense outlined
                :loading="loadingBirim"
                clearable
                required
              />
            </div>
          </div>
        </q-card-section>
        <q-separator />
        <q-card-actions align="right">
          <q-btn flat label="İptal" v-close-popup />
          <q-btn color="primary" label="Kaydet" :loading="loading" @click="updatePersonel" />
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
const loadingUnvan = ref(false)
const loadingBirim = ref(false)

const rows = ref([])
const unvanOptions = ref([])
const birimOptions = ref([])

const form = ref({ adi_soyadi: '', unvan_id: null, birim_id: null, notlar: '' })

// Edit dialog state
const editDialog = ref(false)
const editForm = ref({ id: null, adi_soyadi: '', unvan_id: null, birim_id: null, notlar: '' })

const columns = [
  { name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true },
  { name: 'adi_soyadi', label: 'Adı Soyadı', field: 'adi_soyadi', align: 'left' },
  { name: 'unvan', label: 'Ünvan', field: 'unvan', align: 'left' },
  { name: 'birim', label: 'Birim', field: 'birim', align: 'left' },
  { name: 'notlar', label: 'Notlar', field: 'notlar', align: 'left' },
  { name: 'created_at', label: 'Oluşturma', field: 'created_at', align: 'left' },
  { name: 'actions', label: 'İşlemler', field: 'actions', align: 'right' },
]

async function fetchPersonel() {
  loading.value = true
  try {
    const { data } = await axios.get('http://127.0.0.1:8000/api/personel')
    rows.value = data
  } finally {
    loading.value = false
  }
}

async function fetchUnvanlar() {
  loadingUnvan.value = true
  try {
    const { data } = await axios.get('http://127.0.0.1:8000/api/unvanlar')
    unvanOptions.value = data
  } finally {
    loadingUnvan.value = false
  }
}

async function fetchBirimler() {
  loadingBirim.value = true
  try {
    const { data } = await axios.get('http://127.0.0.1:8000/api/birimler')
    birimOptions.value = data
  } finally {
    loadingBirim.value = false
  }
}

async function createPersonel() {
  loading.value = true
  try {
    await axios.post('http://127.0.0.1:8000/api/personel', form.value)
    form.value = { adi_soyadi: '', unvan_id: null, birim_id: null, notlar: '' }
    Notify.create({ type: 'positive', message: 'Personel eklendi' })
    fetchPersonel()
  } finally {
    loading.value = false
  }
}

async function remove(id) {
  loading.value = true
  try {
    await axios.delete(`http://127.0.0.1:8000/api/personel/${id}`)
    Notify.create({ type: 'positive', message: 'Personel silindi' })
    fetchPersonel()
  } finally {
    loading.value = false
  }
}

function openEdit(row) {
  // Prefill edit form
  editForm.value = {
    id: row.id,
    adi_soyadi: row.adi_soyadi || '',
    unvan_id: row.unvan?.id ?? row.unvan_id ?? null,
    birim_id: row.birim?.id ?? row.birim_id ?? null,
    notlar: row.notlar || ''
  }
  editDialog.value = true
}

async function updatePersonel() {
  if (!editForm.value.id) return
  loading.value = true
  try {
    const payload = {
      adi_soyadi: editForm.value.adi_soyadi,
      unvan_id: editForm.value.unvan_id,
      birim_id: editForm.value.birim_id,
      notlar: editForm.value.notlar,
    }
    await axios.put(`http://127.0.0.1:8000/api/personel/${editForm.value.id}`, payload)
    Notify.create({ type: 'positive', message: 'Personel güncellendi' })
    editDialog.value = false
    fetchPersonel()
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchPersonel()
  fetchUnvanlar()
  fetchBirimler()
})
</script>
