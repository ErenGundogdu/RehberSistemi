const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: 'birimler', component: () => import('pages/BirimlerPage.vue') },
      { path: 'unvanlar', component: () => import('pages/UnvanlarPage.vue') },
      { path: 'personel', component: () => import('pages/PersonelPage.vue') },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
]

export default routes
