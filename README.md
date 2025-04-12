<h1 align="center">🌾 Sistem Pendukung Keputusan (SPK) 🌾</h1>
<h3 align="center">Pemilihan Penerima Prioritas Bantuan Benih Tanaman Pangan</h3>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-red?style=flat&logo=laravel">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-blue?style=flat&logo=tailwind-css">
  <img src="https://img.shields.io/badge/MySQL-8.x-orange?style=flat&logo=mysql">
</p>

---

📌 **SPK Pemilihan Penerima Prioritas Bantuan Benih Tanaman Pangan** atau didalam aplikasi disebut BANPRI (Bantuan Prioritas) adalah aplikasi berbasis web yang dirancang untuk membantu Dinas Pertanian dalam menentukan kelompok tani yang berhak menerima bantuan benih secara lebih objektif menggunakan metode **ROC (Rank Order Centroid) & WPM (Weighted Product Method)**.

---

## 🚀 Fitur Utama

✔️ **Manajemen Data Kelompok Tani**  
✔️ **Pembobotan Kriteria menggunakan ROC**  
✔️ **Seleksi Penerima Bantuan dengan WPM**  
✔️ **Riwayat Seleksi & Hasil Per Kecamatan**  
✔️ **Import data by Excel atau per-kelompok**  
✔️ **Laporan Hasil Seleksi dalam format PDF**  
✔️ **Sistem Autentikasi untuk Admin & Petugas**  

---

## 🛠️ Teknologi yang Digunakan

- 🏗 **Laravel 10** → Backend Development  
- 🎨 **Tailwind CSS** → UI/UX Modern  
- 🗄 **MySQL** → Database Management  
- 📊 **Laravel Excel** → Impor/Ekspor Data  
- 📝 **DOMPDF** → Pembuatan Laporan PDF  

---

## 🔧 Instalasi & Setup

### 1️⃣ Clone Repository
```bash
git clone https://github.com/username/spk-bantuan.git
cd spk-bantuan
```

### 2️⃣ Install Dependencies
```bash
composer install
npm install
```

### 3️⃣ Konfigurasi `.env`
```bash
cp .env.example .env
php artisan key:generate
```
Sesuaikan konfigurasi database di `.env` sebelum menjalankan migrasi.

### 4️⃣ Migrasi & Seeder Database
```bash
php artisan migrate --seed
```

### 5️⃣ Menjalankan Aplikasi
```bash
php artisan serve
```
🔗 Akses aplikasi di **[http://127.0.0.1:8000](http://127.0.0.1:8000)**.

---

## 👥 Hak Akses Pengguna

| **Role**   | **Fitur yang Dapat Diakses** |
|-----------|----------------------------|
| **Admin**  | Memiliki akses semua fitur |
| **Petugas** | Mengelola data kelompok tani & laporan |

---

## 📄 Lisensi

Proyek ini dirilis di bawah lisensi **MIT**.

---
## 👨‍💻 Developer

Dikembangkan oleh **Komang Mahendra** © 2025.  
📩 Jika ada pertanyaan atau saran, silakan buat **issue** di repository ini.  

🚀 **Selamat menggunakan aplikasi ini!** 🎯
