# RehberSistemi

RehberSistemi, kurum içi rehber (telefon/kişi) yönetimini sağlayan tam yığın bir uygulamadır. Arka uçta Laravel 12 ile geliştirilmiş bir REST API bulunur; birimler, unvanlar ve personel için CRUD uçları sağlar. Ön uç, Quasar Framework (Vue 3) ile geliştirilmiş tek sayfa uygulamasıdır ve bu API’yi tüketerek kullanıcıya arayüz sunar.

- Backend: PHP 8.2 + Laravel 12 REST API
- Frontend: Quasar 2 (Vue 3, Vite, Pinia, Vue Router)
- Kapsam: Birim/Unvan/Personel kayıtları, ilişki yönetimi, JSON tabanlı CRUD işlemleri
- Kullanım: İç sistemlerde hızlıca kişi/birim rehberi oluşturma ve yönetimi

---

## 1) Mimarî

- Backend (`backend/`): Laravel 12 ile JSON REST API.
  - Dosya: `backend/composer.json` — PHP ^8.2, `laravel/framework` ^12.
  - API yönlendirme: `backend/bootstrap/app.php` ve `backend/routes/api.php`.
- Frontend (`frontend/`): Quasar + Vue 3 SPA.
  - Dosya: `frontend/package.json` — `quasar`, `vue`, `pinia`, `vue-router`, `axios`.

## 2) Özellikler

- Birimler, Unvanlar, Personel için uçtan uca CRUD.
- İlişkiler: Personel → Unvan, Personel → Birim.
- JSON yanıtlar; oluşturma 201, silme 204.
- VS Code REST Client ile hazır test dosyası (bkz: `backend/README.md`).

## 3) Klasör Yapısı

- `backend/`: Laravel API
  - Modeller: `backend/app/Models/` (`Birim`, `Unvan`, `Personel`)
  - Rotalar: `backend/routes/api.php`
  - Konfig: `backend/config/app.php`
- `frontend/`: Quasar (Vue 3) SPA
  - Geliştirme: `quasar dev`
  - Build: `quasar build`

## 4) Teknolojiler

- Backend: PHP 8.2, Laravel 12, Laravel Tinker
- Dev tools: Laravel Breeze (dev), Pint, Pail, Sail (opsiyonel), PHPUnit
- Frontend: Quasar 2, Vue 3, Pinia, Vue Router, Axios, Vite

## 5) Hızlı Başlangıç

### Backend

1. Bağımlılıklar ve ilk kurulum:
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```
2. Veritabanı ayarlarını `.env` içinde yapın ve migrasyonları çalıştırın:
   ```bash
   php artisan migrate
   ```
3. Geliştirme sunucusu:
   ```bash
   php artisan serve
   ```
   Varsayılan: `http://localhost:8000`

> Ayrıntılı API ve test akışı için `backend/README.md` dosyasına bakın.

### Frontend

1. Bağımlılıklar:
   ```bash
   npm install
   # veya
   yarn
   ```
2. Geliştirme:
   ```bash
   npm run dev
   ```
   Varsayılan: `http://localhost:9000`
3. Build:
   ```bash
   npm run build
   ```

## 6) API Uçları (Özet)

`backend/routes/api.php`:

- `GET/POST/PUT/PATCH/DELETE /api/birimler` — `BirimController`
- `GET/POST/PUT/PATCH/DELETE /api/unvanlar` — `UnvanController`
- `GET/POST/PUT/PATCH/DELETE /api/personel` — `PersonelController` (unvan, birim ilişkileri eager-load)

Base URL (lokal): `http://localhost:8000/api`

## 7) Veri Modeli (Özet)

- `birimler`: `birim_adi (string)`, `konumu (string|null)`
- `unvanlar`: `unvan_adi (string)`
- `personel`: `adi_soyadi (string)`, `unvan_id (fk)`, `birim_id (fk)`, `notlar (text|null)`

İlişkiler:

- `Personel` → `Unvan` (belongsTo)
- `Personel` → `Birim` (belongsTo)
- `Birim`/`Unvan` → `Personel` (hasMany)

## 8) Geliştirme Komutları (Örnekler)

- Backend composer script’leri (`backend/composer.json`):
  - `composer test` → `php artisan test`
  - `composer dev` → Laravel server, queue listener, logs, Vite gibi süreçleri `concurrently` ile paralel başlatır
- Frontend npm script’leri (`frontend/package.json`):
  - `npm run dev` → `quasar dev`
  - `npm run build` → `quasar build`
  - `npm run lint` / `npm run format`

## 9) Test ve Araçlar

- VS Code REST Client ile `backend/api-test.http` üzerinden istekleri çalıştırın.
- Log/Debug: Laravel Pail (dev), Whoops (dev).

## 10) Notlar

- Kimlik doğrulama varsayılan olarak yoktur; proje yalın CRUD odaklıdır.
- Ortam değişkenleri için `.env` kullanılır (uygulama adı, DB, URL, vb.).
- Üretim modunda uygun cache/queue/CI ayarları eklenmelidir.

