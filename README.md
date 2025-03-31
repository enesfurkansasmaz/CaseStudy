<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
</p>

## Proje Gereksinimleri
- PHP 8.2+
- Laravel 12
- PHP 8.2+ ile uyumlu çalışan Composer

## Kurulum hakkında

- Projeyi klonlayın.
- `npm install` komutunu çalıştırın.
- 'docker-compose up -d --build' komutunu çalıştırarak docker'ı ayağa kaldırın.
- `docker compose exec app php artisan migrate --seed` komutunu çalıştırarak veritabanını oluşturun ve seed'leyin.
- Eğer yukarıdaki komut çalışmaz ise, aşağıdaki iki komutu çalıştırın.
- `docker compose exec app php artisan migrate` komutunu çalıştırın.
- `docker compose exec app php artisan db:seed` komutunu çalıştırın.

## Routes
- 'idesoft.postman_collection.json' dosyasını içeri aktararak tüm endpointlere ulaşabilirsiniz.
