# Rehber Sistemi (Laravel REST API)

Bu proje, kurum içi rehber yönetimi için basit bir REST API sağlar. Aşağıdaki kaynaklar ve CRUD uçları mevcuttur:

- Birimler (`birimler`)
- Ünvanlar (`unvanlar`)
- Personel (`personel`)

## Kurulum

- Depoyu klonlayın ve bağımlılıkları kurun:
```
composer install
cp .env.example .env
php artisan key:generate
```

- `.env` içinde veritabanı ayarlarını yapın ve migrasyonları çalıştırın:
```
php artisan migrate
```

- Geliştirme sunucusu:
```
php artisan serve
```

## Veri Modeli ve Migrasyonlar

`database/migrations/` altında oluşturuldu:

- `birimler` — `birim_adi (string)`, `konumu (string|null)`
- `unvanlar` — `unvan_adi (string)`
- `personel` — `adi_soyadi (string)`, `unvan_id (fk)`, `birim_id (fk)`, `notlar (text|null)`

İlişkiler: `personel.unvan_id -> unvanlar.id`, `personel.birim_id -> birimler.id` (update cascade, delete restrict).

## Modeller

`app/Models/`:

- `Birim` — `$table = 'birimler'`, `personeller(): hasMany(Personel::class)`
- `Unvan` — `$table = 'unvanlar'`, `personeller(): hasMany(Personel::class)`
- `Personel` — `$table = 'personel'`, `unvan(): belongsTo(Unvan::class)`, `birim(): belongsTo(Birim::class)`

## Controller’lar (REST, Model Binding)

`app/Http/Controllers/`:

- `BirimController` — CRUD, model param: `{birimler}`
- `UnvanController` — CRUD, model param: `{unvanlar}`
- `PersonelController` — CRUD, model param: `{personel}` ve `unvan`, `birim` ilişkileri eager-load

## Rotalar

`bootstrap/app.php` içinde API routing etkin:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

`routes/api.php`:

```php
Route::apiResource('birimler', BirimController::class);
Route::apiResource('unvanlar', UnvanController::class);
Route::apiResource('personel', PersonelController::class);
```

## Test (REST Client)

VS Code REST Client (humao.rest-client) ile test için `api-test.http` eklenmiştir. `@baseUrl` varsayılanı `http://localhost:8000`’dır. Dosyayı açıp her isteğin üzerindeki “Send Request” butonunu kullanabilirsiniz.

Önerilen sıra:

1. `Birimler - Oluştur`
2. `Unvanlar - Oluştur`
3. `Personel - Oluştur` (oluşan id’leri kullanın)

## Notlar

- Hata yakalama bloğu (try/catch) eklenmemiştir; istenen şekilde yalın CRUD davranışı vardır.
- Yanıtlar JSON’dur. Oluşturma 201, silme 204 döndürür.

---

