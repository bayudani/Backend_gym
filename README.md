# Backend Gym

Backend Gym adalah sistem backend yang dikembangkan dengan PHP dan Blade, dirancang untuk mendukung kebutuhan aplikasi manajemen gym/fitness center. Proyek ini menyediakan API, pengelolaan data member, serta fitur-fitur lain yang dibutuhkan oleh bisnis gym modern.

## Fitur Utama

- **Manajemen Member**  
  Menambah, mengedit, menghapus, dan melihat data member gym secara efisien.
- **Manajemen Pelatih**  
  Kelola data pelatih, jadwal pelatihan, dan sesi latihan.
- **Manajemen Jadwal**  
  Penjadwalan kelas, sesi pribadi, serta integrasi kalender.
- **Pembayaran & Transaksi**  
  Pencatatan pembayaran member, tagihan, dan laporan keuangan sederhana.
- **Article**
  kelola data artikel seputar fitnes 




## Instalasi

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



