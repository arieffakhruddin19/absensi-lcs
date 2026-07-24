<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen User') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Data User</h3>
                    
                    <!-- Modal toggle -->
                    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-xs px-3 py-1.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition ease-in-out duration-150" type="button">
                      + Tambah User
                    </button>
                </div>

                <!-- Filters and Live Search -->
                <style>
                    .user-filter-container {
                        display: flex;
                        justify-content: flex-end;
                        align-items: center;
                        gap: 10px;
                        flex-wrap: wrap;
                    }
                    .user-filter-role { width: 150px; }
                    .user-filter-search {
                        width: 320px;
                        position: relative;
                    }
                    @media (max-width: 768px) {
                        .user-filter-container {
                            flex-direction: column;
                            align-items: stretch;
                        }
                        .user-filter-role { width: 100%; }
                        .user-filter-search { width: 100%; }
                    }
                </style>
                <div class="mb-4 user-filter-container">
                    <!-- Role Filter -->
                    <div class="user-filter-role">
                        <select id="role-filter" name="role" onchange="triggerSearch()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="semua" {{ request('role') == 'semua' || !request('role') ? 'selected' : '' }}>Semua Role</option>
                            <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="pegawai" {{ request('role') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="user-filter-search">
                        <div style="position: absolute; top: 50%; left: 12px; transform: translateY(-50%); pointer-events: none;">
                            <svg style="width: 16px; height: 16px; color: #9ca3af;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" id="livesearch-input" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." style="padding-left: 36px;" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                </div>

                <div id="realtime-content">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center w-16">No</th>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3 text-center">Role</th>
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 text-center">{{ $users->firstItem() + $index }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4">{{ $user->email ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($user->role === 'superadmin')
                                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">Superadmin</span>
                                    @elseif($user->role === 'admin')
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Admin</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-900 dark:text-gray-300">Pegawai</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex items-center justify-center space-x-2">
                                    <button data-modal-target="edit-modal-{{ $user->id }}" data-modal-toggle="edit-modal-{{ $user->id }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-xs px-3 py-1.5 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800 transition" type="button">
                                        Edit
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-md text-xs px-3 py-1.5 dark:bg-red-500 dark:hover:bg-red-600 focus:outline-none dark:focus:ring-red-900 transition" onclick="confirmDelete(this, 'Yakin ingin menghapus user ini?')">Hapus</button>
                                    </form>
                                    @endif

                                    <!-- Edit Modal -->
                                    <div id="edit-modal-{{ $user->id }}" tabindex="-1" aria-hidden="true" data-modal-backdrop="static" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-md max-h-full">
                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                        Edit User
                                                    </h3>
                                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="edit-modal-{{ $user->id }}">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                        </svg>
                                                        <span class="sr-only">Tutup</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.user.update', $user->id) }}" method="POST" class="p-4 md:p-5 text-left">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                                        <div class="col-span-2">
                                                            <label for="name_{{ $user->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                                            <input type="text" name="name" id="name_{{ $user->id }}" value="{{ $user->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <label for="email_{{ $user->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                                            <input type="email" name="email" id="email_{{ $user->id }}" value="{{ $user->email }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <label for="password_{{ $user->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password <span class="text-xs text-gray-400">(kosongkan jika tidak diubah)</span></label>
                                                            <input type="password" name="password" id="password_{{ $user->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Minimal 8 karakter">
                                                        </div>
                                                        <div class="col-span-2">
                                                            <label for="role_{{ $user->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                                            <select name="role" id="role_{{ $user->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                                                <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                                <option value="pegawai" {{ $user->role == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                                            </select>
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
                                <td colspan="5" class="px-6 py-4 text-center">Belum ada data user.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
                </div> <!-- End of #realtime-content -->

            </div>
        </div>
    </div>

    <!-- Tambah User Modal -->
    <div id="crud-modal" tabindex="-1" aria-hidden="true" data-modal-backdrop="static" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Tambah User Baru
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Tutup</span>
                    </button>
                </div>
                <form action="{{ route('admin.user.store') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Nama lengkap" required>
                        </div>
                        <div class="col-span-2">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="email@contoh.com" required>
                        </div>
                        <div class="col-span-2">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Minimal 8 karakter" required>
                        </div>
                        <div class="col-span-2">
                            <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                            <select name="role" id="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                <option value="" selected disabled>-- Pilih Role --</option>
                                <option value="superadmin">Superadmin</option>
                                <option value="admin">Admin</option>
                                <option value="pegawai">Pegawai</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Simpan User
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: {!! json_encode(session('success')) !!},
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: {!! json_encode(session('error')) !!},
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '<ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

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
                    button.closest('form').submit();
                }
            });
        }
    </script>

    <!-- Live Search, Filter & Pagination Script -->
    <script>
        let typingTimer;
        const doneTypingInterval = 500;
        
        window.triggerSearch = function(targetUrl = null) {
            const searchInput = document.getElementById('livesearch-input');
            const roleFilter = document.getElementById('role-filter');
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
                
                if (roleFilter && roleFilter.value && roleFilter.value !== 'semua') {
                    url.searchParams.set('role', roleFilter.value);
                } else {
                    url.searchParams.delete('role');
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

    <!-- Backdrop Script -->
    <script>
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
            const observer = new MutationObserver(function() {
                const anyModalOpen = document.querySelectorAll('[id$="-modal"]:not(.hidden)[tabindex="-1"], [id^="edit-modal-"]:not(.hidden)[tabindex="-1"]');
                let found = false;
                anyModalOpen.forEach(el => {
                    if (!el.classList.contains('hidden')) found = true;
                });
                if (found) { createBackdrop(); } else { removeBackdrop(); }
            });
            document.addEventListener('DOMContentLoaded', function() {
                observer.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['class'] });
            });
        })();
    </script>
</x-app-layout>
