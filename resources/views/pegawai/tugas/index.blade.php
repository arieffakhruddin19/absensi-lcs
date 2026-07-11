<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Tugas LCS Saya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tugas yang perlu diselesaikan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($postings as $post)
                        @php
                            $sudahSelesai = isset($absensi[$post->id]) && $absensi[$post->id] == 1;
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
                                <span class="absolute top-0 right-0 bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-bl-lg dark:bg-red-900 dark:text-red-300">
                                    Belum Selesai
                                </span>
                            @endif

                            <div class="flex flex-col items-center justify-center mb-2 mt-4">
                                <h5 class="mb-0 text-base font-bold text-gray-900 dark:text-white">{{ $post->judul_tugas }}</h5>
                            </div>
                            
                            <div class="flex flex-wrap justify-center gap-2 mb-3 mt-3">
                                @if($post->link_instagram)
                                    <a href="{{ $post->link_instagram }}" target="_blank" onclick="enableBtnSelesai({{ $post->id }})" class="px-2.5 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg hover:bg-gradient-to-l focus:ring-4 focus:outline-none focus:ring-purple-200">Buka IG</a>
                                @endif
                                @if($post->link_facebook)
                                    <a href="{{ $post->link_facebook }}" target="_blank" onclick="enableBtnSelesai({{ $post->id }})" class="px-2.5 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">Buka FB</a>
                                @endif
                                @if($post->link_twitter)
                                    <a href="{{ $post->link_twitter }}" target="_blank" onclick="enableBtnSelesai({{ $post->id }})" class="px-2.5 py-1.5 text-xs font-medium text-white rounded-lg hover:opacity-90 focus:ring-4 focus:outline-none" style="background-color: #1d9bf0;">Buka X</a>
                                @endif
                                @if($post->link_tiktok)
                                    <a href="{{ $post->link_tiktok }}" target="_blank" onclick="enableBtnSelesai({{ $post->id }})" class="px-2.5 py-1.5 text-xs font-medium text-white bg-black rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300">TikTok</a>
                                @endif
                                @if($post->link_youtube)
                                    <a href="{{ $post->link_youtube }}" target="_blank" onclick="enableBtnSelesai({{ $post->id }})" class="px-2.5 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300">YouTube</a>
                                @endif
                            </div>
                            
                            <div class="flex flex-col mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">

                                @if($sudahSelesai)
                                    <button disabled class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-center text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed dark:bg-gray-700 dark:text-gray-400">
                                        Sudah Dikerjakan
                                    </button>
                                @else
                                    <button type="button" id="btn-selesai-{{ $post->id }}" onclick="tandaiSelesai('{{ route('tugas.selesai', $post->id) }}', this)" disabled class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-center text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed dark:bg-gray-700 dark:text-gray-400 transition-all" title="Klik tombol media sosial terlebih dahulu untuk mengaktifkan">
                                        Tandai Selesai
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                          <span class="font-medium">Hore!</span> Belum ada tugas postingan baru saat ini.
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <!-- Script for AJAX -->
    <script>
        function enableBtnSelesai(postId) {
            const btn = document.getElementById('btn-selesai-' + postId);
            if (btn && btn.disabled) {
                btn.disabled = false;
                btn.removeAttribute('title');
                // Kembalikan ke warna hijau ala tombol aktif
                btn.className = 'inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-center text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800 transition-all';
            }
        }

        function tandaiSelesai(targetUrl, btnElement) {
            if(!confirm('Apakah Anda yakin sudah melakukan Like, Comment, dan Share pada postingan ini?')) {
                return;
            }

            // Ganti state tombol jadi loading
            const originalText = btnElement.innerHTML;
            btnElement.innerHTML = 'Memproses...';
            btnElement.disabled = true;

            fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Berubah jadi tombol disabled hijau/abu
                    btnElement.innerHTML = 'Sudah Dikerjakan';
                    btnElement.className = 'inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed dark:bg-gray-700 dark:text-gray-400 mt-2';
                    
                    // Ubah label merah jadi hijau
                    const card = btnElement.closest('.relative');
                    const badge = card.querySelector('.bg-red-100');
                    if(badge) {
                        badge.className = 'absolute top-0 right-0 bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-bl-lg dark:bg-green-900 dark:text-green-300';
                        badge.innerHTML = 'Selesai';
                    }
                } else {
                    alert('Gagal: ' + data.message);
                    btnElement.innerHTML = originalText;
                    btnElement.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan jaringan.');
                btnElement.innerHTML = originalText;
                btnElement.disabled = false;
            });
        }
    </script>
</x-app-layout>
