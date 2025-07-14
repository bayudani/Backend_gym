
# Backend Gym

Repository ini merupakan backend utama untuk aplikasi dashboard Gym, dibangun menggunakan [Filament](https://filamentphp.com/) pada Laravel. Backend ini berfungsi sebagai pusat manajemen data dan antarmuka admin.

## Relasi antar Repository

- **Rest_api_gym**: Repository ini menyediakan REST API untuk berbagai fitur Gym, dibangun menggunakan Express (Node.js). Backend Gym terhubung dengan Rest_api_gym untuk komunikasi data secara terstruktur dan aman.
- **Frontend_gym**: Repository ini adalah aplikasi berbasis Flutter yang digunakan oleh user dan member gym. Frontend_gym berinteraksi dengan Rest_api_gym untuk mendapatkan dan mengirim data, yang kemudian diproses dan dikelola oleh Backend Gym.

## Alur Integrasi

1. **Backend Gym**: Dashboard admin, manajemen data, dan pengelolaan sistem (Filament Laravel).
2. **Rest_api_gym**: Menyediakan API berbasis Express untuk aplikasi mobile dan web, sebagai jembatan antara Backend Gym dan Frontend_gym.
3. **Frontend_gym**: Aplikasi Flutter untuk end-user, mengambil data dari Rest_api_gym.

## Diagram Sederhana

```
[Backend Gym (Filament Admin)]
         |
         v
[Rest_api_gym (Express REST API)]
         |
         v
[Frontend_gym (Flutter)]
```


## Fitur Utama

- **Manajemen Member**Menambah, mengedit, menghapus, dan melihat data member gym secara efisien.
- **Manajemen Artikel**Kelola data artikel.
- **Manajemen Membership**kelola paket membership.
- **Pembayaran & Transaksi**Pencatatan pembayaran member, tagihan, dan laporan keuangan sederhana.
- **Item reward**
  kelola data item reward
- **Absensi member**

## Instalasi

Lihat dokumentasi pada masing-masing repository:

- [Rest_api_gym](https://github.com/bayudani/Rest_api_gym)
- [Frontend_gym](https://github.com/bayudani/frontend_gym)

---

1. **Clone repositori ini**

   ```bash
   git clone https://github.com/bayudani/Backend_gym.git
   cd Backend_gym
   ```
2. **Install dependensi**

   ```bash
   composer install
   ```
3. **Konfigurasi Environment**

   - Copy file `.env.example` menjadi `.env`, lalu atur konfigurasi database dan lainnya sesuai kebutuhan.

   ```bash
   cp .env.example .env
   ```
4. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```
5. **Migrasi dan Seed Database**

   ```bash
   php artisan migrate --seed
   ```
6. **Jalankan Server**

   ```bash
   php artisan serve
   ```

## Struktur Direktori

- `app/` : Business logic & model aplikasi
- `routes/` : Definisi routing API dan web
- `resources/views/` : Template Blade untuk antarmuka admin
- `database/` : Migrasi dan seeder
- `public/` : Public assets
