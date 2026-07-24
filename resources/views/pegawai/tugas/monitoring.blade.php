<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Monitoring Tim LCS') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mb-4">
                        <div class="w-full md:w-auto">
                            <form class="flex items-center gap-2 flex-wrap">
                                <label for="livesearch-input" class="sr-only">Cari</label>
                                <div class="relative w-full md:w-80">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" id="livesearch-input" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari judul postingan...">
                                </div>
                                <div class="relative w-full md:w-auto flex items-center">
                                    <input type="text" id="tanggal-input" name="tanggal" value="{{ request('tanggal') }}" class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-auto p-2 pr-8 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Pilih Tanggal...">
                                    <button type="button" onclick="document.getElementById('tanggal-input')._flatpickr.clear();" class="text-gray-400 hover:text-gray-800 dark:hover:text-gray-200" title="Bersihkan tanggal" style="position: absolute; right: 10px;">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="relative w-full md:w-auto flex items-center">
                                    <select id="sumber-input" name="sumber" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-auto p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">Semua Sumber</option>
                                        <option value="Pusvetma" {{ request('sumber') == 'Pusvetma' ? 'selected' : '' }}>Pusvetma</option>
                                        <option value="Ditjen PKH" {{ request('sumber') == 'Ditjen PKH' ? 'selected' : '' }}>Ditjen PKH</option>
                                        <option value="Kementan" {{ request('sumber') == 'Kementan' ? 'selected' : '' }}>Kementan</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="realtime-content">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @forelse ($postings as $post)
                                @php
                                    $stat = $stats[$post->id];
                                    $percentage = $stat['total'] > 0 ? round(($stat['selesai'] / $stat['total']) * 100) : 0;
                                    $sudahSelesai = $percentage == 100;
                                @endphp
                                <div class="px-6 pb-6 pt-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative text-center">

                                    @if($post->batas_waktu)
                                        @php
                                            $deadline = \Carbon\Carbon::parse($post->batas_waktu)->endOfDay();
                                            $isPast = \Carbon\Carbon::now()->gt($deadline);
                                        @endphp
                                        <span class="absolute top-0 right-0 text-xs font-medium px-2.5 py-0.5 rounded-bl-lg rounded-tr-lg {{ $isPast ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                            Tenggat: {{ \Carbon\Carbon::parse($post->batas_waktu)->locale('id')->translatedFormat('d M Y') }}
                                        </span>
                                    @endif

                                    <div class="flex flex-col items-center justify-center mb-2 mt-1">
                                        <h5 class="mb-2 text-base font-bold text-blue-900 dark:text-blue-300">{{ $post->judul_tugas }}</h5>
                                        
                                        <div class="flex items-center gap-1.5 mt-1 text-xs text-blue-700 dark:text-blue-400 font-medium">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.949 8.949 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                </svg>
                                                <span>{{ $post->sumber_posting ?: 'Pusvetma' }}</span>
                                            </div>
                                            <span class="text-blue-300 dark:text-blue-600">&bull;</span>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16M8 14h8m-4-7V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                                                </svg>
                                                <span>{{ $post->tanggal_tugas ? \Carbon\Carbon::parse($post->tanggal_tugas)->locale('id')->translatedFormat('d F Y') : \Carbon\Carbon::parse($post->created_at)->locale('id')->translatedFormat('d F Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col gap-2 mb-3 mt-3 w-full px-4 text-left">
                                        <div class="w-full">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Progress LCS</span>
                                                <span class="text-xs font-semibold {{ $stat['selesai'] == $stat['total'] ? 'text-green-600' : 'text-blue-600' }}">{{ $stat['selesai'] }} / {{ $stat['total'] }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                                <div class="{{ $percentage == 100 ? 'bg-green-600' : 'bg-blue-600' }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-2 mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 w-full px-2">
                                        @php
                                            $formattedTanggal = $post->tanggal_tugas ? \Carbon\Carbon::parse($post->tanggal_tugas)->locale('id')->translatedFormat('d F Y') : \Carbon\Carbon::parse($post->created_at)->locale('id')->translatedFormat('d F Y');
                                            $sumber = $post->sumber_posting ?: 'Pusvetma';
                                        @endphp
                                        <button onclick="openMonitoringModal({{ $post->id }}, '{{ addslashes($post->judul_tugas) }}', '{{ addslashes($sumber) }}', '{{ addslashes($formattedTanggal) }}', 'sudah')" class="flex-1 inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-green-700 bg-green-50 border border-green-500 rounded-lg hover:bg-green-100 focus:ring-4 focus:outline-none focus:ring-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-500 dark:hover:bg-green-900/50 transition-all">
                                            Sudah: {{ $stat['selesai'] }}
                                        </button>
                                        <button onclick="openMonitoringModal({{ $post->id }}, '{{ addslashes($post->judul_tugas) }}', '{{ addslashes($sumber) }}', '{{ addslashes($formattedTanggal) }}', 'belum')" class="flex-1 inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 border border-red-500 rounded-lg hover:bg-red-100 focus:ring-4 focus:outline-none focus:ring-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-500 dark:hover:bg-red-900/50 transition-all">
                                            Belum: {{ $stat['total'] - $stat['selesai'] }}
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                                    Belum ada tugas LCS
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            {{ $postings->appends(request()->query())->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- List Pegawai Modal -->
    <div id="monitoring-modal" tabindex="-1" aria-hidden="true" data-modal-backdrop="static" class="hidden fixed inset-0 z-50 justify-center items-center">
        <div class="relative p-4 w-full max-w-md" style="max-height: 100vh; margin: auto;">
            <!-- Modal content -->
            <div id="monitoring-modal-content" class="relative bg-white rounded-lg shadow dark:bg-gray-700 flex flex-col">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600" style="flex-shrink: 0;">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-judul-tugas">
                        Daftar Pegawai
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="closeMonitoringModal()">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Tutup</span>
                    </button>
                </div>
                <!-- Modal Search Area -->
                <div class="p-4 md:p-5 border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700" style="flex-shrink: 0;">
                    <div class="mb-2 text-sm text-center text-gray-500 dark:text-gray-400 font-medium line-clamp-2" id="modal-subtitle"></div>
                    <div class="flex items-center justify-center gap-1.5 mb-3 mt-1 text-xs text-blue-700 dark:text-blue-400 font-medium" id="modal-source-date">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.949 8.949 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            <span id="modal-source"></span>
                        </div>
                        <span class="text-blue-300 dark:text-blue-600">&bull;</span>
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16M8 14h8m-4-7V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                            </svg>
                            <span id="modal-date"></span>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" id="monitoring-search" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari nama pegawai...">
                    </div>
                </div>

                <!-- Modal body - scrollable area -->
                <div id="monitoring-modal-body" class="p-4 md:p-5" style="overflow-y: scroll; -webkit-overflow-scrolling: touch; touch-action: pan-y; overscroll-behavior: contain;">
                    
                    <div id="monitoring-loading" class="text-center py-8 hidden">
                        <div role="status">
                            <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                            </svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <ul id="monitoring-pegawai-container" class="space-y-1 select-none">
                        <!-- List will be populated here -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for Live Search -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('livesearch-input');
            const tanggalInput = document.getElementById('tanggal-input');
            const sumberInput = document.getElementById('sumber-input');
            let typingTimer;
            const doneTypingInterval = 500;
            
            window.refreshMonitoringData = function() {
                const url = new URL(window.location.href);
                if (searchInput && searchInput.value.trim() !== '') {
                    url.searchParams.set('search', searchInput.value);
                } else {
                    url.searchParams.delete('search');
                }
                
                if (tanggalInput && tanggalInput.value) {
                    url.searchParams.set('tanggal', tanggalInput.value);
                } else {
                    url.searchParams.delete('tanggal');
                }

                if (sumberInput && sumberInput.value !== '') {
                    url.searchParams.set('sumber', sumberInput.value);
                } else {
                    url.searchParams.delete('sumber');
                }
                
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

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(() => {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('page');
                        window.history.pushState({}, '', url);
                        window.refreshMonitoringData();
                    }, doneTypingInterval);
                });
            }
            if (tanggalInput) {
                tanggalInput.addEventListener('change', () => {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('page');
                    window.history.pushState({}, '', url);
                    window.refreshMonitoringData();
                });
            }
            if (sumberInput) {
                sumberInput.addEventListener('change', () => {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('page');
                    window.history.pushState({}, '', url);
                    window.refreshMonitoringData();
                });
            }
        });

        // Intercept Pagination Clicks
        document.addEventListener('click', function(e) {
            const paginationLink = e.target.closest('#realtime-content nav[role="navigation"] a');
            if (paginationLink && paginationLink.href) {
                e.preventDefault();
                const url = new URL(paginationLink.href);
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
        });
    </script>

    <!-- Script to handle Live Search & Modal -->
    <script>
        let currentPostingId = null;
        let allPegawais = [];
        let currentStatus = 'sudah';

        function openMonitoringModal(postingId, judul, sumber, tanggal, defaultStatus = 'sudah') {
            currentPostingId = postingId;
            currentStatus = defaultStatus;
            
            const titleEl = document.getElementById('modal-judul-tugas');
            titleEl.innerText = 'Pegawai ' + (defaultStatus === 'sudah' ? 'Sudah LCS' : 'Belum LCS');
            titleEl.className = 'text-lg font-semibold ' + (defaultStatus === 'sudah' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400');
            
            document.getElementById('modal-subtitle').innerText = judul;
            document.getElementById('modal-source').innerText = sumber;
            document.getElementById('modal-date').innerText = tanggal;
            document.getElementById('monitoring-search').value = '';
            
            // Tampilkan modal
            const modal = document.getElementById('monitoring-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            
            // Calculate body height dynamically
            setTimeout(function() {
                var modalContent = document.getElementById('monitoring-modal-content');
                var bodyDiv = document.getElementById('monitoring-modal-body');
                var windowHeight = window.innerHeight;
                var maxModalHeight = Math.floor(windowHeight * 0.85);
                modalContent.style.maxHeight = maxModalHeight + 'px';
                
                var headerHeight = modalContent.querySelector('.border-b.rounded-t') ? modalContent.querySelector('.border-b.rounded-t').offsetHeight : 0;
                var searchHeight = document.getElementById('modal-subtitle').closest('.border-b.border-gray-200') ? document.getElementById('modal-subtitle').closest('.border-b.border-gray-200').offsetHeight : 0;
                var remainingHeight = maxModalHeight - headerHeight - searchHeight - 20;
                bodyDiv.style.maxHeight = Math.max(remainingHeight, 150) + 'px';
            }, 50);

            if (typeof createBackdrop === 'function') createBackdrop();
            else {
                const backdrop = document.createElement('div');
                backdrop.id = 'monitoring-modal-backdrop';
                backdrop.style.cssText = 'position:fixed;inset:0;z-index:40;background:rgba(17,24,39,0.5);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);transition:opacity 0.2s;';
                document.body.appendChild(backdrop);
            }

            loadMonitoringData();
        }

        function closeMonitoringModal() {
            const modal = document.getElementById('monitoring-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
            
            const bd = document.getElementById('monitoring-modal-backdrop');
            if (bd) bd.remove();
        }

        function loadMonitoringData() {
            if (!currentPostingId) return;
            
            const container = document.getElementById('monitoring-pegawai-container');
            const loading = document.getElementById('monitoring-loading');
            
            container.innerHTML = '';
            container.classList.add('hidden');
            loading.classList.remove('hidden');
            
            const baseUrl = "{{ url('tugas') }}";
            fetch(`${baseUrl}/${currentPostingId}/list-pegawai?status=${currentStatus}`)
                .then(res => res.json())
                .then(data => {
                    loading.classList.add('hidden');
                    container.classList.remove('hidden');
                    
                    if (data.success) {
                        allPegawais = data.data;
                        renderPegawaiList(allPegawais);
                    } else {
                        container.innerHTML = `<li class="p-2 text-center text-sm text-red-500">${data.message || 'Terjadi kesalahan.'}</li>`;
                    }
                })
                .catch(err => {
                    console.error(err);
                    loading.classList.add('hidden');
                    container.classList.remove('hidden');
                    container.innerHTML = `<li class="p-2 text-center text-sm text-red-500">Terjadi kesalahan.</li>`;
                });
        }

        function renderPegawaiList(data) {
            const container = document.getElementById('monitoring-pegawai-container');
            container.innerHTML = '';
            
            if (data.length === 0) {
                container.innerHTML = '<li class="p-2 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada pegawai yang ditemukan.</li>';
                return;
            }
            
            data.forEach((pegawai, index) => {
                const li = document.createElement('li');
                li.className = 'px-3 py-2 border-b border-gray-100 dark:border-gray-700 last:border-0 text-sm text-gray-700 dark:text-gray-200 flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition rounded-md select-none';
                
                li.innerHTML = `
                    <span class="font-medium text-gray-500 dark:text-gray-400 w-5 text-right flex-shrink-0">${index + 1}.</span> 
                    <span>${pegawai.nama_pegawai}</span>
                `;
                container.appendChild(li);
            });
        }

        document.getElementById('monitoring-search').addEventListener('input', function(e) {
            const keyword = e.target.value.toLowerCase();
            const filtered = allPegawais.filter(p => p.nama_pegawai.toLowerCase().includes(keyword));
            renderPegawaiList(filtered);
        });
    </script>
    
    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                allowInput: true,
                disableMobile: true
            });
        });
    </script>
    
    <!-- Real-Time Sync -->
    <script>
        let syncPollTimer = null;
        let lastActivity = Date.now();
        let pusherConnected = false;
        const POLL_INTERVAL = 10000;          // 10 detik
        const IDLE_TIMEOUT  = 30 * 60 * 1000; // 30 menit

        function refreshFromServer() {
            if (Date.now() - lastActivity > IDLE_TIMEOUT) {
                stopPolling();
                return;
            }
            if (window.refreshMonitoringData) {
                window.refreshMonitoringData();
            }
        }

        function startPolling() {
            if (syncPollTimer || pusherConnected) return;
            console.log('[Fail-Safe] Pusher tidak tersedia, memulai AJAX polling...');
            syncPollTimer = setInterval(refreshFromServer, POLL_INTERVAL);
        }

        function stopPolling() {
            if (syncPollTimer) {
                clearInterval(syncPollTimer);
                syncPollTimer = null;
            }
        }

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopPolling();
            } else {
                lastActivity = Date.now();
                refreshFromServer();
                if (!pusherConnected) startPolling();
            }
        });

        ['click', 'keydown', 'mousemove', 'scroll'].forEach(evt => {
            document.addEventListener(evt, () => {
                const wasIdle = Date.now() - lastActivity > IDLE_TIMEOUT;
                lastActivity = Date.now();
                if (wasIdle) {
                    refreshFromServer();
                    if (!pusherConnected) startPolling();
                }
            }, { passive: true });
        });

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                if (window.Echo) {
                    try {
                        window.Echo.connector.pusher.connection.bind('connected', () => {
                            pusherConnected = true;
                            stopPolling();
                        });
                        ['disconnected', 'unavailable', 'failed'].forEach(state => {
                            window.Echo.connector.pusher.connection.bind(state, () => {
                                pusherConnected = false;
                                startPolling();
                            });
                        });

                        window.Echo.channel('admin-notifications')
                            .listen('.App\\Events\\AdminDataUpdated', (e) => {
                                if (e.type === 'laporan' || e.type === 'posting' || e.type === 'rekap') {
                                    refreshFromServer();
                                }
                            });
                    } catch (err) {
                        console.warn('[Pusher] Error inisialisasi:', err);
                        startPolling();
                    }
                } else {
                    startPolling();
                }
            }, 1500);
        });
    </script>
</x-app-layout>
