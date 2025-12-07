<div align="center">

# 💰 Dompet Pintar
### Sistem Manajemen Keuangan Pribadi Terintegrasi

Dibangun dengan pendekatan **Database-Centric Logic** menggunakan  
**MySQL Stored Procedures, Functions, dan Triggers** untuk menjaga integritas saldo secara otomatis.

<br>

<img src="https://img.shields.io/badge/Backend-PHP%208.2-777BB4?style=for-the-badge&logo=php&logoColor=white" />
<img src="https://img.shields.io/badge/Database-MySQL%208.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
<img src="https://img.shields.io/badge/Scripting-Python%203.10-3776AB?style=for-the-badge&logo=python&logoColor=white" />
<img src="https://img.shields.io/badge/Frontend-Vanilla%20JS-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" />

</div>

---

## 📸 Tampilan Aplikasi

| Dashboard | Edit & Koreksi |
|:---:|:---:|
| <img src="dashboard.png" width="100%"> | <img src="edit.png" width="100%"> |
| *Saldo realtime & statistik ringkas* | *Koreksi saldo otomatis saat transaksi diubah* |

| Pencarian Live | Export Laporan |
|:---:|:---:|
| <img src="cari.png" width="100%"> | <img src="report.png" width="100%"> |
| *Search tanpa reload (AJAX)* | *Python sebagai worker laporan* |

---

## ✨ Fitur Utama

🔹 **Smart Balance Automation (Trigger-Based)**  
Seluruh perhitungan saldo ditangani oleh MySQL Triggers — bukan melalui kode PHP:
- Insert ➜ Perubahan saldo otomatis
- Delete ➜ Saldo otomatis dikembalikan (*refund*)
- Update ➜ Selisih saldo dikoreksi secara cerdas

🔹 **Integrasi Lintas Bahasa — PHP × Python**  
Frontend PHP memanggil script Python untuk proses **reporting** dan **data extraction**.

🔹 **Stored Procedure & Function**  
- Procedure menjadi *entry point* satu-satunya untuk input transaksi
- Function melakukan formatting label `[+]` / `[-]` agar data konsisten di seluruh aplikasi

---

## 🧠 Arsitektur Database

| Komponen | Nama | Fungsi |
|:---|:---|:---|
| **Tabel** | `dompet`, `transaksi` | Saldo utama & riwayat transaksi |
| **Procedure** | `tambah_transaksi` | Validasi & input standar |
| **Function** | `format_label` | Label otomatis transaksi |
| **Triggers** | `update_saldo_*` | Insert/Update/Delete saldo |
| **View** | `laporan_view` | Query siap pakai untuk laporan |

Semua business logic terjadi pada Database Layer → aplikasi aman dari *human error*.

---

## 🚀 Instalasi

1. Clone repository
```bash
git clone https://github.com/username/dompet-pintar.git
````

2. Buat database & import

```sql
CREATE DATABASE dompet_db;
```

Import file `database.sql`.

3. Konfigurasi MySQL
   Edit file `koneksi.php`:

```php
$host = "...";
$user = "...";
$pass = "...";
$db   = "dompet_db";
```

4. Install library Python (opsional untuk export laporan)

```bash
pip install mysql-connector-python
```

5. Jalankan aplikasi
   Akses melalui:

```
http://localhost/dompet_pintar
```

---

## 🔍 Penjelasan Konsep

### Separation of Concerns (Pemisahan Tugas)

| Layer                  | Peran                                   |
| :--------------------- | :-------------------------------------- |
| Frontend (HTML/CSS/JS) | UI dan interaksi                        |
| Backend (PHP)          | Request/Response & routing              |
| Database (MySQL Logic) | Seluruh perhitungan saldo & aturan data |
| Python Worker          | Laporan & pengolahan file               |

---

## 💡 Contoh Kode Kunci

### Trigger Update (Koreksi Saldo Otomatis)

```sql
-- Pembatalan efek nominal lama
IF OLD.jenis = 'Pemasukan' THEN
  UPDATE dompet SET saldo = saldo - OLD.nominal;
ELSE
  UPDATE dompet SET saldo = saldo + OLD.nominal;
END IF;

-- Penerapan nominal baru
IF NEW.jenis = 'Pemasukan' THEN
  UPDATE dompet SET saldo = saldo + NEW.nominal;
ELSE
  UPDATE dompet SET saldo = saldo - NEW.nominal;
END IF;
```

### Memanggil Worker Python dari PHP

```php
$output = shell_exec("python export_data.py 2>&1");
```

---

## 📂 Struktur Folder

```
dompet-pintar/
├── index.php
├── edit.php
├── cari.php
├── koneksi.php
├── export_data.py
├── script.js
├── style.css
├── database.sql
└── README.md
```
