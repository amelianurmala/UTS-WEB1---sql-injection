# UTS-WEB1---sql-injection

# SQL Injection Demo — Eksperimen Keamanan Web

> Repositori ini berisi hasil eksperimen SQL Injection sebagai bagian dari tugas UTS Pemrograman Web.
> 
> **Artikel lengkap:** [Bahaya SQL Injection: Bagaimana Hacker Masuk Tanpa Kata Sandi](https://medium.com/@amelianurmala02/bahaya-sql-injection-bagaimana-hacker-masuk-tanpa-kata-sandi-c4334fa93570)

---

## Deskripsi

Eksperimen ini bertujuan untuk memahami bagaimana serangan **SQL Injection** bekerja pada form login yang tidak aman, serta cara mencegahnya menggunakan **Prepared Statement**.

Eksperimen dilakukan menggunakan:
- **PHP** — bahasa pemrograman server-side
- **MySQL** — database
- **XAMPP** — local server (Apache + MySQL)

---

## Struktur File

```
sql-injection-demo/
├── login.php          # Halaman login RENTAN (vulnerable)
├── login_aman.php     # Halaman login AMAN (secure)
├── database.sql       # Script untuk membuat database dan tabel
└── README.md          # Dokumentasi eksperimen
```

---

## Cara Menjalankan

### Prasyarat
- XAMPP sudah terinstall
- Apache dan MySQL sudah berjalan

### Langkah-langkah

**1. Import Database**
- Buka `localhost/phpmyadmin`
- Buat database baru bernama `latihan_sql`
- Klik tab **SQL** lalu import file `database.sql`

**2. Letakkan File PHP**
- Copy semua file ke folder `C:\xampp\htdocs\sqli_demo\`

**3. Buka di Browser**
- Versi rentan: `localhost/sqli_demo/login.php`
- Versi aman: `localhost/sqli_demo/login_aman.php`

---

## Hasil Eksperimen

### Percobaan 1 — Login Normal
- **Username:** `admin`
- **Kata Sandi:** `admin123`
- **Hasil:** ✅ Login BERHASIL — sistem berjalan normal

### Percobaan 2 — Serangan SQL Injection
- **Username:** `admin' OR '1'='1' --`
- **Kata Sandi:** `apasaja`
- **Hasil di login.php:** ✅ Login BERHASIL — sistem **BERHASIL DIBOBOL**
- **Hasil di login_aman.php:** ❌ Login GAGAL — serangan **BERHASIL DICEGAH**

---

## Penjelasan Serangan

Ketika input `admin' OR '1'='1' --` dimasukkan, query yang dijalankan menjadi:

```sql
SELECT * FROM users WHERE username='admin' OR '1'='1' -- ' AND password='apasaja'
```

- `OR '1'='1'` → selalu bernilai TRUE, sehingga query mengembalikan semua data
- `--` → mengomentari sisa query termasuk pengecekan kata sandi

---

## Solusi — Prepared Statement

Prepared Statement memisahkan struktur query dari data, sehingga input berbahaya tidak bisa mengubah logika SQL:

```php
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username=? AND password=?");
mysqli_stmt_bind_param($stmt, "ss", $username, $password);
mysqli_stmt_execute($stmt);
```

