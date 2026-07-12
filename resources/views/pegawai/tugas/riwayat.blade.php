<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Tugas LCS') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-4" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 m-0">Tugas yang sudah diselesaikan</h3>
                    
                    <!-- Live Search Form -->
                    <div style="position: relative; width: 320px;">
                        <div style="position: absolute; top: 50%; left: 12px; transform: translateY(-50%); pointer-events: none;">
                            <svg style="width: 16px; height: 16px; color: #9ca3af;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" id="livesearch-input" name="search" value="{{ request('search') }}" placeholder="Cari judul tugas..." style="padding-left: 36px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                </div>

                <div id="realtime-content">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse ($postings as $post)
                        @php
                            $abs = $absensiRecords[$post->id] ?? null;
                            $sudahSelesai = $abs && $abs->status_selesai;
                        @endphp
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative text-center">
                            <span class="absolute top-0 left-0 bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-0.5 rounded-br-lg dark:bg-gray-700 dark:text-gray-300">
                                {{ $post->tanggal_tugas ? \Carbon\Carbon::parse($post->tanggal_tugas)->locale('id')->translatedFormat('d F Y') : $post->created_at->locale('id')->translatedFormat('d F Y') }}
                            </span>
                            @if($sudahSelesai)
                                <span class="absolute top-0 right-0 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-bl-lg dark:bg-green-900 dark:text-green-300">
                                    Selesai
                                </span>
                            @else
                                <span class="absolute top-0 right-0 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-bl-lg dark:bg-red-900 dark:text-red-300">
                                    Belum Selesai
                                </span>
                            @endif

                            <div class="flex flex-col items-center justify-center mb-2 mt-4">
                                <h5 class="mb-0 text-base font-bold text-gray-900 dark:text-white">{{ $post->judul_tugas }}</h5>
                            </div>
                            
                            <div class="flex flex-col gap-2 mb-3 mt-3 w-full px-4 text-left">
                                @foreach([
                                    'instagram' => ['link' => $post->link_instagram, 'key' => 'ig', 'label' => 'Buka IG', 'class' => 'bg-gradient-to-r from-purple-500 to-pink-500 hover:bg-gradient-to-l focus:ring-purple-200', 'style' => ''],
                                    'facebook' => ['link' => $post->link_facebook, 'key' => 'fb', 'label' => 'Buka FB', 'class' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-300', 'style' => ''],
                                    'twitter' => ['link' => $post->link_twitter, 'key' => 'tw', 'label' => 'Buka X', 'class' => 'hover:opacity-90', 'style' => 'background-color: #1d9bf0;'],
                                    'tiktok' => ['link' => $post->link_tiktok, 'key' => 'tt', 'label' => 'TikTok', 'class' => 'bg-black hover:bg-gray-800 focus:ring-gray-300 text-white', 'style' => ''],
                                    'youtube' => ['link' => $post->link_youtube, 'key' => 'yt', 'label' => 'YouTube', 'class' => 'bg-red-600 hover:bg-red-700 focus:ring-red-300', 'style' => '']
                                ] as $platform => $data)
                                    @if($data['link'])
                                        <div class="flex items-center justify-between w-full p-2 bg-gray-50 rounded dark:bg-gray-700 opacity-75 gap-2">
                                            <a href="{{ $data['link'] }}" target="_blank" class="px-3 py-1.5 text-xs font-medium text-white rounded-lg focus:ring-4 focus:outline-none text-center whitespace-nowrap {{ $data['class'] }}" style="{{ $data['style'] }}">{{ $data['label'] }}</a>
                                            <div class="flex items-center space-x-2 sm:space-x-3 justify-end">
                                                @foreach(['like' => 'L', 'comment' => 'C', 'share' => 'S'] as $action => $actionLabel)
                                                    @php 
                                                        $field = $data['key'] . '_' . $action;
                                                        $isChecked = $abs->$field ?? false;
                                                    @endphp
                                                    <label class="flex items-center space-x-1" title="{{ ucfirst($action) }}">
                                                        <input type="checkbox" class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500" {{ $isChecked ? 'checked' : '' }} disabled>
                                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $actionLabel }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            
                            <div class="flex flex-col mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">

                                @if($sudahSelesai)
                                    <button disabled class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-center text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed dark:bg-gray-700 dark:text-gray-400">
                                        Sudah Dikerjakan
                                    </button>
                                @else
                                    <button type="button" onclick="tandaiSelesai('{{ route('tugas.selesai', $post->id) }}', this)" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-center text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800 transition-all">
                                        Tandai Selesai
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                          <span class="font-medium">Kosong!</span> Belum ada riwayat tugas LCS yang Anda selesaikan.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $postings->appends(request()->query())->links() }}
                </div>
                </div> <!-- End of #realtime-content -->

            </div>
        </div>
    </div>

    <!-- Live Search Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('livesearch-input');
            let typingTimer;
            const doneTypingInterval = 500;
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(function() {
                        const url = new URL(window.location.href);
                        if (searchInput.value.trim() !== '') {
                            url.searchParams.set('search', searchInput.value);
                            url.searchParams.delete('page');
                        } else {
                            url.searchParams.delete('search');
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
                    }, doneTypingInterval);
                });
            }
        });
    </script>
    <x-realtime-sync type="tugas" channel="pegawai-notifications-{{ auth()->user()->pegawai_id ?? '0' }}" event="PegawaiDataUpdated" />
</x-app-layout>
