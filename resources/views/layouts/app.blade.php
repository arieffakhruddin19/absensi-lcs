<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Absensi LCS') }}</title>

        <!-- PWA / Web App Manifest -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#1e40af">
        <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 antialiased">
        
        <!-- Top Navbar -->
        <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 h-16">
          <div class="flex items-center justify-between h-full">
            <div class="flex items-center h-full">
                <!-- Logo Area (Matches Sidebar Width & Color) -->
                <div id="logo-container" class="w-64 bg-blue-900 h-full flex items-center justify-center transition-all duration-300 hidden sm:flex shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <div class="w-8 h-8 mr-3 bg-white rounded-lg flex items-center justify-center">
                            <span class="text-blue-900 font-bold text-xl">V</span>
                        </div>
                        <span class="self-center text-xl font-bold whitespace-nowrap text-white">Pusvetma LCS</span>
                    </a>
                </div>

                <!-- Hamburger Menu -->
                <button id="sidebar-toggle-btn" type="button" class="ml-4 inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Buka sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                       <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                 </button>
                 
                 <!-- Mobile Logo (Shows only when logo-container is hidden) -->
                 <a href="{{ route('dashboard') }}" class="flex ms-4 sm:hidden">
                    <div class="w-8 h-8 mr-3 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl">V</span>
                    </div>
                    <span class="self-center text-xl font-bold whitespace-nowrap dark:text-white text-blue-900">Pusvetma LCS</span>
                 </a>
            </div>
            <div class="flex items-center pr-4" style="margin-right: 2rem;">
                  <div class="flex items-center ms-3">
                    <div>
                      <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user" data-dropdown-placement="bottom-end">
                        <span class="sr-only">Buka menu profil</span>
                        @if (Auth::user()->avatar)
                            <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="user photo">
                        @else
                            <div class="relative w-8 h-8 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                                <svg class="absolute w-10 h-10 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                            </div>
                        @endif
                      </button>
                    </div>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                      <div class="px-4 py-3" role="none">
                        <p class="text-sm text-gray-900 dark:text-white" role="none">
                          {{ Auth::user()->name }}
                        </p>
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                          {{ Auth::user()->nip }}
                        </p>
                      </div>
                      <ul class="py-1" role="none">
                        <li>
                          <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Profil Anda</a>
                        </li>
                        <li>
                          <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Log out</a>
                          </form>
                        </li>
                      </ul>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </nav>

        <!-- Sidebar -->
        <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-blue-900 border-r border-blue-900 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
           <div class="h-full px-3 pb-4 overflow-y-auto bg-blue-900 dark:bg-gray-800 text-white">
              <ul class="space-y-2 font-medium text-sm">
                 @if (in_array(Auth::user()->role, ['superadmin', 'admin']))
                 <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-blue-800 group {{ request()->routeIs('dashboard') ? 'bg-blue-800' : '' }}">
                       <svg class="w-5 h-5 text-blue-200 transition duration-75 group-hover:text-white {{ request()->routeIs('dashboard') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                          <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                          <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                       </svg>
                       <span class="ms-3">Dashboard</span>
                    </a>
                 </li>
                 @endif
                 
                 @if (in_array(Auth::user()->role, ['superadmin', 'admin']))
                 <li class="pt-4 mt-4 space-y-2 border-t border-blue-800">
                    <span class="px-3 text-xs font-semibold text-blue-300 uppercase tracking-wider">{{ Auth::user()->role === 'superadmin' ? 'MANAJEMEN SUPERADMIN' : 'MANAJEMEN ADMIN' }}</span>
                 </li>
                 <li>
                    <a href="{{ route('admin.pegawai.index') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-blue-800 group {{ request()->routeIs('admin.pegawai.*') ? 'bg-blue-800' : '' }}">
                       <svg class="flex-shrink-0 w-5 h-5 text-blue-200 transition duration-75 group-hover:text-white {{ request()->routeIs('admin.pegawai.*') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                          <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                       </svg>
                       <span class="flex-1 ms-3 whitespace-nowrap">Data Pegawai</span>
                    </a>
                 </li>
                 <li>
                    <a href="{{ route('admin.posting.index') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-blue-800 group {{ request()->routeIs('admin.posting.*') ? 'bg-blue-800' : '' }}">
                       <svg class="flex-shrink-0 w-5 h-5 text-blue-200 transition duration-75 group-hover:text-white {{ request()->routeIs('admin.posting.*') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 2a2 2 0 00-2 2H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2-2H10zm2 2a1 1 0 10-2 0h2zm-3.707 5.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L10 10.586 8.293 8.879z" clip-rule="evenodd"/>
                       </svg>
                       <span class="flex-1 ms-3 whitespace-nowrap">Daftar LCS</span>
                    </a>
                 </li>
                 <li>
                    <a href="{{ route('admin.rekap-laporan') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-blue-800 group {{ request()->routeIs('admin.rekap-laporan') ? 'bg-blue-800' : '' }}">
                       <svg class="flex-shrink-0 w-5 h-5 text-blue-200 transition duration-75 group-hover:text-white {{ request()->routeIs('admin.rekap-laporan') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M4 4a2 2 0 0 1 2-2h4.586A2 2 0 0 1 12 2.586L15.414 6A2 2 0 0 1 16 7.414V16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4Zm2 6a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H7a1 1 0 0 1-1-1Zm1 3a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H7Z"/>
                       </svg>
                       <span class="flex-1 ms-3 whitespace-nowrap">Rekap Laporan</span>
                    </a>
                 </li>
                 @endif

                 @if (Auth::user()->role === 'superadmin')
                 <li class="pt-4 mt-4 space-y-2 border-t border-blue-800">
                    <span class="px-3 text-xs font-semibold text-blue-300 uppercase tracking-wider">AKSES</span>
                 </li>
                 <li>
                    <a href="{{ route('admin.user.index') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-blue-800 group {{ request()->routeIs('admin.user.*') ? 'bg-blue-800' : '' }}">
                       <svg class="flex-shrink-0 w-5 h-5 text-blue-200 transition duration-75 group-hover:text-white {{ request()->routeIs('admin.user.*') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                       </svg>
                       <span class="flex-1 ms-3 whitespace-nowrap">Manajemen User</span>
                    </a>
                 </li>
                 @endif
                 
                 @if (Auth::user()->role === 'pegawai')
                 <li class="mb-2 space-y-2">
                    <span class="px-3 text-xs font-semibold text-blue-300 uppercase tracking-wider">MENU PEGAWAI</span>
                 </li>
                 <li>
                    <a href="{{ route('tugas.index') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-blue-800 group {{ request()->routeIs('tugas.index') ? 'bg-blue-800' : '' }}">
                       <svg class="flex-shrink-0 w-5 h-5 text-blue-200 transition duration-75 group-hover:text-white {{ request()->routeIs('tugas.index') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 2a2 2 0 00-2 2H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2-2H10zm2 2a1 1 0 10-2 0h2zm-3.707 5.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L10 10.586 8.293 8.879z" clip-rule="evenodd"/>
                       </svg>
                       <span class="flex-1 ms-3 whitespace-nowrap">Daftar LCS</span>
                    </a>
                 </li>
                 <li>
                    <a href="{{ route('tugas.riwayat') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-blue-800 group {{ request()->routeIs('tugas.riwayat') ? 'bg-blue-800' : '' }}">
                       <svg class="flex-shrink-0 w-5 h-5 text-blue-200 transition duration-75 group-hover:text-white {{ request()->routeIs('tugas.riwayat') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                       </svg>
                       <span class="flex-1 ms-3 whitespace-nowrap">Riwayat Selesai</span>
                    </a>
                 </li>
                 @if(Auth::user()->pegawai && Auth::user()->pegawai->can_monitor)
                 <li class="pt-4 mt-4 space-y-2 border-t border-blue-800">
                    <span class="px-3 text-xs font-semibold text-blue-300 uppercase tracking-wider">KINERJA TIM</span>
                 </li>
                 <li>
                    <a href="{{ route('tugas.monitoring') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-blue-800 group {{ request()->routeIs('tugas.monitoring') ? 'bg-blue-800' : '' }}">
                       <svg class="flex-shrink-0 w-5 h-5 text-blue-200 transition duration-75 group-hover:text-white {{ request()->routeIs('tugas.monitoring') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                          <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                       </svg>
                       <span class="flex-1 ms-3 whitespace-nowrap">Monitoring LCS</span>
                    </a>
                 </li>
                 @endif
                 @endif

              </ul>
           </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div id="main-content-wrapper" class="p-4 sm:ml-64 pt-20 min-h-screen flex flex-col transition-all duration-300">
           <!-- Page Heading -->
           @isset($header)
               <header class="mb-6">
                   <div class="px-2">
                       {{ $header }}
                   </div>
               </header>
           @endisset

           <!-- Page Content -->
           <main class="flex-1">
               {{ $slot }}
           </main>
           
           <footer class="mt-auto py-6 mt-8 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
               Pusvetma LCS System &copy; {{ date('Y') }}
           </footer>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggleBtn = document.getElementById('sidebar-toggle-btn');
                const sidebar = document.getElementById('logo-sidebar');
                const mainWrapper = document.getElementById('main-content-wrapper');
                const logoContainer = document.getElementById('logo-container');

                if (toggleBtn && sidebar && mainWrapper) {
                    toggleBtn.addEventListener('click', function() {
                        // Desktop
                        if (window.innerWidth >= 640) {
                            sidebar.classList.toggle('sm:translate-x-0');
                            sidebar.classList.toggle('sm:-translate-x-full');
                            mainWrapper.classList.toggle('sm:ml-64');
                            mainWrapper.classList.toggle('sm:ml-0');
                            
                            if (logoContainer) {
                                logoContainer.classList.toggle('w-64');
                                logoContainer.classList.toggle('w-0');
                                logoContainer.classList.toggle('overflow-hidden');
                                logoContainer.classList.toggle('opacity-0');
                            }
                        } 
                        // Mobile
                        else {
                            sidebar.classList.toggle('-translate-x-full');
                            sidebar.classList.toggle('translate-x-0');
                        }
                    });
                }
            });
        </script>

        <!-- Listener Akses Sidebar Pegawai -->
        @if(Auth::check() && Auth::user()->role === 'pegawai' && Auth::user()->pegawai_id)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(() => {
                    if (window.Echo) {
                        window.Echo.channel('pegawai-notifications-{{ Auth::user()->pegawai_id }}')
                            .listen('.App\\Events\\PegawaiDataUpdated', (e) => {
                                if (e.type === 'sidebar') {
                                    // Refresh halaman untuk memperbarui menu sidebar (muncul/hilang)
                                    window.location.reload();
                                }
                            });
                    }
                }, 2000);
            });
        </script>
        @endif

        <!-- Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('{{ asset('sw.js') }}')
                        .then(registration => {
                            console.log('ServiceWorker registration successful with scope: ', registration.scope);
                        })
                        .catch(error => {
                            console.log('ServiceWorker registration failed: ', error);
                        });
                });
            }
        </script>
    </body>
</html>
