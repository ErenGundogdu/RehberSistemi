<template>
  <q-page padding>
    <div class="q-pa-md">
      <!-- Centered search under top bar -->
      <div class="row justify-center q-mt-sm q-mb-md">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
          <q-input
            v-model="query"
            placeholder="Personel ara (ad, birim, ünvan)"
            filled
            rounded
            clearable
            clear-icon="close"
            debounce="300"
            class="shadow-1 bg-white"
          >
            <template #prepend>
              <q-icon name="search" color="primary" />
            </template>
          </q-input>
        </div>
      </div>

      <div class="row items-center q-col-gutter-md q-mb-md">
        <div class="col">
          <div class="text-h5">Askeri Rehber Sistemi • Personeller
            <q-chip color="primary" text-color="white" dense>{{ filtered.length }}</q-chip>
          </div>
        </div>
        <div class="col-auto">
          <q-btn flat icon="refresh" label="Yenile" @click="load" :loading="loading" />
        </div>
      </div>

      <div class="row q-col-gutter-md">
        <div v-if="loading" class="col-12">
          <q-skeleton type="rect" height="120px" class="q-mb-md" v-for="n in 4" :key="n" />
        </div>
        <template v-else>
          <div v-if="filtered.length === 0" class="col-12 text-grey">Kayıt bulunamadı</div>
          <div v-for="p in filtered" :key="p.id" class="col-12 col-sm-6 col-md-4 col-lg-3">
            <q-card class="person-card clickable shadow-1" clickable v-ripple @click="openPerson(p)">
              <q-card-section class="row items-center no-wrap">
                <q-avatar color="primary" text-color="white" size="44px">{{ initials(p.adi_soyadi) }}</q-avatar>
                <div class="q-ml-md col">
                  <div class="text-subtitle1 text-weight-medium">{{ p.adi_soyadi }}</div>
                  <div class="row items-center q-gutter-xs q-mt-xs">
                    <q-chip dense color="secondary" text-color="black" icon="badge">{{ p.unvan?.unvan_adi || '-' }}</q-chip>
                    <q-chip dense color="secondary" text-color="black" icon="business">{{ p.birim?.birim_adi || '-' }}</q-chip>
                  </div>
                </div>
              </q-card-section>
              <q-separator />
              <q-card-section class="q-pt-sm">
                <div class="row items-center">
                  <q-icon name="notes" size="16px" class="q-mr-xs text-grey-7" />
                  <div class="text-caption" v-if="p.notlar">{{ p.notlar }}</div>
                  <div class="text-caption text-grey" v-else>Not yok</div>
                </div>
              </q-card-section>
            </q-card>
          </div>
        </template>
      </div>

      <!-- Person Detail Dialog (Editable) -->
      <q-dialog v-model="showDialog">
        <q-card style="width: 620px; max-width: 95vw">
          <q-card-section class="row items-center q-gutter-sm">
            <q-avatar color="primary" text-color="white" size="48px">{{ initials(editForm.adi_soyadi) }}</q-avatar>
            <div class="text-h6">Personel Detay / Düzenle</div>
            <q-space />
            <q-btn icon="close" flat round dense v-close-popup />
          </q-card-section>
          <q-separator />
          <q-card-section>
            <div class="row q-col-gutter-md">
              <div class="col-12 col-sm-6">
                <q-input v-model="editForm.adi_soyadi" label="Adı Soyadı" dense outlined required />
              </div>
              <div class="col-12 col-sm-6">
                <q-input v-model="editForm.notlar" label="Notlar" dense outlined />
              </div>
              <div class="col-12 col-sm-6">
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
                />
              </div>
              <div class="col-12 col-sm-6">
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
                />
              </div>
              <div class="col-12 text-caption text-grey-7" v-if="selected?.created_at">
                Oluşturma: {{ selected?.created_at }}
              </div>
            </div>
          </q-card-section>
          <q-separator />
          <q-card-actions align="right">
            <q-btn flat label="Kapat" v-close-popup />
            <q-btn color="primary" :loading="saving" label="Kaydet" @click="updatePerson" />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </div>
  </q-page>
</template>

<script setup>
import axios from 'axios'
import { ref, computed, onMounted } from 'vue'
import { Notify } from 'quasar'

const loading = ref(false)
const saving = ref(false)
const rows = ref([])
const query = ref('')

// Dialog state
const showDialog = ref(false)
const selected = ref(null)

// Edit form and dropdown options
const editForm = ref({ id: null, adi_soyadi: '', unvan_id: null, birim_id: null, notlar: '' })
const unvanOptions = ref([])
const birimOptions = ref([])
const loadingUnvan = ref(false)
const loadingBirim = ref(false)

const filtered = computed(() => {
  const q = query.value.trim().toLowerCase()
  if (!q) return rows.value
  return rows.value.filter(p =>
    (p.adi_soyadi || '').toLowerCase().includes(q) ||
    (p.unvan?.unvan_adi || '').toLowerCase().includes(q) ||
    (p.birim?.birim_adi || '').toLowerCase().includes(q)
  )
})

function initials(name = '') {
  return name
    .split(' ')
    .filter(Boolean)
    .slice(0, 2)
    .map(s => s[0]?.toUpperCase())
    .join('') || 'P'
}

async function load() {
  loading.value = true
  try {
    const { data } = await axios.get('http://127.0.0.1:8000/api/personel')
    rows.value = data
  } catch {
    Notify.create({ type: 'negative', message: 'Personel listesi alınamadı. Backend çalışıyor mu?' })
  } finally {
    loading.value = false
  }
}

onMounted(load)

function openPerson(p) {
  selected.value = p
  // Prefill edit form
  editForm.value = {
    id: p.id,
    adi_soyadi: p.adi_soyadi || '',
    unvan_id: p.unvan?.id ?? p.unvan_id ?? null,
    birim_id: p.birim?.id ?? p.birim_id ?? null,
    notlar: p.notlar || ''
  }
  // Ensure dropdown options are loaded
  if (unvanOptions.value.length === 0) fetchUnvanlar()
  if (birimOptions.value.length === 0) fetchBirimler()
  showDialog.value = true
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

async function updatePerson() {
  if (!editForm.value.id) return
  saving.value = true
  try {
    const payload = {
      adi_soyadi: editForm.value.adi_soyadi,
      unvan_id: editForm.value.unvan_id,
      birim_id: editForm.value.birim_id,
      notlar: editForm.value.notlar,
    }
    await axios.put(`http://127.0.0.1:8000/api/personel/${editForm.value.id}`, payload)
    Notify.create({ type: 'positive', message: 'Personel güncellendi' })
    showDialog.value = false
    await load()
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.person-card {
  transition: transform 160ms ease, box-shadow 160ms ease, border-color 160ms ease;
  border-left: 4px solid var(--q-secondary);
}
.person-card:hover,
.person-card:focus {
  transform: translateY(-3px) scale(1.06);
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
}
.person-card:active {
  transform: scale(1.03);
}
.clickable {
  cursor: pointer;
}
</style>
