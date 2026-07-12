<x-app-layout>
    <x-slot name="header">
        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Tugas LCS') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full">
            <div id="realtime-content" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if (session('success'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                      <span class="font-medium">Sukses!</span> {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Link Postingan</h3>
                    
                    <!-- Modal toggle -->
                    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                      + Tambah Tugas
                    </button>
                </div>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-16">No</th>
                                <th scope="col" class="px-6 py-3">Judul Tugas</th>
                                <th scope="col" class="px-6 py-3">Tanggal Dibuat</th>
                                <th scope="col" class="px-6 py-3">Link Medsos Terpasang</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($postings as $index => $post)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 text-center">{{ $postings->firstItem() + $index }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $post->judul_tugas }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $post->tanggal_tugas ? \Carbon\Carbon::parse($post->tanggal_tugas)->locale('id')->translatedFormat('d F Y') : $post->created_at->locale('id')->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @if($post->link_instagram) <a href="{{ $post->link_instagram }}" target="_blank" class="bg-pink-100 text-pink-800 text-xs font-medium px-2 py-0.5 rounded">IG</a> @endif
                                        @if($post->link_facebook) <a href="{{ $post->link_facebook }}" target="_blank" class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">FB</a> @endif
                                        @if($post->link_twitter) <a href="{{ $post->link_twitter }}" target="_blank" class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded">X</a> @endif
                                        @if($post->link_tiktok) <a href="{{ $post->link_tiktok }}" target="_blank" class="bg-black text-white text-xs font-medium px-2 py-0.5 rounded">TikTok</a> @endif
                                        @if($post->link_youtube) <a href="{{ $post->link_youtube }}" target="_blank" class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded">YT</a> @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 flex items-center space-x-2">
                                    <a href="{{ route('admin.posting.laporan', $post->id) }}" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-2 dark:bg-green-500 dark:hover:bg-green-600 focus:outline-none dark:focus:ring-green-800">Laporan</a>
                                    <button data-modal-target="edit-modal-{{ $post->id }}" data-modal-toggle="edit-modal-{{ $post->id }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800" type="button">Edit</button>
                                    <form action="{{ route('admin.posting.destroy', $post->id) }}" method="POST" class="inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-2 dark:bg-red-500 dark:hover:bg-red-600 focus:outline-none dark:focus:ring-red-900" onclick="return confirm('Yakin ingin menghapus tugas ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center">Belum ada tugas postingan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $postings->links() }}
                </div>

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
                        Tambah Tugas LCS Baru
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
                            <label for="judul_tugas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Tugas</label>
                            <input type="text" name="judul_tugas" id="judul_tugas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                        </div>
                        <div class="col-span-2">
                            <label for="tanggal_tugas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                            <input type="text" name="tanggal_tugas" id="tanggal_tugas" value="{{ date('Y-m-d') }}" class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>
                        <div class="col-span-2 hidden">
                            <label for="batas_waktu" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Batas Waktu (Deadline)</label>
                            <input type="date" name="batas_waktu" id="batas_waktu" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>
                        
                        <div class="col-span-2 mt-2">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white border-b pb-1">Link Media Sosial (Opsional)</h4>
                            <p class="text-xs text-gray-500">Kosongkan link jika tidak ada tugas di platform tersebut.</p>
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
                        Simpan Tugas
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modals -->
    @foreach ($postings as $post)
    <div id="edit-modal-{{ $post->id }}" tabindex="-1" aria-hidden="true" data-modal-backdrop="static" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Edit Tugas LCS
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
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Tugas</label>
                            <input type="text" name="judul_tugas" value="{{ $post->judul_tugas }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                            <input type="text" name="tanggal_tugas" value="{{ $post->tanggal_tugas ? \Carbon\Carbon::parse($post->tanggal_tugas)->format('Y-m-d') : '' }}" class="datepicker bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div class="col-span-2 hidden">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Batas Waktu (Deadline)</label>
                            <input type="date" name="batas_waktu" value="{{ $post->batas_waktu ? \Carbon\Carbon::parse($post->batas_waktu)->format('Y-m-d') : '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        
                        <div class="col-span-2 mt-2">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white border-b pb-1">Link Media Sosial (Opsional)</h4>
                            <p class="text-xs text-gray-500">Kosongkan link jika tidak ada tugas di platform tersebut.</p>
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
    <x-realtime-sync type="posting" />
</x-app-layout>
