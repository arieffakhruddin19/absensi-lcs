<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rekap Laporan Tugas LCS') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Rekapitulasi Semua Postingan dan Media Sosial</h3>
                </div>

                <div class="mb-4" style="display: flex; justify-content: flex-end;">
                    <form method="GET" action="{{ route('admin.rekap-laporan') }}" class="flex gap-2 w-full sm:w-auto">
                        
                        <div style="position: relative; display: flex; align-items: center; width: 180px;">
                            <input type="text" id="filter-tanggal" name="tanggal" value="{{ request('tanggal') }}" class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Pilih Tanggal..." title="Filter Tanggal">
                            <button type="button" onclick="document.getElementById('filter-tanggal')._flatpickr.clear();" class="text-gray-400 hover:text-gray-800 dark:hover:text-gray-200" title="Bersihkan tanggal" style="position: absolute; right: 10px;">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div>
                            <select id="filter-medsos" name="jenis_medsos" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Semua Medsos</option>
                                <option value="Instagram" {{ request('jenis_medsos') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                                <option value="Facebook" {{ request('jenis_medsos') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                                <option value="Twitter" {{ request('jenis_medsos') == 'Twitter' ? 'selected' : '' }}>Twitter</option>
                                <option value="TikTok" {{ request('jenis_medsos') == 'TikTok' ? 'selected' : '' }}>TikTok</option>
                                <option value="YouTube" {{ request('jenis_medsos') == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                            </select>
                        </div>

                        <div style="position: relative; width: 320px;">
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
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-16">No</th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Judul Postingan</th>
                                <th scope="col" class="px-6 py-3">Link</th>
                                <th scope="col" class="px-6 py-3">Medsos</th>
                                <th scope="col" class="px-6 py-3">Jumlah Like</th>
                                <th scope="col" class="px-6 py-3">Jumlah Comment</th>
                                <th scope="col" class="px-6 py-3">Jumlah Share</th>
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
                                <td class="px-6 py-4">
                                    <a href="{{ $item->link }}" target="_blank" class="text-blue-600 dark:text-blue-500 hover:underline">Link</a>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->jenis_medsos }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $item->like }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $item->comment }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $item->share }}
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
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('livesearch-input');
            const filterTanggal = document.getElementById('filter-tanggal');
            const filterMedsos = document.getElementById('filter-medsos');
            let typingTimer;
            const doneTypingInterval = 500;
            
            function triggerSearch() {
                const url = new URL(window.location.href);
                
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

                url.searchParams.delete('page');
                
                window.history.pushState({}, '', url);
                
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
                    if (newContent) {
                        document.querySelector('#realtime-content').innerHTML = newContent.innerHTML;
                    }
                })
                .catch(err => console.error('Live search error:', err));
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
        });
    </script>
    
    <x-realtime-sync type="laporan" />
</x-app-layout>
