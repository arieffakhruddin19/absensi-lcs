name: security-skill
description: Melakukan validasi dan mitigasi kerentanan keamanan sebelum kode diimplementasikan
license: MIT
compatibility: opencode
metadata:
  audience: developers

## What I do
Secara otomatis memvalidasi *input*, mengecek otorisasi, dan memastikan standar keamanan terpenuhi agar data proyek dan user tetap aman dari celah umum (seperti SQL Injection, XSS, dan kebocoran Token).

## When to use me
- Sesaat setelah membuat fitur Form/Input.
- Sesaat setelah membuat atau memodifikasi *Endpoint API*.
- Sesaat setelah mengimplementasikan fitur *Login/Authentication*.
- Setiap kali berurusan dengan penyimpanan data sensitif (Password, Token, Data Rekam Medis).

## How I do it
Sebelum menyatakan bahwa pekerjaan selesai, AI wajib melakukan pengecekan keamanan mandiri:
1. **Input Validation**: Pastikan tidak ada data mentah dari *user* yang langsung dimasukkan ke database tanpa sanitasi (mencegah SQL Injection).
2. **XSS Protection**: Pastikan setiap *output* ke *view* (HTML/UI) yang berasal dari input user telah di-*escape* dengan benar.
3. **Authorization Check**: Pastikan *endpoint* atau halaman yang sifatnya *restricted* (seperti halaman admin) dibungkus oleh middleware/pengecekan *role* yang sesuai.
4. **Secure Storage**: Jika menyimpan Token/Password, pastikan di-hash (misal bcrypt) untuk backend, dan gunakan *Secure Storage* untuk di aplikasi *mobile*.
5. **CSRF Protection**: Pastikan form di aplikasi web terlindungi dari eksploitasi CSRF.
6. Apabila terdeteksi celah keamanan dari poin-poin di atas, **langsung perbaiki kodenya** sebelum memanggil `log-skill`.
