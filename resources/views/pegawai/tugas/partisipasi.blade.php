<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Partisipasi LCS Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Berikut adalah daftar peringkat partisipasi pegawai dalam mengerjakan tugas LCS (Like, Comment, Share), diurutkan berdasarkan jumlah LCS terbanyak.
                        </p>
                    </div>

                    <style>
                        .custom-filter-form { display: flex; flex-wrap: wrap; gap: 0.5rem; width: 100%; margin-bottom: 1rem; justify-content: flex-end; }
                        .custom-filter-tanggal { flex: 1 1 40%; min-width: 140px; }
                        .custom-filter-search { flex: 1 1 100%; }
                        
                        @media (min-width: 640px) {
                            .custom-filter-tanggal { flex: 0 0 160px; }
                            .custom-filter-search { flex: 0 0 260px; }
                        }
                    </style>
                    <div class="flex justify-end w-full">
                        <form method="GET" action="{{ route('tugas.partisipasi') }}" class="custom-filter-form">
                            <div class="custom-filter-tanggal" style="position: relative; display: flex; align-items: center;">
                                <input type="text" id="filter-start-date" name="start_date" value="{{ request('start_date') }}" class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tanggal Mulai..." title="Tanggal Mulai">
                                <button type="button" onclick="document.getElementById('filter-start-date')._flatpickr.clear(); triggerSearch();" class="text-gray-400 hover:text-gray-800 dark:hover:text-gray-200" title="Bersihkan tanggal mulai" style="position: absolute; right: 10px;">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <div class="custom-filter-tanggal" style="position: relative; display: flex; align-items: center;">
                                <input type="text" id="filter-end-date" name="end_date" value="{{ request('end_date') }}" class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tanggal Akhir..." title="Tanggal Akhir">
                                <button type="button" onclick="document.getElementById('filter-end-date')._flatpickr.clear(); triggerSearch();" class="text-gray-400 hover:text-gray-800 dark:hover:text-gray-200" title="Bersihkan tanggal akhir" style="position: absolute; right: 10px;">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <div class="custom-filter-search" style="position: relative;">
                                <div style="position: absolute; top: 50%; left: 12px; transform: translateY(-50%); pointer-events: none;">
                                    <svg style="width: 16px; height: 16px; color: #9ca3af;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input type="text" id="livesearch-input" name="search" value="{{ request('search') }}" placeholder="Cari Nama Pegawai..." style="padding-left: 36px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                        </form>
                    </div>
                    
                    <div id="realtime-content">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-2 py-3 text-center border-r border-gray-200 dark:border-gray-700" rowspan="2">
                                        No.
                                    </th>
                                    <th scope="col" class="px-4 py-3 border-r border-gray-200 dark:border-gray-700" rowspan="2">
                                        Nama Pegawai
                                    </th>
                                    <th scope="col" class="px-2 py-2 text-center border-b border-r border-gray-200 dark:border-gray-700" colspan="3">
                                        <i class="fab fa-instagram text-pink-500"></i> IG
                                    </th>
                                    <th scope="col" class="px-2 py-2 text-center border-b border-r border-gray-200 dark:border-gray-700" colspan="3">
                                        <i class="fab fa-facebook text-blue-600"></i> FB
                                    </th>
                                    <th scope="col" class="px-2 py-2 text-center border-b border-r border-gray-200 dark:border-gray-700" colspan="3">
                                        <i class="fab fa-twitter text-blue-400"></i> TW
                                    </th>
                                    <th scope="col" class="px-2 py-2 text-center border-b border-r border-gray-200 dark:border-gray-700" colspan="3">
                                        <i class="fab fa-tiktok text-black dark:text-white"></i> TT
                                    </th>
                                    <th scope="col" class="px-2 py-2 text-center border-b border-r border-gray-200 dark:border-gray-700" colspan="3">
                                        <i class="fab fa-youtube text-red-600"></i> YT
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-center w-32" rowspan="2">
                                        Total LCS
                                    </th>
                                </tr>
                                <tr>
                                    <!-- IG -->
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Like">L</th>
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Comment">C</th>
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Share">S</th>
                                    <!-- FB -->
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Like">L</th>
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Comment">C</th>
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Share">S</th>
                                    <!-- TW -->
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Like">L</th>
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Comment">C</th>
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Share">S</th>
                                    <!-- TT -->
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Like">L</th>
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Comment">C</th>
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Share">S</th>
                                    <!-- YT -->
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Like">L</th>
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Comment">C</th>
                                    <th scope="col" class="px-1 py-1 text-center border-r border-gray-200 dark:border-gray-700" title="Share">S</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pegawais as $index => $pegawai)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-2 py-2 text-center font-medium text-gray-900 dark:text-white border-r border-gray-200 dark:border-gray-700">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-2 font-medium text-gray-900 dark:text-white border-r border-gray-200 dark:border-gray-700">
                                            {{ $pegawai->nama_pegawai }}
                                        </td>
                                        
                                        <!-- IG -->
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->ig_l ?: '-' }}</td>
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->ig_c ?: '-' }}</td>
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->ig_s ?: '-' }}</td>
                                        
                                        <!-- FB -->
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->fb_l ?: '-' }}</td>
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->fb_c ?: '-' }}</td>
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->fb_s ?: '-' }}</td>

                                        <!-- TW -->
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->tw_l ?: '-' }}</td>
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->tw_c ?: '-' }}</td>
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->tw_s ?: '-' }}</td>

                                        <!-- TT -->
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->tt_l ?: '-' }}</td>
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->tt_c ?: '-' }}</td>
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->tt_s ?: '-' }}</td>

                                        <!-- YT -->
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->yt_l ?: '-' }}</td>
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->yt_c ?: '-' }}</td>
                                        <td class="px-1 py-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-gray-700">{{ $pegawai->yt_s ?: '-' }}</td>

                                        <td class="px-4 py-2 text-center font-bold text-blue-600 dark:text-blue-400">
                                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">
                                                {{ $pegawai->total_lcs ?? 0 }} LCS
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="18" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Belum ada data partisipasi pegawai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d", // Data dikirim ke server dalam format ini
                altInput: true,
                altFormat: "d/m/Y", // Tampilan untuk user
                allowInput: true,
                onChange: function(selectedDates, dateStr, instance) {
                    if (window.triggerSearch) {
                        window.triggerSearch();
                    }
                }
            });
        });
    </script>

    <!-- Live Search Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('livesearch-input');
            const filterStartDate = document.getElementById('filter-start-date');
            const filterEndDate = document.getElementById('filter-end-date');
            let typingTimer;
            const doneTypingInterval = 500;
            
            function triggerSearch(targetUrl = null) {
                let url;
                if (typeof targetUrl === 'string') {
                    url = new URL(targetUrl, window.location.origin);
                } else {
                    url = new URL(window.location.href);
                    
                    if (searchInput && searchInput.value.trim() !== '') {
                        url.searchParams.set('search', searchInput.value);
                    } else {
                        url.searchParams.delete('search');
                    }
                    
                    if (filterStartDate && filterStartDate.value !== '') {
                        url.searchParams.set('start_date', filterStartDate.value);
                    } else {
                        url.searchParams.delete('start_date');
                    }

                    if (filterEndDate && filterEndDate.value !== '') {
                        url.searchParams.set('end_date', filterEndDate.value);
                    } else {
                        url.searchParams.delete('end_date');
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
                    }
                })
                .catch(err => {
                    console.error('Live search error:', err);
                    if (contentDiv) {
                        contentDiv.style.opacity = '1';
                        contentDiv.style.pointerEvents = 'auto';
                    }
                });
            }

            window.triggerSearch = triggerSearch;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(triggerSearch, doneTypingInterval);
                });
            }
            
            if (filterStartDate) {
                filterStartDate.addEventListener('change', triggerSearch);
            }

            if (filterEndDate) {
                filterEndDate.addEventListener('change', triggerSearch);
            }
            
            // Intercept Pagination Clicks
            document.addEventListener('click', function(e) {
                const paginationLink = e.target.closest('#realtime-content nav[role="navigation"] a');
                if (paginationLink && paginationLink.href) {
                    e.preventDefault();
                    triggerSearch(paginationLink.href);
                }
            });
        });
    </script>
</x-app-layout>
<x-realtime-sync type="rekap" />
