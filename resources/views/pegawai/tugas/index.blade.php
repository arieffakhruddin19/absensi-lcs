<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Tugas LCS Saya') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="w-full">
            <div id="realtime-content" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tugas yang perlu diselesaikan</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse ($postings as $post)
                        @php
                            $abs = $absensiRecords[$post->id] ?? null;
                            $sudahSelesai = $abs && $abs->status_selesai;
                        @endphp
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative text-center">
                            <span class="absolute top-0 left-0 bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-0.5 rounded-br-lg rounded-tl-lg dark:bg-gray-700 dark:text-gray-300">
                                {{ $post->tanggal_tugas ? \Carbon\Carbon::parse($post->tanggal_tugas)->locale('id')->translatedFormat('d F Y') : $post->created_at->locale('id')->translatedFormat('d F Y') }}
                            </span>

                            @if($sudahSelesai)
                                <span class="absolute top-0 right-0 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-bl-lg rounded-tr-lg dark:bg-green-900 dark:text-green-300">
                                    Selesai
                                </span>
                            @else
                                <span class="absolute top-0 right-0 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-bl-lg rounded-tr-lg dark:bg-red-900 dark:text-red-300">
                                    Belum Selesai
                                </span>
                            @endif

                            <div class="flex flex-col items-center justify-center mb-2 mt-4">
                                <h5 class="mb-0 text-base font-bold text-gray-900 dark:text-white">{{ $post->judul_tugas }}</h5>
                            </div>
                            
                            <div class="flex flex-col gap-2 mb-3 mt-3 w-full px-4">
                                @foreach([
                                    'instagram' => ['link' => $post->link_instagram, 'key' => 'ig', 'label' => 'Buka IG', 'class' => 'bg-gradient-to-r from-purple-500 to-pink-500 hover:bg-gradient-to-l focus:ring-purple-200', 'style' => ''],
                                    'facebook' => ['link' => $post->link_facebook, 'key' => 'fb', 'label' => 'Buka FB', 'class' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-300', 'style' => ''],
                                    'twitter' => ['link' => $post->link_twitter, 'key' => 'tw', 'label' => 'Buka X', 'class' => 'hover:opacity-90', 'style' => 'background-color: #1d9bf0;'],
                                    'tiktok' => ['link' => $post->link_tiktok, 'key' => 'tt', 'label' => 'TikTok', 'class' => 'bg-black hover:bg-gray-800 focus:ring-gray-300 text-white', 'style' => ''],
                                    'youtube' => ['link' => $post->link_youtube, 'key' => 'yt', 'label' => 'YouTube', 'class' => 'bg-red-600 hover:bg-red-700 focus:ring-red-300', 'style' => '']
                                ] as $platform => $data)
                                    @if($data['link'])
                                        <div class="flex items-center justify-between w-full p-2 bg-gray-50 rounded dark:bg-gray-700 gap-2">
                                            <a href="javascript:void(0)" onclick="bukaDanBukaKunci('{{ $post->id }}', '{{ $data['key'] }}', '{{ $data['link'] }}')" class="px-3 py-1.5 text-xs font-medium text-white rounded-lg focus:ring-4 focus:outline-none text-center whitespace-nowrap {{ $data['class'] }}" style="{{ $data['style'] }}">{{ $data['label'] }}</a>
                                            <div class="flex items-center space-x-2 sm:space-x-3 justify-end">
                                                @foreach(['like' => 'L', 'comment' => 'C', 'share' => 'S'] as $action => $actionLabel)
                                                    @php 
                                                        $field = $data['key'] . '_' . $action;
                                                        $isChecked = $abs->$field ?? false;
                                                    @endphp
                                                    <label class="flex items-center space-x-1 cursor-pointer" title="{{ ucfirst($action) }}">
                                                        <input type="checkbox" class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 transition-opacity duration-200 medsos-checkbox-{{ $post->id }} medsos-checkbox-{{ $post->id }}-{{ $data['key'] }} {{ $isChecked ? '' : 'opacity-50 cursor-not-allowed' }}" data-locked="{{ $isChecked ? 'false' : 'true' }}" data-post="{{ $post->id }}" data-platform="{{ $data['key'] }}" data-action="{{ $action }}" data-url="{{ route('tugas.medsos', $post->id) }}" onclick="return handleCheckboxClick(event, this);" onchange="tandaiMedsos(this)" {{ $isChecked ? 'checked' : '' }}>
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
                                    <button type="button" id="btn-selesai-{{ $post->id }}" onclick="tandaiSelesai('{{ route('tugas.selesai', $post->id) }}', this)" disabled class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-center text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed dark:bg-gray-700 dark:text-gray-400 transition-all w-full" title="Selesaikan semua medsos di atas terlebih dahulu">
                                        Konfirmasi Selesai Semua
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                          Belum ada tugas LCS postingan baru saat ini.
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <!-- Script for AJAX -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Set global untuk menyimpan platform yang sudah dibuka kuncinya
        // Format: 'postId-platform' misalnya '7-ig'
        if (!window._unlockedPlatforms) {
            window._unlockedPlatforms = new Set();
        }

        function bukaDanBukaKunci(postId, platform, url) {
            // 1. Simpan ke memori global (tahan terhadap refresh DOM)
            window._unlockedPlatforms.add(postId + '-' + platform);
            
            // 2. Buka kuncinya
            unlockCheckboxes(postId, platform);
            
            // 3. Baru kemudian buka tab baru
            window.open(url, '_blank');
        }

        function unlockCheckboxes(postId, platform) {
            const checkboxes = document.querySelectorAll('.medsos-checkbox-' + postId + '-' + platform);
            for(let i=0; i<checkboxes.length; i++) {
                checkboxes[i].setAttribute('data-locked', 'false');
                checkboxes[i].classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        // Fungsi ini dipanggil setiap kali DOM di-refresh oleh realtime-sync
        function restoreUnlockedCheckboxes() {
            window._unlockedPlatforms.forEach(function(key) {
                var parts = key.split('-');
                var postId = parts[0];
                var platform = parts[1];
                unlockCheckboxes(postId, platform);
            });
            // Re-check tombol selesai
            var posts = new Set();
            document.querySelectorAll('input[type="checkbox"][data-post]').forEach(function(cb) {
                posts.add(cb.getAttribute('data-post'));
            });
            posts.forEach(function(postId) { checkAllMedsos(postId); });
        }

        // Hook global agar realtime-sync bisa memanggilnya
        window.restoreUnlockedCheckboxes = restoreUnlockedCheckboxes;

        function handleCheckboxClick(e, checkbox) {
            if (checkbox.getAttribute('data-locked') === 'true') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Terkunci!',
                    text: 'Silakan buka link medsos terlebih dahulu dan lakukan LCS!',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                e.preventDefault();
                return false;
            }
            return true;
        }

        function activateButton(btn) {
            btn.disabled = false;
            btn.removeAttribute('title');
            btn.className = 'inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-center text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800 transition-all w-full';
        }

        function deactivateButton(btn) {
            btn.disabled = true;
            btn.setAttribute('title', 'Selesaikan semua medsos di atas terlebih dahulu');
            btn.className = 'inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-center text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed dark:bg-gray-700 dark:text-gray-400 transition-all w-full';
        }

        function checkAllMedsos(postId) {
            // Syarat: IG minimal 1 centang DAN FB minimal 1 centang
            const requiredPlatforms = ['ig', 'fb'];
            let allPlatformsMet = true;
            let hasAnyRequired = false;
            
            requiredPlatforms.forEach(platform => {
                const checkboxes = document.querySelectorAll('.medsos-checkbox-' + postId + '-' + platform);
                if (checkboxes.length > 0) {
                    hasAnyRequired = true;
                    // Cukup minimal 1 yang dicentang
                    let anyChecked = false;
                    checkboxes.forEach(cb => {
                        if (cb.checked) anyChecked = true;
                    });
                    if (!anyChecked) allPlatformsMet = false;
                }
            });
            
            // Jika tidak ada IG maupun FB, fallback: minimal 1 centang dari platform manapun
            if (!hasAnyRequired) {
                const allCb = document.querySelectorAll('.medsos-checkbox-' + postId);
                if (allCb.length === 0) return;
                let anyChecked = false;
                allCb.forEach(cb => {
                    if (cb.checked) anyChecked = true;
                });
                allPlatformsMet = anyChecked;
            }
            
            const btn = document.getElementById('btn-selesai-' + postId);
            if(btn) {
                if(allPlatformsMet) {
                    activateButton(btn);
                } else {
                    deactivateButton(btn);
                }
            }
        }

        function tandaiMedsos(checkbox) {
            checkbox.disabled = true;
            
            const postId = checkbox.getAttribute('data-post');
            const platform = checkbox.getAttribute('data-platform');
            const action = checkbox.getAttribute('data-action');
            const isChecked = checkbox.checked;
            const targetUrl = checkbox.getAttribute('data-url');
            
            fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ platform: platform, action: action, is_checked: isChecked })
            })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    checkbox.disabled = false;
                    checkAllMedsos(postId);
                } else {
                    checkbox.checked = !isChecked;
                    checkbox.disabled = false;
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                }
            })
            .catch(e => {
                checkbox.checked = !isChecked;
                checkbox.disabled = false;
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem: ' + e.message });
                console.error(e);
            });
        }

        // Cek status saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            // Ambil semua ID posting yang ada di halaman
            const posts = new Set();
            document.querySelectorAll('input[type="checkbox"][data-post]').forEach(cb => {
                posts.add(cb.getAttribute('data-post'));
            });
            
            posts.forEach(postId => checkAllMedsos(postId));
            
            // Restore unlock state jika ada
            restoreUnlockedCheckboxes();
        });

        function tandaiSelesai(targetUrl, btnElement) {
            Swal.fire({
                title: 'Konfirmasi LCS',
                text: 'Apakah Anda yakin sudah melakukan Like, Comment, dan Share pada postingan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sudah!',
                cancelButtonText: 'Belum'
            }).then((result) => {
                if (result.isConfirmed) {
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
                            const card = btnElement.closest('.relative');
                            
                            // Animasi menghilang (fade out & shrink)
                            card.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.9)';
                            
                            // Hapus elemen dari DOM setelah animasi selesai
                            setTimeout(() => {
                                card.remove();
                                
                                // Cek apakah tidak ada lagi tugas yang tersisa di dalam container grid
                                const container = document.querySelector('.grid');
                                if (container && container.children.length === 0) {
                                    container.innerHTML = `<div class="col-span-full p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                                      <span class="font-medium">Hore!</span> Semua tugas telah diselesaikan.
                                    </div>`;
                                }
                            }, 500);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                            btnElement.innerHTML = originalText;
                            btnElement.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.' });
                        btnElement.innerHTML = originalText;
                        btnElement.disabled = false;
                    });
                }
            });
        }
    </script>
    <x-realtime-sync type="tugas" channel="pegawai-notifications-{{ auth()->user()->pegawai_id ?? '0' }}" event="PegawaiDataUpdated" />
</x-app-layout>
