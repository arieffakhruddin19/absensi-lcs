<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <div class="flex flex-nowrap justify-between items-center w-full gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan LCS') }}
            </h2>
            <a id="public-view-btn" href="{{ route('public.rekap-laporan', request()->query()) }}" target="_blank" class="inline-flex flex-shrink-0 items-center px-3 py-2 whitespace-nowrap bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Tampil Publik
            </a>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
                    @php
                        $sumberText = 'Kementan';
                        if (isset($tab)) {
                            if ($tab == 'pkh') $sumberText = 'Ditjen PKH';
                            elseif ($tab == 'pusvetma') $sumberText = 'Pusvetma';
                        }
                    @endphp
                    <h3 id="dynamic-title" class="text-lg font-medium text-gray-900 dark:text-gray-100">Rekapitulasi LCS Postingan Media Sosial {{ $sumberText }}</h3>
                    <a id="export-excel-btn" href="{{ route('admin.rekap-laporan.export', request()->query()) }}" class="flex items-center text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-md text-xs px-3 py-1.5 dark:bg-green-500 dark:hover:bg-green-600 focus:outline-none dark:focus:ring-green-800 transition ease-in-out duration-150">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Excel
                    </a>
                </div>

                <style>
                    .custom-filter-form { display: flex; flex-wrap: wrap; gap: 0.5rem; width: 100%; }
                    .custom-filter-perpage { flex: 1 1 20%; min-width: 70px; }
                    .custom-filter-tanggal { flex: 1 1 60%; min-width: 140px; }
                    .custom-filter-medsos { flex: 1 1 100%; }
                    .custom-filter-search { flex: 1 1 100%; }
                    
                    @media (min-width: 640px) {
                        .custom-filter-form { justify-content: flex-end; width: auto; }
                        .custom-filter-perpage { flex: 0 0 80px; }
                        .custom-filter-tanggal { flex: 0 0 160px; }
                        .custom-filter-medsos { flex: 0 0 160px; }
                        .custom-filter-search { flex: 0 0 260px; }
                    }
                </style>
                <div class="mb-4 flex justify-end w-full">
                    <form method="GET" action="{{ route('admin.rekap-laporan') }}" class="custom-filter-form">
                        
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
                            <button type="button" onclick="document.getElementById('filter-tanggal')._flatpickr.clear();" class="text-gray-400 hover:text-gray-800 dark:hover:text-gray-200" title="Bersihkan tanggal" style="position: absolute; right: 10px;">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="custom-filter-medsos">
                            <select id="filter-medsos" name="jenis_medsos" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Semua Medsos</option>
                                <option value="Instagram" {{ request('jenis_medsos') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                                <option value="Facebook" {{ request('jenis_medsos') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                                <option value="Twitter" {{ request('jenis_medsos') == 'Twitter' ? 'selected' : '' }}>Twitter</option>
                                <option value="TikTok" {{ request('jenis_medsos') == 'TikTok' ? 'selected' : '' }}>TikTok</option>
                                <option value="YouTube" {{ request('jenis_medsos') == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                            </select>
                        </div>

                        <div class="custom-filter-search" style="position: relative;">
                            <div style="position: absolute; top: 50%; left: 12px; transform: translateY(-50%); pointer-events: none;">
                                <svg style="width: 16px; height: 16px; color: #9ca3af;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="text" id="livesearch-input" name="search" value="{{ request('search') }}" placeholder="Cari Judul Postingan..." style="padding-left: 36px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                    </form>
                </div>

                <div id="realtime-content">
                    <!-- Tabs -->
                    <div class="mb-4 flex justify-center sm:justify-start">
                        <ul class="flex flex-wrap justify-center gap-2 text-xs sm:text-sm font-medium text-center" role="tablist">
                            <li role="presentation">
                                <a href="#" onclick="event.preventDefault(); switchTab('pkh')" class="inline-block px-3 py-2 whitespace-nowrap rounded-lg transition-colors duration-200 shadow-sm {{ (isset($tab) ? $tab : 'pkh') == 'pkh' ? 'text-white bg-blue-600 dark:bg-blue-500' : 'text-gray-600 bg-gray-100 border border-gray-200 hover:text-gray-900 hover:bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white' }}" role="tab">Ditjen PKH</a>
                            </li>
                            <li role="presentation">
                                <a href="#" onclick="event.preventDefault(); switchTab('pusvetma')" class="inline-block px-3 py-2 whitespace-nowrap rounded-lg transition-colors duration-200 shadow-sm {{ (isset($tab) ? $tab : 'pkh') == 'pusvetma' ? 'text-white bg-blue-600 dark:bg-blue-500' : 'text-gray-600 bg-gray-100 border border-gray-200 hover:text-gray-900 hover:bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white' }}" role="tab">Pusvetma</a>
                            </li>
                            <li role="presentation">
                                <a href="#" onclick="event.preventDefault(); switchTab('kementan')" class="inline-block px-3 py-2 whitespace-nowrap rounded-lg transition-colors duration-200 shadow-sm {{ (isset($tab) ? $tab : 'pkh') == 'kementan' ? 'text-white bg-blue-600 dark:bg-blue-500' : 'text-gray-600 bg-gray-100 border border-gray-200 hover:text-gray-900 hover:bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white' }}" role="tab">Kementan</a>
                            </li>
                        </ul>
                    </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-16 text-center">No</th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Judul Postingan</th>
                                <th scope="col" class="px-6 py-3 text-center">Sumber</th>
                                <th scope="col" class="px-6 py-3 text-center">Link</th>
                                <th scope="col" class="px-6 py-3 text-center">Medsos</th>
                                <th scope="col" class="px-6 py-3 text-center">Jumlah Like</th>
                                <th scope="col" class="px-6 py-3 text-center">Jumlah Comment</th>
                                <th scope="col" class="px-6 py-3 text-center">Jumlah Share</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekap as $index => $item)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 text-center">{{ $item->no }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $item->judul }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if(isset($item->sumber) && $item->sumber)
                                        @if($item->sumber == 'Kementan')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300 border border-green-400">{{ $item->sumber }}</span>
                                        @elseif($item->sumber == 'Ditjen PKH')
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 border border-blue-400">{{ $item->sumber }}</span>
                                        @else
                                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300 border border-purple-400">{{ $item->sumber }}</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ $item->link }}" target="_blank" class="text-blue-600 dark:text-blue-500 hover:underline">Link</a>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $item->jenis_medsos }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $item->like }} <span class="text-xs text-gray-500 font-medium ml-1">({{ round(($item->like / $totalPegawaiAktif) * 100, 1) }}%)</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $item->comment }} <span class="text-xs text-gray-500 font-medium ml-1">({{ round(($item->comment / $totalPegawaiAktif) * 100, 1) }}%)</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $item->share }} <span class="text-xs text-gray-500 font-medium ml-1">({{ round(($item->share / $totalPegawaiAktif) * 100, 1) }}%)</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center">Belum ada data rekap laporan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $rekap->appends(request()->query())->links() }}
                </div>
                </div> <!-- End of #realtime-content -->

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
                    if (window.triggerSearchGlobal) {
                        window.triggerSearchGlobal();
                    }
                }
            });
        });
    </script>

    <!-- Live Search Script -->
    <script>
        window.switchTab = function(tabName) {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabName);
            url.searchParams.delete('page');
            if (window.triggerSearchGlobal) {
                window.triggerSearchGlobal(url.href);
            } else {
                window.location.href = url.href;
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('livesearch-input');
            const filterTanggal = document.getElementById('filter-tanggal');
            const filterMedsos = document.getElementById('filter-medsos');
            const filterPerPage = document.getElementById('filter-perpage');
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
                    
                    if (filterTanggal && filterTanggal.value !== '') {
                        url.searchParams.set('tanggal', filterTanggal.value);
                    } else {
                        url.searchParams.delete('tanggal');
                    }

                    if (filterMedsos && filterMedsos.value !== '') {
                        url.searchParams.set('jenis_medsos', filterMedsos.value);
                    } else {
                        url.searchParams.delete('jenis_medsos');
                    }

                    if (filterPerPage && filterPerPage.value !== '') {
                        url.searchParams.set('per_page', filterPerPage.value);
                    } else {
                        url.searchParams.delete('per_page');
                    }

                    url.searchParams.delete('page');
                }
                
                window.history.pushState({}, '', url);
                
                const exportUrl = new URL('{{ route("admin.rekap-laporan.export") }}', window.location.origin);
                exportUrl.search = url.search;
                const exportBtn = document.getElementById('export-excel-btn');
                if (exportBtn) {
                    exportBtn.href = exportUrl.href;
                }
                
                const publicUrl = new URL('{{ route("public.rekap-laporan") }}', window.location.origin);
                publicUrl.search = url.search;
                const publicBtn = document.getElementById('public-view-btn');
                if (publicBtn) {
                    publicBtn.href = publicUrl.href;
                }
                
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
                    const newTitle = doc.querySelector('#dynamic-title');
                    const currentTitle = document.querySelector('#dynamic-title');
                    if (newTitle && currentTitle) {
                        currentTitle.innerHTML = newTitle.innerHTML;
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

            window.triggerSearchGlobal = triggerSearch;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(triggerSearch, doneTypingInterval);
                });
            }
            
            if (filterTanggal) {
                filterTanggal.addEventListener('change', triggerSearch);
            }

            if (filterMedsos) {
                filterMedsos.addEventListener('change', triggerSearch);
            }

            if (filterPerPage) {
                filterPerPage.addEventListener('change', triggerSearch);
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
    
    <x-realtime-sync type="laporan" />
</x-app-layout>
