<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Ringkasan Sistem LCS</h3>
                
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Card Pegawai Aktif -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pegawai Aktif</p>
                            <h4 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pegawaiAktif ?? 0 }}</h4>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full dark:bg-blue-900">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card Total Tugas -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Tugas LCS</p>
                            <h4 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalTugas ?? 0 }}</h4>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full dark:bg-green-900">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <!-- Gamification & Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column (Charts) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Line Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Tren Partisipasi (7 Hari Terakhir)</h4>
                        <canvas id="trendChart" height="100"></canvas>
                    </div>
                    
                    <!-- Doughnut Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Platform Terpopuler (Bulan Ini)</h4>
                        <div class="flex justify-center">
                            <div class="w-full max-w-sm">
                                <canvas id="platformChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Leaderboard) -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 h-full border border-gray-100 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            Top Pegawai Bulan Ini
                        </h4>
                        
                        <div class="space-y-3">
                            @forelse($topPegawais as $index => $pegawai)
                                <div class="flex items-center p-3 {{ $index < 3 ? 'bg-yellow-50 dark:bg-yellow-900/20' : 'bg-gray-50 dark:bg-gray-700/50' }} rounded-lg border {{ $index < 3 ? 'border-yellow-200 dark:border-yellow-700/50' : 'border-gray-100 dark:border-gray-600' }}">
                                    <div class="flex-shrink-0 mr-4">
                                        @if($index == 0)
                                            <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center text-white font-bold text-sm shadow-sm">1</div>
                                        @elseif($index == 1)
                                            <div class="w-8 h-8 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold text-sm shadow-sm">2</div>
                                        @elseif($index == 2)
                                            <div class="w-8 h-8 rounded-full bg-orange-400 flex items-center justify-center text-white font-bold text-sm shadow-sm">3</div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold text-sm">{{ $index + 1 }}</div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $pegawai->nama_pegawai }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 text-right ml-2">
                                        <div class="text-sm font-bold {{ $index < 3 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-600 dark:text-gray-300' }}">
                                            {{ $pegawai->total_lcs }} <span class="text-xs font-normal">LCS</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-sm text-gray-500 py-4">Belum ada aktivitas LCS di bulan ini.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </div>

    <!-- Chart.js and Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Trend Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! $chartTrendLabels->toJson() !!},
                    datasets: [{
                        label: 'Tugas Diselesaikan',
                        data: {!! $chartTrendData->toJson() !!},
                        borderColor: '#3b82f6', // blue-500
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#3b82f6',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0 }
                        }
                    }
                }
            });

            // Platform Chart
            const platformCtx = document.getElementById('platformChart').getContext('2d');
            new Chart(platformCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Instagram', 'Facebook', 'Twitter', 'TikTok', 'YouTube'],
                    datasets: [{
                        data: {!! json_encode($chartPlatformData) !!},
                        backgroundColor: [
                            '#ec4899', // pink-500 (IG)
                            '#3b82f6', // blue-500 (FB)
                            '#14b8a6', // teal-500 (TW)
                            '#1f2937', // gray-800 (TT)
                            '#ef4444'  // red-500 (YT)
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 20 }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
