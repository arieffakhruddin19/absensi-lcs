name: code-review-skill
description: Melakukan validasi mandiri dan pembersihan kode sebelum penyelesaian task
license: MIT
compatibility: opencode
metadata:
  audience: developers

## What I do
Melakukan pengecekan *best-practice*, membersihkan *dead code* (variabel tak terpakai, perintah debug), dan memastikan standar kualitas terpenuhi secara otomatis sebelum menyatakan sebuah tugas selesai.

## When to use me
- Sesaat setelah menulis fitur baru.
- Setelah memperbaiki *bug*.
- Setiap kali akan mengakhiri sebuah pengerjaan *file* atau komponen.

## How I do it
Sebelum mengonfirmasi bahwa pekerjaan teknis telah selesai kepada user, AI WAJIB memvalidasi dan membereskan hal-hal berikut pada kode yang baru saja dimodifikasi:
1. **Pembersihan Debug**: Pastikan tidak tertinggal `console.log()`, `dd()`, `var_dump()`, atau `print_r()` yang dipakai selama fase eksperimen.
2. **Dead Code**: Hapus *import* library, *class*, atau *variable* yang tidak pernah dipanggil/digunakan.
3. **Hardcoding Guard**: Pastikan *credentials*, *API key*, atau URL *endpoint* tidak di-*hardcode* di dalam kode, melainkan mengambil dari *environment variables* (contoh: `.env`).
4. **Safety Check**: Berikan *error handling* (misal `try-catch`) pada kode krusial yang rentan *fail* (seperti *fetch request* ke API atau query *database*).
5. Jika ada pelanggaran, perbaiki kode tersebut terlebih dahulu secara otomatis. Setelah lolos validasi, barulah panggil `log-skill` untuk mencatat pekerjaan.
