<x-app-layout>
    <x-slot name="header">
        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen LCS') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- SweetAlert handles success and error messages now -->

                @php
                    $sumberText = 'Kementan';
                    if (isset($tab)) {
                        if ($tab == 'pkh') $sumberText = 'Ditjen PKH';
                        elseif ($tab == 'pusvetma') $sumberText = 'Pusvetma';
                    }
                @endphp
                <div class="flex flex-nowrap justify-between items-center w-full gap-2 mb-4">
                    <h3 id="dynamic-title" class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Link Postingan {{ $sumberText }}</h3>
                    
                    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="inline-block flex-shrink-0 whitespace-nowrap text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-xs px-3 py-1.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition ease-in-out duration-150" type="button">+ Tambah</button>
                </div>

                <!-- Filter Form -->
                <style>
                    .custom-filter-form { display: flex; flex-wrap: wrap; gap: 0.5rem; width: 100%; }
                    .custom-filter-perpage { flex: 1 1 20%; min-width: 70px; }
                    .custom-filter-tanggal { flex: 1 1 60%; min-width: 140px; }
                    .custom-filter-medsos { flex: 1 1 100%; }
                    .custom-filter-search { flex: 1 1 100%; }
                    
                    .aksi-buttons { display: flex; flex-direction: column; gap: 0.25rem; justify-content: center; align-items: stretch; }
                    .aksi-buttons > a, .aksi-buttons > button, .aksi-buttons > form { width: 100%; text-align: center; }
                    .aksi-buttons > form > button { width: 100%; }
                    
                    @media (min-width: 640px) {
                        .custom-filter-form { justify-content: flex-end; width: auto; }
                        .custom-filter-perpage { flex: 0 0 80px; }
                        .custom-filter-tanggal { flex: 0 0 160px; }
                        .custom-filter-medsos { flex: 0 0 160px; }
                        .custom-filter-search { flex: 0 0 260px; }
                        .aksi-buttons { flex-direction: row; align-items: center; justify-content: center; }
                        .aksi-buttons > a, .aksi-buttons > button, .aksi-buttons > form { width: auto; }
                    }
                </style>
                <div class="mb-4 flex justify-end w-full">
                    <form method="GET" action="{{ route('admin.posting.index') }}" class="custom-filter-form">
                        
                        <div class="custom-filter-perpage">
                            <select id="filter-perpage" name="per_page" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="10" {{ request('per_page', '10') == '10' ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>

                        <div class="custom-filter-tanggal" style="position: relative; display: flex; align-items: center;">
                            <input type="text" id="filter-tanggal" name="tanggal" value="{{ request('tanggal') }}" class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Pilih Tanggal..." title="Filter Tanggal">
                            <button type="button" onclick="document.getElementById('filter-tanggal')._flatpickr.clear(); window.triggerSearchGlobal();" class="text-gray-400 hover:text-gray-800 dark:hover:text-gray-200" title="Bersihkan tanggal" style="position: absolute; right: 10px;">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="custom-filter-medsos">
                            <select id="filter-medsos" name="medsos" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Semua Medsos</option>
                                <option value="Instagram" {{ request('medsos') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                                <option value="Facebook" {{ request('medsos') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                                <option value="Twitter" {{ request('medsos') == 'Twitter' ? 'selected' : '' }}>Twitter / X</option>
                                <option value="TikTok" {{ request('medsos') == 'TikTok' ? 'selected' : '' }}>TikTok</option>
                                <option value="YouTube" {{ request('medsos') == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                            </select>
                        </div>

                        <div class="custom-filter-search" style="position: relative;">
                            <div style="position: absolute; top: 50%; left: 12px; transform: translateY(-50%); pointer-events: none;">
                                <svg style="width: 16px; height: 16px; color: #9ca3af;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="text" id="livesearch-input" name="search" value="{{ request('search') }}" placeholder="Cari judul postingan..." style="padding-left: 36px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </form>
                </div>

                <div id="realtime-content">
                    <!-- Tabs -->
                    <div class="mb-4 flex justify-center sm:justify-start">
                        <ul class="flex flex-wrap justify-center gap-1 sm:gap-2 text-xs sm:text-sm font-medium text-center" role="tablist">
                            <li role="presentation">
                                <a href="#" onclick="event.preventDefault(); switchTab('pkh')" class="inline-block px-3 sm:px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm {{ (isset($tab) ? $tab : 'pkh') == 'pkh' ? 'text-white bg-blue-600 dark:bg-blue-500' : 'text-gray-600 bg-gray-100 border border-gray-200 hover:text-gray-900 hover:bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white' }}" role="tab">Ditjen PKH</a>
                            </li>
                            <li role="presentation">
                                <a href="#" onclick="event.preventDefault(); switchTab('pusvetma')" class="inline-block px-3 sm:px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm {{ (isset($tab) ? $tab : 'pkh') == 'pusvetma' ? 'text-white bg-blue-600 dark:bg-blue-500' : 'text-gray-600 bg-gray-100 border border-gray-200 hover:text-gray-900 hover:bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white' }}" role="tab">Pusvetma</a>
                            </li>
                            <li role="presentation">
                                <a href="#" onclick="event.preventDefault(); switchTab('kementan')" class="inline-block px-3 sm:px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm {{ (isset($tab) ? $tab : 'pkh') == 'kementan' ? 'text-white bg-blue-600 dark:bg-blue-500' : 'text-gray-600 bg-gray-100 border border-gray-200 hover:text-gray-900 hover:bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white' }}" role="tab">Kementan</a>
                            </li>
                        </ul>
                    </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center w-16">No</th>
                                <th scope="col" class="px-6 py-3 text-center">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Judul Postingan</th>
                                <th scope="col" class="px-6 py-3 text-center">Link Medsos</th>
                                <th scope="col" class="px-6 py-3 text-center">Progress</th>
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($postings as $index => $post)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 align-top text-center">{{ $postings->firstItem() + $index }}</td>
                                <td class="px-6 py-4 align-top whitespace-nowrap text-center">
                                    {{ $post->tanggal_tugas ? \Carbon\Carbon::parse($post->tanggal_tugas)->locale('id')->translatedFormat('d F Y') : $post->created_at->locale('id')->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 align-top text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $post->judul_tugas }}
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex flex-wrap justify-center gap-1">
                                        @if($post->link_instagram) <a href="{{ $post->link_instagram }}" target="_blank" class="bg-pink-100 text-pink-800 text-xs font-medium px-2 py-0.5 rounded">IG</a> @endif
                                        @if($post->link_facebook) <a href="{{ $post->link_facebook }}" target="_blank" class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">FB</a> @endif
                                        @if($post->link_twitter) <a href="{{ $post->link_twitter }}" target="_blank" class="bg-sky-200 text-sky-900 text-xs font-medium px-2 py-0.5 rounded">X</a> @endif
                                        @if($post->link_tiktok) <a href="{{ $post->link_tiktok }}" target="_blank" class="bg-black text-white text-xs font-medium px-2 py-0.5 rounded">TikTok</a> @endif
                                        @if($post->link_youtube) <a href="{{ $post->link_youtube }}" target="_blank" class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded">YT</a> @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <button type="button" onclick="showListPegawai('{{ route('admin.posting.list-pegawai', $post->id) }}', 'sudah', '{{ htmlspecialchars($post->judul_tugas, ENT_QUOTES) }}')" class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded border border-green-400 w-full whitespace-nowrap hover:bg-green-200 transition focus:outline-none focus:ring-2 focus:ring-green-400">Sudah: {{ $post->sudah_lcs_count }}</button>
                                        <button type="button" onclick="showListPegawai('{{ route('admin.posting.list-pegawai', $post->id) }}', 'belum', '{{ htmlspecialchars($post->judul_tugas, ENT_QUOTES) }}')" class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded border border-red-400 w-full whitespace-nowrap hover:bg-red-200 transition focus:outline-none focus:ring-2 focus:ring-red-400">Belum: {{ max(0, $totalPegawaiAktif - $post->sudah_lcs_count) }}</button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top aksi-buttons">
                                    <a href="{{ route('admin.posting.laporan', $post->id) }}" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded text-xs px-2 py-1 dark:bg-green-500 dark:hover:bg-green-600 focus:outline-none dark:focus:ring-green-800 transition">Laporan</a>
                                    <button data-modal-target="edit-modal-{{ $post->id }}" data-modal-toggle="edit-modal-{{ $post->id }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded text-xs px-2 py-1 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800 transition" type="button">Edit</button>
                                    <form action="{{ route('admin.posting.destroy', $post->id) }}" method="POST" class="inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded text-xs px-2 py-1 dark:bg-red-500 dark:hover:bg-red-600 focus:outline-none dark:focus:ring-red-900 transition" onclick="confirmDelete(this, 'Yakin ingin menghapus postingan ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center">Belum ada postingan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $postings->appends(request()->query())->links() }}
                </div>
                </div> <!-- End of #realtime-content -->

            </div>
        </div>
    </div>

    <!-- Main modal -->
    <div id="crud-modal" tabindex="-1" aria-hidden="true" data-modal-backdrop="static" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Tambah Postingan Baru
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Tutup</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('admin.posting.store') }}" method="POST" class="p-4 md:p-5" onsubmit="this.querySelector('button[type=submit]').disabled=true;this.querySelector('button[type=submit]').innerHTML='Menyimpan...'">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="judul_tugas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Postingan</label>
                            <input type="text" name="judul_tugas" id="judul_tugas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                        </div>
                        <div class="col-span-2">
                            <label for="tanggal_tugas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                            <input type="text" name="tanggal_tugas" id="tanggal_tugas" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" class="datepicker-today bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>
                        @php
                            $sumberDefault = 'Kementan';
                            if (isset($tab)) {
                                if ($tab == 'pkh') $sumberDefault = 'Ditjen PKH';
                                elseif ($tab == 'pusvetma') $sumberDefault = 'Pusvetma';
                            }
                        @endphp
                        <input type="hidden" name="sumber_posting" value="{{ $sumberDefault }}">
                        <div class="col-span-2 hidden">
                            <label for="batas_waktu" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Batas Waktu (Deadline)</label>
                            <input type="date" name="batas_waktu" id="batas_waktu" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>
                        
                        <div class="col-span-2 mt-2">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white border-b pb-1">Link Media Sosial (Opsional)</h4>
                            <p class="text-xs text-gray-500">Kosongkan link jika tidak ada instruksi di platform tersebut.</p>
                        </div>

                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-900 dark:text-gray-300">Instagram</label>
                            <input type="url" name="link_instagram" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-900 dark:text-gray-300">Facebook</label>
                            <input type="url" name="link_facebook" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-900 dark:text-gray-300">Twitter / X</label>
                            <input type="url" name="link_twitter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-900 dark:text-gray-300">TikTok</label>
                            <input type="url" name="link_tiktok" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-900 dark:text-gray-300">YouTube</label>
                            <input type="url" name="link_youtube" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                    </div>
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Simpan Postingan
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div id="modals-container">
    <!-- Edit Modals -->
    @foreach ($postings as $post)
    <div id="edit-modal-{{ $post->id }}" tabindex="-1" aria-hidden="true" data-modal-backdrop="static" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Edit LCS
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="edit-modal-{{ $post->id }}">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Tutup</span>
                    </button>
                </div>
                <form action="{{ route('admin.posting.update', $post->id) }}" method="POST" class="p-4 md:p-5" onsubmit="this.querySelector('button[type=submit]').disabled=true;this.querySelector('button[type=submit]').innerHTML='Menyimpan...'">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Postingan</label>
                            <input type="text" name="judul_tugas" value="{{ $post->judul_tugas }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                            <input type="text" name="tanggal_tugas" value="{{ $post->tanggal_tugas ? \Carbon\Carbon::parse($post->tanggal_tugas)->format('Y-m-d') : '' }}" class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <input type="hidden" name="sumber_posting" value="{{ $post->sumber_posting }}">
                        <div class="col-span-2 hidden">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Batas Waktu (Deadline)</label>
                            <input type="date" name="batas_waktu" value="{{ $post->batas_waktu ? \Carbon\Carbon::parse($post->batas_waktu)->format('Y-m-d') : '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        
                        <div class="col-span-2 mt-2">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white border-b pb-1">Link Media Sosial (Opsional)</h4>
                            <p class="text-xs text-gray-500">Kosongkan link jika tidak ada instruksi di platform tersebut.</p>
                        </div>

                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-900 dark:text-gray-300">Instagram</label>
                            <input type="url" name="link_instagram" value="{{ $post->link_instagram }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-900 dark:text-gray-300">Facebook</label>
                            <input type="url" name="link_facebook" value="{{ $post->link_facebook }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-900 dark:text-gray-300">Twitter / X</label>
                            <input type="url" name="link_twitter" value="{{ $post->link_twitter }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-900 dark:text-gray-300">TikTok</label>
                            <input type="url" name="link_tiktok" value="{{ $post->link_tiktok }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-900 dark:text-gray-300">YouTube</label>
                            <input type="url" name="link_youtube" value="{{ $post->link_youtube }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                    </div>
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
    
    <!-- List Pegawai Modal -->
    <div id="list-pegawai-modal" tabindex="-1" aria-hidden="true" data-modal-backdrop="static" class="hidden fixed inset-0 z-50 justify-center items-center">
        <div class="relative p-4 w-full max-w-md" style="max-height: 100vh; margin: auto;">
            <!-- Modal content -->
            <div id="list-pegawai-modal-content" class="relative bg-white rounded-lg shadow dark:bg-gray-700 flex flex-col">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600" style="flex-shrink: 0;">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="list-pegawai-modal-title">
                        Daftar Pegawai
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="closeListPegawaiModal()">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Tutup</span>
                    </button>
                </div>
                <!-- Modal Search Area -->
                <div class="p-4 md:p-5 border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700" style="flex-shrink: 0;">
                    <div class="mb-3 text-sm text-gray-500 dark:text-gray-400 font-medium line-clamp-2" id="list-pegawai-posting-title"></div>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" id="search-list-pegawai" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari nama pegawai...">
                    </div>
                </div>

                <!-- Modal body - this is the ONLY scrollable area -->
                <div id="list-pegawai-body" class="p-4 md:p-5" style="overflow-y: scroll; -webkit-overflow-scrolling: touch; touch-action: pan-y; overscroll-behavior: contain;">

                    <div id="list-pegawai-loading" class="text-center py-8 hidden">
                        <div role="status">
                            <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                            </svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <ul id="list-pegawai-container" class="space-y-1 select-none">
                        <!-- List will be populated here -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    </div>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize flatpickr for visible elements (filter, edit modals datepickers)
        window.initDatepickers = function() {
            // Filter tanggal (always visible)
            var filterEl = document.getElementById('filter-tanggal');
            if (filterEl && !filterEl._flatpickr) {
                flatpickr(filterEl, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d/m/Y",
                    allowInput: true,
                    onChange: function(selectedDates, dateStr, instance) {
                        if (window.triggerSearchGlobal) {
                            window.triggerSearchGlobal();
                        }
                    }
                });
            }

            // Edit modal datepickers (init when visible)
            document.querySelectorAll('.datepicker').forEach(function(el) {
                if (!el._flatpickr) {
                    flatpickr(el, {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "d/m/Y",
                        allowInput: true
                    });
                }
            });
        };

        // Initialize flatpickr for crud-modal WHEN it becomes visible
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('[data-modal-target="crud-modal"]');
            if (btn) {
                setTimeout(function() {
                    var el = document.getElementById('tanggal_tugas');
                    if (el && !el._flatpickr) {
                        flatpickr(el, {
                            dateFormat: "Y-m-d",
                            altInput: true,
                            altFormat: "d/m/Y",
                            allowInput: true,
                            defaultDate: "today"
                        });
                    }
                }, 150);
            }

            // Also handle edit modal open
            var editBtn = e.target.closest('[data-modal-target^="edit-modal-"]');
            if (editBtn) {
                var targetId = editBtn.getAttribute('data-modal-target');
                setTimeout(function() {
                    var modal = document.getElementById(targetId);
                    if (modal) {
                        modal.querySelectorAll('.datepicker').forEach(function(el) {
                            if (!el._flatpickr) {
                                flatpickr(el, {
                                    dateFormat: "Y-m-d",
                                    altInput: true,
                                    altFormat: "d/m/Y",
                                    allowInput: true
                                });
                            }
                        });
                    }
                }, 150);
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            window.initDatepickers();
        });
    </script>

    <script>
        // Manual backdrop karena Flowbite tidak auto-generate
        (function() {
            function createBackdrop() {
                if (document.getElementById('custom-modal-backdrop')) return;
                const backdrop = document.createElement('div');
                backdrop.id = 'custom-modal-backdrop';
                backdrop.style.cssText = 'position:fixed;inset:0;z-index:40;background:rgba(17,24,39,0.5);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);transition:opacity 0.2s;';
                document.body.appendChild(backdrop);
            }
            function removeBackdrop() {
                const backdrop = document.getElementById('custom-modal-backdrop');
                if (backdrop) backdrop.remove();
            }
            // Observe semua modal (crud-modal & edit-modal-*) untuk perubahan class
            const observer = new MutationObserver(function() {
                const anyModalOpen = document.querySelectorAll('[id$="-modal"]:not(.hidden)[tabindex="-1"], [id^="edit-modal-"]:not(.hidden)[tabindex="-1"]');
                let found = false;
                anyModalOpen.forEach(el => {
                    if (!el.classList.contains('hidden')) found = true;
                });
                if (found) {
                    createBackdrop();
                } else {
                    removeBackdrop();
                }
            });
            document.addEventListener('DOMContentLoaded', function() {
                observer.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['class'] });
            });
        })();
    </script>

    <x-realtime-sync type="posting" />

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert for Session Success
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: {!! json_encode(session('success')) !!},
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        // SweetAlert for Session Errors
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '<ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        // SweetAlert for Delete Confirmation
        function confirmDelete(button, message) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }
    </script>

    <!-- Live Search & Pagination Script -->
    <script>
        let typingTimer;
        const doneTypingInterval = 500;
        
        window.switchTab = function(tabName) {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabName);
            url.searchParams.delete('page');
            
            // Update crud-modal hidden input
            const crudSumber = document.querySelector('#crud-modal input[name="sumber_posting"]');
            if (crudSumber) {
                if (tabName === 'pkh') crudSumber.value = 'Ditjen PKH';
                else if (tabName === 'pusvetma') crudSumber.value = 'Pusvetma';
                else crudSumber.value = 'Kementan';
            }
            
            window.triggerSearch(url.href);
        };
        
        window.triggerSearch = function(targetUrl = null) {
            const searchInput = document.getElementById('livesearch-input');
            const filterTanggal = document.getElementById('filter-tanggal');
            const filterMedsos = document.getElementById('filter-medsos');
            const filterPerPage = document.getElementById('filter-perpage');
            let url;
            
            if (targetUrl) {
                url = new URL(targetUrl, window.location.origin);
            } else {
                url = new URL(window.location.href);
                if (searchInput && searchInput.value.trim() !== '') {
                    url.searchParams.set('search', searchInput.value);
                } else {
                    url.searchParams.delete('search');
                }
                
                if (filterTanggal && filterTanggal.value !== '') {
                    url.searchParams.set('tanggal', filterTanggal.value);
                } else {
                    url.searchParams.delete('tanggal');
                }

                if (filterMedsos && filterMedsos.value !== '') {
                    url.searchParams.set('medsos', filterMedsos.value);
                } else {
                    url.searchParams.delete('medsos');
                }

                if (filterPerPage && filterPerPage.value !== '') {
                    url.searchParams.set('per_page', filterPerPage.value);
                } else {
                    url.searchParams.delete('per_page');
                }
                
                url.searchParams.delete('page');
            }
            
            window.history.pushState({}, '', url);
            
            const contentDiv = document.querySelector('#realtime-content');
            if (contentDiv) {
                contentDiv.style.transition = 'opacity 0.2s ease-in-out';
                contentDiv.style.opacity = '0.4';
                contentDiv.style.pointerEvents = 'none';
            }
            
            fetch(url.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Cache-Control': 'no-cache',
                    'Pragma': 'no-cache'
                }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('#realtime-content');
                if (newContent && contentDiv) {
                    contentDiv.innerHTML = newContent.innerHTML;
                    contentDiv.style.opacity = '1';
                    contentDiv.style.pointerEvents = 'auto';
                    
                    const newModals = doc.querySelector('#modals-container');
                    const modalsDiv = document.querySelector('#modals-container');
                    if (newModals && modalsDiv) {
                        modalsDiv.innerHTML = newModals.innerHTML;
                    }
                    
                    try {
                        if (typeof initFlowbite === 'function') {
                            initFlowbite();
                        } else if (typeof initModals === 'function') {
                            initModals();
                        }
                    } catch(e) { console.error('initFlowbite error:', e); }
                    
                    try {
                        if (typeof window.initDatepickers === 'function') {
                            setTimeout(() => { window.initDatepickers(); }, 50);
                        }
                    } catch(e) { console.error('initDatepickers error:', e); }
                    
                    const newTitle = doc.querySelector('#dynamic-title');
                    const currentTitle = document.querySelector('#dynamic-title');
                    if (newTitle && currentTitle) {
                        currentTitle.innerHTML = newTitle.innerHTML;
                    }
                }
            })
            .catch(err => {
                console.error('Live search error:', err);
                if (contentDiv) {
                    contentDiv.style.opacity = '1';
                    contentDiv.style.pointerEvents = 'auto';
                }
            });
        };
        
        window.triggerSearchGlobal = window.triggerSearch;

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('livesearch-input');
            const filterTanggal = document.getElementById('filter-tanggal');
            const filterMedsos = document.getElementById('filter-medsos');
            const filterPerPage = document.getElementById('filter-perpage');
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(() => window.triggerSearch(), doneTypingInterval);
                });
            }
            if (filterMedsos) filterMedsos.addEventListener('change', () => window.triggerSearch());
            if (filterPerPage) filterPerPage.addEventListener('change', () => window.triggerSearch());
        });

        // Intercept Pagination Clicks
        document.addEventListener('click', function(e) {
            const paginationLink = e.target.closest('#realtime-content nav[role="navigation"] a');
            if (paginationLink && paginationLink.href) {
                e.preventDefault();
                window.triggerSearch(paginationLink.href);
            }
        });
        
        let allPegawaiData = [];
        window.showListPegawai = function(url, status, judulTugas) {
            const modal = document.getElementById('list-pegawai-modal');
            const title = document.getElementById('list-pegawai-modal-title');
            const subtitle = document.getElementById('list-pegawai-posting-title');
            const container = document.getElementById('list-pegawai-container');
            const loading = document.getElementById('list-pegawai-loading');
            const searchInput = document.getElementById('search-list-pegawai');
            
            title.textContent = 'Pegawai ' + (status === 'sudah' ? 'Sudah LCS' : 'Belum LCS');
            title.className = 'text-lg font-semibold ' + (status === 'sudah' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400');
            subtitle.textContent = judulTugas;
            
            container.innerHTML = '';
            searchInput.value = '';
            container.classList.add('hidden');
            loading.classList.remove('hidden');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            
            // Calculate body height dynamically using actual pixel values
            // This fixes mobile browsers where vh units are unreliable
            setTimeout(function() {
                var modalContent = document.getElementById('list-pegawai-modal-content');
                var bodyDiv = document.getElementById('list-pegawai-body');
                var windowHeight = window.innerHeight;
                var maxModalHeight = Math.floor(windowHeight * 0.85);
                modalContent.style.maxHeight = maxModalHeight + 'px';
                
                // Calculate remaining height for body after header and search
                var headerHeight = modalContent.querySelector('.border-b.rounded-t') ? modalContent.querySelector('.border-b.rounded-t').offsetHeight : 0;
                var searchHeight = document.getElementById('list-pegawai-posting-title').closest('.border-b.border-gray-200') ? document.getElementById('list-pegawai-posting-title').closest('.border-b.border-gray-200').offsetHeight : 0;
                var remainingHeight = maxModalHeight - headerHeight - searchHeight - 20;
                bodyDiv.style.maxHeight = Math.max(remainingHeight, 150) + 'px';
            }, 50);
            
            if (typeof createBackdrop === 'function') createBackdrop();
            else {
                const backdrop = document.createElement('div');
                backdrop.id = 'list-pegawai-backdrop';
                backdrop.style.cssText = 'position:fixed;inset:0;z-index:40;background:rgba(17,24,39,0.5);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);transition:opacity 0.2s;';
                document.body.appendChild(backdrop);
            }
            
            fetch(`${url}?status=${status}`)
                .then(res => res.json())
                .then(data => {
                    allPegawaiData = data.data || [];
                    renderPegawaiList(allPegawaiData);
                    loading.classList.add('hidden');
                    container.classList.remove('hidden');
                })
                .catch(err => {
                    console.error(err);
                    loading.classList.add('hidden');
                    container.innerHTML = '<li class="p-2 text-center text-sm text-red-500">Gagal memuat data.</li>';
                    container.classList.remove('hidden');
                });
        };
        
        window.closeListPegawaiModal = function() {
            const modal = document.getElementById('list-pegawai-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
            
            if (typeof removeBackdrop === 'function') removeBackdrop();
            const bd = document.getElementById('list-pegawai-backdrop');
            if (bd) bd.remove();
        };
        
        function renderPegawaiList(data) {
            const container = document.getElementById('list-pegawai-container');
            container.innerHTML = '';
            
            if (data.length === 0) {
                container.innerHTML = '<li class="p-2 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data pegawai.</li>';
                return;
            }
            
            data.forEach((pegawai, index) => {
                const li = document.createElement('li');
                li.className = 'px-3 py-2 border-b border-gray-100 dark:border-gray-700 last:border-0 text-sm text-gray-700 dark:text-gray-200 flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition rounded-md select-none';
                li.innerHTML = `<span class="font-medium text-gray-500 dark:text-gray-400 w-5 text-right flex-shrink-0">${index + 1}.</span> <span>${pegawai.nama_pegawai}</span>`;
                container.appendChild(li);
            });
        }
        
        document.getElementById('search-list-pegawai').addEventListener('input', function(e) {
            const keyword = e.target.value.toLowerCase();
            const filtered = allPegawaiData.filter(p => p.nama_pegawai.toLowerCase().includes(keyword));
            renderPegawaiList(filtered);
        });
        

    </script>
</x-app-layout>
