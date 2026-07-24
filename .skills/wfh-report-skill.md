name: wfh-report-skill
description: Menghasilkan draf laporan WFH otomatis berdasarkan file progress.txt
license: MIT
compatibility: opencode
metadata:
  audience: maintainers

## What I do
Membaca file `progress.txt` harian dan menyusun draf Laporan WFH lengkap yang siap disalin ke Microsoft Word/dokumen pelaporan, meniru struktur template laporan yang biasa digunakan (termasuk mencakup ringkasan tugas dan aktivitas Zoom meeting jika diminta).

## When to use me
- Saat user meminta "Buatkan laporan WFH" atau "Drafting WFH Report".
- Di penghujung sesi kerja sebelum user menyelesaikan pekerjaannya pada hari tersebut.

## How I do it
1. Baca file `progress.txt` di root directory.
2. Cari dan ekstrak semua log pekerjaan yang tercatat pada tanggal hari ini (atau tanggal spesifik yang diminta user).
3. Ubah bahasa bullet points log teknis menjadi deskripsi profesional yang layak dibaca manajemen.
4. Format output akhir menggunakan struktur Markdown yang rapi:
   - Header (Judul & Tanggal).
   - Rincian Pengembangan / Bug Fixing.
   - Agenda Rapat / Koordinasi (berikan placeholder jika tidak ada data spesifik).
5. Berikan hasilnya langsung ke layar chat (sebagai *output*) kepada user. File `progress.txt` JANGAN diubah/dihapus isinya.
