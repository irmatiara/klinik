# Pelayanan Medis - Sistem Informasi Klinik (Halim Center)

Aplikasi web manajemen pelayanan medis dan alur antrian klinik yang dibangun menggunakan **Laravel 11**, **Tailwind CSS**, **Alpine.js**, **Aiven MySQL**, dan di-deploy secara serverless di **Vercel**.

---

## 🌐 Live URL & Akses Aplikasi
- **Website Live**: [https://klinik-seven-chi.vercel.app](https://klinik-seven-chi.vercel.app)
- **Database**: Aiven MySQL (Cloud Managed DB)
- **Deployment Platform**: Vercel Serverless

---

## Akun Bawaan (Default Credentials)

Seluruh akun demo menggunakan **Password**: `12345678`

| Role / Jabatan | Email | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@klinik.com` | `12345678` | Akses penuh ke seluruh modul & master data |
| **Dokter** | `dokter@klinik.com` | `12345678` | Ruang periksa dokter & pencatatan rekam medis |
| **Apoteker** | `apoteker@klinik.com` | `12345678` | Stasiun farmasi, resep obat & penyerahan obat |
| **Kasir** | `kasir@klinik.com` | `12345678` | Pembayaran tagihan & cetak rincian transaksi |
| **Perawat** | `perawat@klinik.com` | `12345678` | Stasiun triage & pemeriksaan awal (suhu, TD, BB) |
| **Resepsionis** | `resepsionis@klinik.com` | `12345678` | Pendaftaran pasien & pengambilan nomor antrian |

---

## Fitur & Modul Utama
1. **Dashboard Klinik Real-Time**
   - Rangkuman total pasien, antrian harian (berdasarkan WIB), status pelayanan selesai, dan pendapatan kasir.
   - Pemantauan alur antrian aktif per stasiun pelayanan (Triage, Dokter, Kasir, Farmasi).
   - Peringatan (*Alert*) otomatis untuk stok obat menipis (≤ 10 unit).

2. **Master Data Pasien**
   - Registrasi pasien baru, penomoran Rekam Medis (RM) otomatis (`RM-xxxx`).
   - Riwayat data diri, alamat, jenis kelamin, dan nomor telepon.

3. **Registrasi & Antrian Pasien**
   - Penerbitan nomor antrian otomatis (`A-001`, `A-002`, dst).
   - Penentuan biaya administrasi & layanan awal.

4. **Pemeriksaan Awal (Stasiun Perawat / Triage)**
   - Input tanda vital pasien: Tekanan Darah (TD), Suhu Tubuh, dan Berat Badan (BB).

5. **Ruang Dokter & Rekam Medis**
   - Diagnosis dokter, keluhan pasien, tindakan medis, dan peresepan obat multi-item.

6. **Kasir & Pembayaran**
   - Kalkulasi otomatis biaya layanan + total harga obat resep.
   - Update status pembayaran lunas.

7. **Stasiun Farmasi & Apotek**
   - Penyiapan dan penyerahan obat resep ke pasien.
   - Pemotongan stok obat secara otomatis setelah obat diserahkan.

---

## URL Pembantu Maintenance (Artisan Helper Routes)
Untuk mempermudah manajemen database tanpa memerlukan akses SSH terminal di Vercel:

- **Reset & Migration Fresh (Membuat Ulang Database Clean + Seed)**:
  `https://klinik-seven-chi.vercel.app/artisan-migrate-fresh`

- **Seeding Data Default & Reset Password (`12345678`)**:
  `https://klinik-seven-chi.vercel.app/artisan-seed`

- **Migration Standar**:
  `https://klinik-seven-chi.vercel.app/artisan-migrate`

---

## Panduan Jalankan Secara Lokal (Local Development)

1. **Clone Repository**:
   ```bash
   git clone https://github.com/irmatiara/klinik.git
   cd klinik
   ```

2. **Install Dependensi Composer & NPM**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment (`.env`)**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Atur koneksi database pada `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=web_klinik
   DB_USERNAME=root
   DB_PASSWORD=
   APP_TIMEZONE=Asia/Jakarta
   APP_LOCALE=id
   ```

4. **Jalankan Migration & Seed**:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Jalankan Server Lokal**:
   ```bash
   npm run dev
   php artisan serve
   ```
   Akses di browser: `http://localhost:8000`