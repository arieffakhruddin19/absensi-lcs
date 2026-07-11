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
                      <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                        <span class="sr-only">Buka menu profil</span>
                        <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff" alt="user photo">
                      </button>
                    </div>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                      <div class="px-4 py-3" role="none">
                        <p class="text-sm text-gray-900 dark:text-white" role="none">
                          {{ Auth::user()->name }}
                        </p>
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                          {{ Auth::user()->nip }} ({{ Auth::user()->role }})
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
                 <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-blue-800 group {{ request()->routeIs('dashboard') ? 'bg-blue-800' : '' }}">
                       <svg class="w-5 h-5 text-blue-200 transition duration-75 group-hover:text-white {{ request()->routeIs('dashboard') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                          <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                          <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                       </svg>
                       <span class="ms-3">Dashboard</span>
                    </a>
                 </li>
                 
                 @if (Auth::user()->role === 'admin')
                 <li class="pt-4 mt-4 space-y-2 border-t border-blue-800">
                    <span class="px-3 text-xs font-semibold text-blue-300 uppercase tracking-wider">MANAJEMEN ADMIN</span>
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
                          <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
                       </svg>
                       <span class="flex-1 ms-3 whitespace-nowrap">Tugas LCS</span>
                    </a>
                 </li>
                 @endif
                 
                 @if (Auth::user()->role === 'pegawai')
                 <li class="pt-4 mt-4 space-y-2 border-t border-blue-800">
                    <span class="px-3 text-xs font-semibold text-blue-300 uppercase tracking-wider">MENU PEGAWAI</span>
                 </li>
                 <li>
                    <a href="{{ route('tugas.index') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-blue-800 group {{ request()->routeIs('tugas.index') ? 'bg-blue-800' : '' }}">
                       <svg class="flex-shrink-0 w-5 h-5 text-blue-200 transition duration-75 group-hover:text-white {{ request()->routeIs('tugas.index') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M10 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16Zm1 11H9v-2h2v2Zm0-4H9V5h2v4Z"/>
                       </svg>
                       <span class="flex-1 ms-3 whitespace-nowrap">Daftar Tugas LCS</span>
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
