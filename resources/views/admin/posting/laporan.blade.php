<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Absensi LCS') }}
            </h2>
            @php
                $tabMap = [
                    'Kementan' => 'kementan',
                    'Ditjen PKH' => 'pkh',
                    'Pusvetma' => 'pusvetma'
                ];
                $backTab = $tabMap[$posting->sumber_posting ?? 'Kementan'] ?? 'kementan';
            @endphp
            <a href="{{ route('admin.posting.index', ['tab' => $backTab]) }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Info Postingan -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <h3 class="text-lg font-bold text-blue-900 mb-1">{{ $posting->judul_tugas }}</h3>
                    <div class="flex flex-wrap items-center gap-3 text-sm text-blue-800 mb-3" style="row-gap: 4px;">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.949 8.949 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            <span class="font-medium">{{ $posting->sumber_posting }}</span>
                        </div>
                        <span class="text-blue-300">•</span>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($posting->tanggal_tugas)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if($posting->link_instagram) <a href="{{ $posting->link_instagram }}" target="_blank" class="bg-pink-100 text-pink-800 text-xs font-medium px-2 py-0.5 rounded hover:bg-pink-200">IG</a> @endif
                        @if($posting->link_facebook) <a href="{{ $posting->link_facebook }}" target="_blank" class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded hover:bg-blue-200">FB</a> @endif
                        @if($posting->link_twitter) <a href="{{ $posting->link_twitter }}" target="_blank" class="bg-sky-200 text-sky-900 text-xs font-medium px-2 py-0.5 rounded hover:bg-sky-300">X</a> @endif
                        @if($posting->link_tiktok) <a href="{{ $posting->link_tiktok }}" target="_blank" class="bg-black text-white text-xs font-medium px-2 py-0.5 rounded hover:bg-gray-800">TikTok</a> @endif
                        @if($posting->link_youtube) <a href="{{ $posting->link_youtube }}" target="_blank" class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded hover:bg-red-200">YT</a> @endif
                    </div>
                </div>

                <style>
                    .filter-container {
                        display: flex;
                        justify-content: flex-end;
                        align-items: center;
                        gap: 10px;
                        flex-wrap: wrap;
                    }
                    .filter-group {
                        display: flex;
                        gap: 10px;
                    }
                    .filter-perpage {
                        width: 80px;
                    }
                    .filter-status {
                        width: 150px;
                    }
                    .filter-search {
                        width: 320px;
                        position: relative;
                    }
                    @media (max-width: 768px) {
                        .filter-container {
                            flex-direction: column;
                            align-items: stretch;
                        }
                        .filter-group {
                            width: 100%;
                        }
                        .filter-perpage {
                            flex: 1;
                        }
                        .filter-status {
                            flex: 2;
                        }
                        .filter-search {
                            width: 100%;
                        }
                    }
                </style>
                <!-- Live Search & Filter Form -->
                <div class="mb-4 filter-container">
                    <div class="filter-group">
                        <!-- Per Page Filter -->
                        <div class="filter-perpage">
                            <select id="perpage-filter" name="per_page" onchange="triggerSearch()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                                <option value="15" {{ request('per_page', 15) == '15' ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="filter-status">
                            <select id="status-filter" name="status" onchange="triggerSearch()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                <option value="sudah" {{ request('status') == 'sudah' ? 'selected' : '' }}>Sudah Selesai</option>
                                <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-search">
                        <div style="position: absolute; top: 50%; left: 12px; transform: translateY(-50%); pointer-events: none;">
                            <svg style="width: 16px; height: 16px; color: #9ca3af;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" id="livesearch-input" name="search" value="{{ request('search') }}" placeholder="Ketik nama untuk mencari..." style="padding-left: 36px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                </div>

                <!-- Area yang akan di-update oleh Live Search & Realtime Sync -->
                <div id="realtime-content">
                    <!-- Tabel Laporan Pegawai -->
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Nama Pegawai</th>
                                @if($posting->link_instagram)<th scope="col" class="px-6 py-3 text-center">IG</th>@endif
                                @if($posting->link_facebook)<th scope="col" class="px-6 py-3 text-center">FB</th>@endif
                                @if($posting->link_twitter)<th scope="col" class="px-6 py-3 text-center">X</th>@endif
                                @if($posting->link_tiktok)<th scope="col" class="px-6 py-3 text-center">TikTok</th>@endif
                                @if($posting->link_youtube)<th scope="col" class="px-6 py-3 text-center">YT</th>@endif
                                <th scope="col" class="px-6 py-3 text-center">{{ auth()->user()->role === 'superadmin' ? 'Status / Aksi' : 'Status' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pegawais as $index => $pegawai)
                                @php
                                    $abs = $absensiRecords[$pegawai->id] ?? null;
                                    $isSelesai = $abs && $abs->status_selesai;
                                    $waktu = $abs && $abs->waktu_dikerjakan ? \Carbon\Carbon::parse($abs->waktu_dikerjakan)->format('d M Y, H:i') : '-';
                                @endphp
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $pegawais->firstItem() + $index }}</td>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $pegawai->nama_pegawai }}
                                    </th>
                                    @foreach(['ig' => $posting->link_instagram, 'fb' => $posting->link_facebook, 'tw' => $posting->link_twitter, 'tt' => $posting->link_tiktok, 'yt' => $posting->link_youtube] as $platKey => $hasPlat)
                                        @if($hasPlat)
                                            @php
                                                $waktuMap = ['ig' => 'instagram', 'fb' => 'facebook', 'tw' => 'twitter', 'tt' => 'tiktok', 'yt' => 'youtube'];
                                                $waktuField = 'waktu_' . $waktuMap[$platKey];
                                                $hasWaktu = $abs && $abs->$waktuField;
                                            @endphp
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex gap-4 justify-center items-center" title="{{ $hasWaktu ? \Carbon\Carbon::parse($abs->$waktuField)->format('d M Y, H:i') : 'Belum' }}">
                                                    @foreach(['like' => 'L', 'comment' => 'C', 'share' => 'S'] as $action => $label)
                                                        @php
                                                            $field = $platKey . '_' . $action;
                                                            $isChecked = $abs && $abs->$field;
                                                        @endphp
                                                        @if(auth()->user()->role === 'superadmin')
                                                        <button type="button" 
                                                            id="btn-lcs-{{ $pegawai->id }}-{{ $platKey }}-{{ $action }}"
                                                            class="flex items-center gap-1 cursor-pointer hover:opacity-70 transition-opacity" 
                                                            onclick="toggleLcs({{ $posting->id }}, {{ $pegawai->id }}, '{{ $platKey }}', '{{ $action }}', {{ $isChecked ? 'true' : 'false' }})">
                                                            <span class="font-bold text-sm" style="{{ $isChecked ? 'color: #16a34a;' : 'color: #6b7280;' }}">{{ $label }}</span>
                                                            @if($isChecked)
                                                                <svg class="w-4 h-4" fill="#16a34a" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                            @else
                                                                <svg class="w-4 h-4" fill="#dc2626" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                                            @endif
                                                        </button>
                                                        @else
                                                        <div class="flex items-center gap-1">
                                                            <span class="font-bold text-sm" style="{{ $isChecked ? 'color: #16a34a;' : 'color: #6b7280;' }}">{{ $label }}</span>
                                                            @if($isChecked)
                                                                <svg class="w-4 h-4" fill="#16a34a" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                            @else
                                                                <svg class="w-4 h-4" fill="#dc2626" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                                            @endif
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach
                                    <td class="px-6 py-4 text-center">
                                        @if($isSelesai)
                                            @if($abs && $abs->diselesaikan_oleh_admin)
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300" title="Diselesaikan oleh Admin">
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">
                                                    Selesai
                                                </span>
                                            @endif
                                            <div class="text-xs text-gray-500 mt-1">{{ $waktu }}</div>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">
                                                Belum
                                            </span>
                                            @if(auth()->user()->role === 'superadmin')
                                            <div class="mt-2">
                                                <button type="button" 
                                                    onclick="selesaikanLcs({{ $posting->id }}, {{ $pegawai->id }})" 
                                                    class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-md text-xs px-3 py-1 transition">
                                                    Selesaikan
                                                </button>
                                            </div>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-4 text-center text-gray-500">Belum ada data pegawai terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $pegawais->appends(request()->query())->links() }}
                </div>
                </div> <!-- End of #realtime-content -->

            </div>
        </div>
    </div>

    <!-- Live Search & Pagination Script -->
    <script>
        let typingTimer;
        const doneTypingInterval = 500;
        
        window.triggerSearch = function(targetUrl = null) {
            const searchInput = document.getElementById('livesearch-input');
            const statusFilter = document.getElementById('status-filter');
            const perPageFilter = document.getElementById('perpage-filter');
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
                
                if (statusFilter && statusFilter.value && statusFilter.value !== 'semua') {
                    url.searchParams.set('status', statusFilter.value);
                } else {
                    url.searchParams.delete('status');
                }

                if (perPageFilter && perPageFilter.value) {
                    url.searchParams.set('per_page', perPageFilter.value);
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
                    
                    if (typeof initFlowbite === 'function') {
                        initFlowbite();
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

    <!-- Superadmin LCS AJAX Scripts -->
    @if(auth()->user()->role === 'superadmin')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleLcs(postingId, pegawaiId, platform, action, currentState) {
            const newState = !currentState;
            const btn = document.getElementById(`btn-lcs-${pegawaiId}-${platform}-${action}`);
            if (btn) {
                btn.style.opacity = '0.4';
                btn.style.pointerEvents = 'none';
            }

            const url = `{{ url('admin/posting') }}/${postingId}/tandai-medsos/${pegawaiId}`;
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    platform: platform,
                    action: action,
                    is_checked: newState
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Refresh tabel via triggerSearch
                    window.triggerSearch();
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, timer: 2000, showConfirmButton: false });
                    if (btn) { btn.style.opacity = '1'; btn.style.pointerEvents = 'auto'; }
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.', timer: 2000, showConfirmButton: false });
                if (btn) { btn.style.opacity = '1'; btn.style.pointerEvents = 'auto'; }
            });
        }

        function selesaikanLcs(postingId, pegawaiId) {
            Swal.fire({
                title: 'Selesaikan tugas?',
                text: 'Tandai tugas ini sebagai selesai untuk pegawai ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = `{{ url('admin/posting') }}/${postingId}/selesaikan-lcs/${pegawaiId}`;
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 1500, showConfirmButton: false });
                            window.triggerSearch();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, timer: 2000, showConfirmButton: false });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.', timer: 2000, showConfirmButton: false });
                    });
                }
            });
        }
    </script>
    @endif

    <x-realtime-sync type="laporan" />
</x-app-layout>
