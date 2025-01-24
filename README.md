# TEMU - Platform Kemitraan Non-Profit dan Perusahaan

TEMU adalah proyek RESTful API yang dibangun menggunakan Laravel. Proyek ini bertujuan untuk membantu kemitraan antara organisasi non-profit dan perusahaan agar lebih mudah dan terpercaya.

## 📌 Teknologi yang Digunakan
- Laravel (Backend)
- MySQL (Database)
- Flutter (Frontend)

## 📂 Struktur Proyek
```
backendalpsemester3/
│-- app/
│-- bootstrap/
│-- config/
│-- database/
│-- public/
│-- routes/
│-- storage/
│-- tests/
│-- .env.example
│-- artisan
│-- composer.json
│-- package.json
```

## 🚀 Instalasi dan Menjalankan Proyek

### 1. Clone Repository
```bash
git clone https://github.com/leowdij08/ALP-Semester-3-Kelompok-2.git
cd ALP-Semester-3-Kelompok-2/backendalpsemester3
```

### 2. Instalasi Dependensi
```bash
composer install
```

### 3. Konfigurasi Lingkungan
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Lalu, atur konfigurasi database di dalam `.env`.

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Migrasi dan Seeder Database
```bash
php artisan migrate --seed
```

### 6. Menjalankan Server
```bash
php artisan serve
```
Akses API melalui: `http://127.0.0.1:8000`

## 🔗 API Endpoint
Berikut adalah beberapa endpoint utama yang tersedia:

### Autentikasi
- **[POST]** `/api/register_organisasi` - Mendaftarkan organisasi baru
- **[POST]** `/api/register_perusahaan` - Mendaftarkan perusahaan baru
- **[POST]** `/api/login` - Login pengguna
- **[POST]** `/api/logout` - Logout pengguna
- **[POST]** `/api/getSession` - Mendapatkan sesi pengguna

### Pengguna
- **[GET]** `/api/user` - Mendapatkan data pengguna yang sedang login

### Acara
- **[GET]** `/api/acara` - Mendapatkan semua acara
- **[GET]** `/api/acara/getIncoming` - Mendapatkan acara yang akan datang
- **[GET]** `/api/acara/{idAcara}` - Mendapatkan acara berdasarkan ID
- **[GET]** `/api/acara/search/{keyword}` - Mencari acara berdasarkan kata kunci
- **[POST]** `/api/acara/filter` - Memfilter acara
- **[POST]** `/api/acara` - Membuat acara baru
- **[PUT]** `/api/acara/{idAcara}` - Memperbarui acara
- **[DELETE]** `/api/acara/{idAcara}` - Menghapus acara

### Chat
- **[GET]** `/api/chat` - Mendapatkan semua chat
- **[GET]** `/api/chat/{idChat}` - Mendapatkan chat berdasarkan ID
- **[POST]** `/api/chat/{idPenerima}` - Mengirim chat
- **[GET]** `/api/chat/byTeman/{idTemanChat}` - Mendapatkan chat berdasarkan teman

### Perusahaan
- **[GET]** `/api/perusahaan` - Mendapatkan semua perusahaan
- **[GET]** `/api/perusahaan/{idPerusahaan}` - Mendapatkan perusahaan berdasarkan ID
- **[GET]** `/api/perusahaan/search/{keyword}` - Mencari perusahaan
- **[PUT]** `/api/perusahaan` - Memperbarui perusahaan

### Organisasi
- **[GET]** `/api/organisasi` - Mendapatkan semua organisasi
- **[GET]** `/api/organisasi/{idOrganisasi}` - Mendapatkan organisasi berdasarkan ID
- **[GET]** `/api/organisasi/search/{keyword}` - Mencari organisasi
- **[PUT]** `/api/organisasi` - Memperbarui organisasi

### Laporan Pertanggungjawaban
- **[GET]** `/api/laporan/{id}` - Mendapatkan laporan berdasarkan ID
- **[GET]** `/api/laporan/search/{keyword}` - Mencari laporan
- **[POST]** `/api/laporan/{idAcara}` - Membuat laporan
- **[PUT]** `/api/laporan/{idAcara}` - Memperbarui laporan
- **[DELETE]** `/api/laporan/{idAcara}` - Menghapus laporan

### Penanggung Jawab
- **[GET]** `/api/penanggungjawaborganisasi/{id}` - Mendapatkan penanggung jawab organisasi berdasarkan ID
- **[GET]** `/api/penanggungjawaborganisasi/search/{keyword}` - Mencari penanggung jawab organisasi
- **[PUT]** `/api/penanggungjawaborganisasi` - Memperbarui penanggung jawab organisasi
- **[GET]** `/api/penanggungjawabperusahaan/{id}` - Mendapatkan penanggung jawab perusahaan berdasarkan ID
- **[GET]** `/api/penanggungjawabperusahaan/search/{keyword}` - Mencari penanggung jawab perusahaan
- **[PUT]** `/api/penanggungjawabperusahaan` - Memperbarui penanggung jawab perusahaan

### Pembayaran dan Rekening
- **[GET]** `/api/pembayaranperusahaan` - Mendapatkan semua pembayaran perusahaan
- **[GET]** `/api/pembayaranperusahaan/{idPembayaran}` - Mendapatkan pembayaran berdasarkan ID
- **[POST]** `/api/pembayaranperusahaan/{idAcara}` - Membuat pembayaran
- **[GET]** `/api/rekeningperusahaan` - Mendapatkan rekening perusahaan
- **[PUT]** `/api/rekeningperusahaan` - Memperbarui rekening perusahaan
- **[POST]** `/api/rekeningperusahaan` - Membuat rekening perusahaan
- **[DELETE]** `/api/rekeningperusahaan` - Menghapus rekening perusahaan
- **[GET]** `/api/rekeningtemu` - Mendapatkan rekening TEMU
- **[PUT]** `/api/rekeningtemu` - Memperbarui rekening TEMU
- **[GET]** `/api/rekeningorganisasi` - Mendapatkan rekening organisasi
- **[PUT]** `/api/rekeningorganisasi` - Memperbarui rekening organisasi
- **[POST]** `/api/rekeningorganisasi` - Membuat rekening organisasi
- **[DELETE]** `/api/rekeningorganisasi` - Menghapus rekening organisasi

### Penarikan Dana
- **[GET]** `/api/penarikanorganisasi` - Mendapatkan semua penarikan organisasi
- **[GET]** `/api/penarikanorganisasi/{id}` - Mendapatkan penarikan berdasarkan ID
- **[POST]** `/api/penarikanorganisasi` - Membuat penarikan organisasi

## 🔗 Frontend Repository
Proyek ini memiliki frontend yang dikembangkan menggunakan Flutter:
[TEMU Frontend (Flutter)](https://github.com/alfiantenggara/3RDProject-Temu/)
