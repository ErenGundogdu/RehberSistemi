<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-toolbar-title> Askeri Rehber Sistemi </q-toolbar-title>
        <q-space />
        <q-tabs dense class="text-white" indicator-color="secondary" active-color="secondary">
          <q-route-tab icon="home" label="Ana Sayfa" to="/" exact />
          <q-route-tab icon="business" label="Birimler" to="/birimler" />
          <q-route-tab icon="badge" label="Ünvanlar" to="/unvanlar" />
          <q-route-tab icon="people" label="Personel" to="/personel" />
        </q-tabs>
      </q-toolbar>
    </q-header>

    <!-- fixed, click-through watermark overlay -->
    <div class="bg-watermark" style="background-image: url('/icons/weapon-watermark.svg')"></div>

    <q-page-container>
      <router-view />
    </q-page-container>

    <q-footer elevated class="bg-primary text-white footer-bar">
      <q-toolbar class="justify-between">
        <div class="row items-center no-wrap">
          <q-icon name="military_tech" size="18px" class="q-mr-sm" />
          <span class="text-caption">Askeri Rehber Sistemi</span>
        </div>
        <div class="text-caption">© 2025 • Tüm hakları saklıdır.</div>
      </q-toolbar>
    </q-footer>
  </q-layout>
</template>
