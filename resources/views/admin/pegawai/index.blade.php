<x-app-layout>
    <x-slot name="header">
        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- SweetAlert handles success and error messages now -->

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Pegawai</h3>
                    
                    <!-- Modal toggle -->
                    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-xs px-3 py-1.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition ease-in-out duration-150" type="button">
                      + Tambah Pegawai
                    </button>
                </div>

                <!-- Filters and Live Search Form -->
                <div class="mb-4 flex justify-between items-center">
                    <!-- Status Filter -->
                    <div class="flex items-center space-x-2">
                        <label for="status-filter" class="text-sm font-medium text-gray-900 dark:text-gray-300">Status:</label>
                        <select id="status-filter" name="status" onchange="triggerSearch()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="aktif" {{ request('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="pensiun" {{ request('status') == 'pensiun' ? 'selected' : '' }}>Pensiun</option>
                            <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>

                    <!-- Search Input -->
                    <div style="position: relative; width: 320px;">
                        <div style="position: absolute; top: 50%; left: 12px; transform: translateY(-50%); pointer-events: none;">
                            <svg style="width: 16px; height: 16px; color: #9ca3af;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" id="livesearch-input" name="search" value="{{ request('search') }}" placeholder="Cari NIP atau Nama..." style="padding-left: 36px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                </div>

                <div id="realtime-content">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center w-16">No</th>
                                <th scope="col" class="px-6 py-3">NIP / Username</th>
                                <th scope="col" class="px-6 py-3">Nama Pegawai</th>
                                <th scope="col" class="px-6 py-3 text-center">TMT</th>
                                <th scope="col" class="px-6 py-3 text-center">Tanggal Pensiun</th>
                                <th scope="col" class="px-6 py-3 text-center">Status</th>
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pegawais as $index => $pegawai)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 text-center">{{ $pegawais->firstItem() + $index }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $pegawai->nip }}
                                </td>
                                <td class="px-6 py-4">{{ $pegawai->nama_pegawai }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">{{ $pegawai->tmt ? \Carbon\Carbon::parse($pegawai->tmt)->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">{{ $pegawai->tanggal_pensiun ? \Carbon\Carbon::parse($pegawai->tanggal_pensiun)->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $today = \Carbon\Carbon::now()->startOfDay();
                                        $pensiunDate = $pegawai->tanggal_pensiun ? \Carbon\Carbon::parse($pegawai->tanggal_pensiun)->startOfDay() : null;
                                        $isPensiun = $pensiunDate && $today->gt($pensiunDate);
                                    @endphp
                                    @if($isPensiun)
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Pensiun</span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex items-center justify-center space-x-2">
                                    <button data-modal-target="edit-modal-{{ $pegawai->id }}" data-modal-toggle="edit-modal-{{ $pegawai->id }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-xs px-3 py-1.5 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800 transition" type="button">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.pegawai.reset-password', $pegawai->id) }}" method="POST" class="inline m-0">
                                        @csrf
                                        <button type="button" class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 font-medium rounded-md text-xs px-3 py-1.5 dark:bg-yellow-500 dark:hover:bg-yellow-600 focus:outline-none dark:focus:ring-yellow-800 transition whitespace-nowrap" onclick="confirmReset(this, 'Yakin ingin mereset password pegawai ini menjadi 12345678?')">Reset Pass</button>
                                    </form>
                                    <form action="{{ route('admin.pegawai.destroy', $pegawai->id) }}" method="POST" class="inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-md text-xs px-3 py-1.5 dark:bg-red-500 dark:hover:bg-red-600 focus:outline-none dark:focus:ring-red-900 transition" onclick="confirmDelete(this, 'Yakin ingin menghapus pegawai ini beserta akun login-nya?')">Hapus</button>
                                    </form>

                                    <!-- Edit Modal -->
                                    <div id="edit-modal-{{ $pegawai->id }}" tabindex="-1" aria-hidden="true" data-modal-backdrop="static" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-md max-h-full">
                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                        Edit Data Pegawai
                                                    </h3>
                                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="edit-modal-{{ $pegawai->id }}">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                        </svg>
                                                        <span class="sr-only">Tutup</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.pegawai.update', $pegawai->id) }}" method="POST" class="p-4 md:p-5 text-left">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                                        <div class="col-span-2">
                                                            <label for="nip_{{ $pegawai->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIP</label>
                                                            <input type="text" name="nip" id="nip_{{ $pegawai->id }}" value="{{ $pegawai->nip }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                                                        </div>
                                                        <div class="col-span-2">
                                                            <label for="nama_pegawai_{{ $pegawai->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Pegawai</label>
                                                            <input type="text" name="nama_pegawai" id="nama_pegawai_{{ $pegawai->id }}" value="{{ $pegawai->nama_pegawai }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                                                        </div>
                                                        <div class="col-span-2">
                                                            <label for="tmt_{{ $pegawai->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">TMT</label>
                                                            <input type="text" name="tmt" id="tmt_{{ $pegawai->id }}" value="{{ $pegawai->tmt }}" class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                        </div>
                                                        <div class="col-span-2">
                                                            <label for="tanggal_pensiun_{{ $pegawai->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Pensiun</label>
                                                            <input type="text" name="tanggal_pensiun" id="tanggal_pensiun_{{ $pegawai->id }}" value="{{ $pegawai->tanggal_pensiun }}" class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                        Simpan Perubahan
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center">Belum ada data pegawai.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $pegawais->links() }}
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
                        Tambah Pegawai Baru
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Tutup</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('admin.pegawai.store') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="nip" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIP</label>
                            <input type="text" name="nip" id="nip" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Masukkan NIP" required="">
                        </div>
                        <div class="col-span-2">
                            <label for="nama_pegawai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Pegawai</label>
                            <input type="text" name="nama_pegawai" id="nama_pegawai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nama Lengkap" required="">
                        </div>
                        <div class="col-span-2">
                            <label for="tmt" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">TMT</label>
                            <input type="text" name="tmt" id="tmt" class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Pilih TMT">
                        </div>
                        <div class="col-span-2">
                            <label for="tanggal_pensiun" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Pensiun</label>
                            <input type="text" name="tanggal_pensiun" id="tanggal_pensiun" class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Pilih Tanggal Pensiun">
                        </div>
                    </div>
                    <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                        *Catatan: Akun login akan dibuat otomatis.<br>
                        Username: <b>[Sesuai NIP]</b><br>
                        Password: <b>12345678</b>
                    </div>
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Simpan Pegawai
                    </button>
                </form>
            </div>
        </div>
    </div>
    <x-realtime-sync type="pegawai" />

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
                    // Submit the closest form
                    button.closest('form').submit();
                }
            });
        }

        function confirmReset(button, message) {
            Swal.fire({
                title: 'Konfirmasi Reset Password',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#eab308',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }
    </script>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d", // Data dikirim ke server dalam format ini
                altInput: true,
                altFormat: "d/m/Y", // Tampilan untuk user
                allowInput: true
            });
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

    <!-- Live Search, Filter & Pagination Script -->
    <script>
        let typingTimer;
        const doneTypingInterval = 500;
        
        window.triggerSearch = function(targetUrl = null) {
            const searchInput = document.getElementById('livesearch-input');
            const statusFilter = document.getElementById('status-filter');
            let url;
            
            if (targetUrl) {
                url = new URL(targetUrl);
            } else {
                url = new URL(window.location.href);
                if (searchInput && searchInput.value.trim() !== '') {
                    url.searchParams.set('search', searchInput.value);
                } else {
                    url.searchParams.delete('search');
                }
                
                if (statusFilter && statusFilter.value) {
                    url.searchParams.set('status', statusFilter.value);
                }
                // Reset page on search/filter only if not clicking pagination
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
                    
                    // Re-initialize Flowbite modals for the newly injected HTML
                    if (typeof initFlowbite === 'function') {
                        initFlowbite();
                    } else if (typeof initModals === 'function') {
                        initModals();
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

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('livesearch-input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(() => window.triggerSearch(), doneTypingInterval);
                });
            }
        });

        // Intercept Pagination Clicks
        document.addEventListener('click', function(e) {
            const paginationLink = e.target.closest('#realtime-content nav[role="navigation"] a');
            if (paginationLink && paginationLink.href) {
                e.preventDefault();
                window.triggerSearch(paginationLink.href);
            }
        });
    </script>
</x-app-layout>
