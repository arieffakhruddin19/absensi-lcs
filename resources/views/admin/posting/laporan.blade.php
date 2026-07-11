<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Absensi Tugas LCS') }}
            </h2>
            <a href="{{ route('admin.posting.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Kembali ke Daftar</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Info Postingan -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <h3 class="text-lg font-bold text-blue-900 mb-1">{{ $posting->judul_tugas }}</h3>
                    <div class="flex gap-2 mt-2">
                        @if($posting->link_instagram) <a href="{{ $posting->link_instagram }}" target="_blank" class="bg-pink-100 text-pink-800 text-xs font-medium px-2 py-0.5 rounded hover:bg-pink-200">IG</a> @endif
                        @if($posting->link_facebook) <a href="{{ $posting->link_facebook }}" target="_blank" class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded hover:bg-blue-200">FB</a> @endif
                        @if($posting->link_twitter) <a href="{{ $posting->link_twitter }}" target="_blank" class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded hover:bg-gray-200">X</a> @endif
                        @if($posting->link_tiktok) <a href="{{ $posting->link_tiktok }}" target="_blank" class="bg-black text-white text-xs font-medium px-2 py-0.5 rounded hover:bg-gray-800">TikTok</a> @endif
                        @if($posting->link_youtube) <a href="{{ $posting->link_youtube }}" target="_blank" class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded hover:bg-red-200">YT</a> @endif
                    </div>
                </div>

                <!-- Tabel Laporan Pegawai -->
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Nama Pegawai</th>
                                <th scope="col" class="px-6 py-3">Divisi</th>
                                <th scope="col" class="px-6 py-3 text-center">Status Pengerjaan</th>
                                <th scope="col" class="px-6 py-3 text-center">Waktu Dikerjakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pegawais as $index => $pegawai)
                                @php
                                    $isSelesai = isset($absensi[$pegawai->id]) && $absensi[$pegawai->id] == 1;
                                    $waktu = isset($waktuSelesai[$pegawai->id]) ? \Carbon\Carbon::parse($waktuSelesai[$pegawai->id])->format('d M Y, H:i') : '-';
                                @endphp
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $pegawai->nama_pegawai }}
                                    </th>
                                    <td class="px-6 py-4">{{ $pegawai->divisi }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($isSelesai)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">
                                                Belum
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{ $waktu }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data pegawai terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
