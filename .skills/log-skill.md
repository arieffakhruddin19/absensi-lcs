name: log-skill
description: Mencatat log dari rencana pekerjaan sebelum diimplementasikan, serta pekerjaan yang sudah selesai ke dalam progress.txt
license: MIT
compatibility: opencode
metadata:
  audience: maintainers

## What I do
Mencatat rencana pekerjaan SEBELUM diimplementasikan, serta setiap perubahan kode, pembuatan file, refactoring, atau perbaikan bug ke dalam file `progress.txt` di root directory.

## When to use me
- SEBELUM mulai mengimplementasikan fitur, task, atau perbaikan bug (sebagai rencana).
- Setelah selesai membuat file baru.
- Setelah melakukan refactor kode.
- Setelah menyelesaikan sebuah task.
- Setelah memperbaiki bug.
- Setiap kali ada perubahan code yang signifikan sebelum mengakhiri sesi.

## How I do it
1. Baca file `progress.txt` di root directory untuk memahami konteks log terakhir. Jika file belum ada, buat filenya.
2. Cek apakah header untuk tanggal hari ini (format: `### YYYY-MM-DD`) sudah ada. Jika belum, tambahkan di bagian paling bawah.
3. Tambahkan entry log baru di bawah header tanggal hari ini menggunakan bullet points (`-`).
4. Gunakan format standar: `**[AKSI]** nama_file - Deskripsi singkat namun jelas`.
   * Pilihan AKSI: `Plan`, `Created`, `Modified`, `Fixed`, `Deleted`, `Refactored`.
   * Gunakan aksi `Plan` untuk mencatat rencana sebelum mulai *coding*.
5. **CRITICAL:** Selalu tambahkan (APPEND) log baru di bagian bawah. JANGAN PERNAH menimpa (OVERWRITE) atau menghapus log sebelumnya yang sudah ada di dalam file.
