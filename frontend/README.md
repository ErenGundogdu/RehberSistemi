# Quasar App (frontend)

Askeri Rehber Sistemi (Frontend)

Bu proje Quasar (Vue 3) ile geliştirilmiş bir rehber (directory) arayüzüdür. Laravel backend API ile konuşarak Birimler, Ünvanlar ve Personel yönetimi sağlar.

## Özellikler
- Personelleri ana sayfada kartlar halinde listeleme, arama (ad, birim, ünvan)
- Birimler / Ünvanlar / Personel CRUD sayfaları (ekleme, listeleme, silme)
- Üst menü sekmeleri ile hızlı geçiş; global footer
- Askeri temaya uygun renk paleti ve arka plan deseni
- İsteğe bağlı watermark (SVG) overlay

## Teknolojiler
- Quasar Framework (Vue 3, Vite)
- Axios (HTTP istekleri)
- Laravel API (backend, ayrı repo/projede çalışır)

## Gereksinimler
- Node.js 16+ (veya Quasar’ın önerdiği sürüm)
- Quasar CLI: `npm i -g @quasar/cli`
- Backend (Laravel) `http://127.0.0.1:8000` üzerinde çalışıyor olmalı

## Kurulum
```bash
npm install
# veya
yarn
```

## Çalıştırma (Geliştirme)
```bash
quasar dev
```
Uygulama varsayılan olarak `http://localhost:9000` üzerinde çalışır.

Backend API varsayılan uç noktaları:
- `GET/POST http://127.0.0.1:8000/api/birimler`
- `GET/POST http://127.0.0.1:8000/api/unvanlar`
- `GET/POST http://127.0.0.1:8000/api/personel`

Not: Frontend dosyalarında doğrudan mutlak URL’ler kullanıldı (CORS/proxy sorunlarını önlemek için). İsterseniz `src/boot/axios.js` içindeki `$api` örneğini ve `.env` ile `VITE_API_BASE_URL` değişkenini kullanacak şekilde düzenleyebilirsiniz.

## Üretim Derlemesi
```bash
quasar build
```
Oluşan derleme `dist/` altına çıkar. Sunucunuza uygun şekilde yayımlayabilirsiniz.

## Faydalı Komutlar
```bash
# Lint
npm run lint

# Format
npm run format
```

## Proje Yapısı (Önemli Dosyalar)
- `src/layouts/MainLayout.vue`: Üst sekmeler ve global footer
- `src/pages/IndexPage.vue`: Personel kart listesi ve arama
- `src/pages/BirimlerPage.vue`: Birim CRUD
- `src/pages/UnvanlarPage.vue`: Ünvan CRUD
- `src/pages/PersonelPage.vue`: Personel CRUD (birim/ünvan seçimleri dahil)
- `src/css/quasar.variables.scss`: Askeri tema renkleri
- `src/css/app.scss`: Arkaplan deseni ve global stiller
- `public/icons/weapon-watermark.svg`: Watermark görseli

## Sorun Giderme
- Personel listesi gelmiyorsa backend çalıştığından emin olun: `php artisan serve` ve `http://127.0.0.1:8000/api/personel`
- CORS hatalarında mutlak URL’ler tercih edildi. Gerekirse Laravel CORS yapılandırmasını kontrol edin.
- API uç noktaları/ID’ler değiştiyse ilgili sayfalardaki axios isteklerini güncelleyin.

### Customize the configuration

See [Configuring quasar.config.js](https://v2.quasar.dev/quasar-cli-vite/quasar-config-js).
