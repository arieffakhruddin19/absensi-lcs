<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-0">
        @php
            $monthName = \Carbon\Carbon::createFromDate(null, $selectedMonth ?? date('n'), 1)->locale('id')->translatedFormat('F');
            $yearName = $selectedYear ?? date('Y');
            $monthYear = $monthName . ' ' . $yearName;
        @endphp
        <div class="w-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Ringkasan Sistem LCS</h3>
                    <div class="flex w-full md:w-auto items-center gap-3">
                        <select id="filter-month" class="flex-1 md:flex-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ (isset($selectedMonth) ? $selectedMonth : date('n')) == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromDate(null, $m, 1)->locale('id')->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                        <select id="filter-year" class="flex-1 md:flex-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @foreach(range(date('Y'), date('Y') - 5) as $y)
                                <option value="{{ $y }}" {{ (isset($selectedYear) ? $selectedYear : date('Y')) == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card Pegawai Aktif -->
                <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white border-none">
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-100 mb-1">Pegawai Aktif</p>
                            <h4 id="val-pegawai-aktif" class="text-4xl font-extrabold">{{ $pegawaiAktif ?? 0 }}</h4>
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <!-- Decorative SVG -->
                    <svg class="absolute -bottom-4 -right-4 w-32 h-32 text-white/10 transform rotate-12" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                </div>

                <!-- Card Total Tugas -->
                <div class="relative overflow-hidden bg-gradient-to-br from-emerald-400 to-teal-600 rounded-xl shadow-lg p-6 text-white border-none">
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p id="title-tugas-bulan" class="text-sm font-medium text-emerald-100 mb-1">Postingan LCS Bulan {{ $monthYear }}</p>
                            <h4 id="val-total-tugas" class="text-4xl font-extrabold">{{ $totalTugas ?? 0 }}</h4>
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                    </div>
                    <!-- Decorative SVG -->
                    <svg class="absolute -top-4 -right-4 w-32 h-32 text-white/10 transform -rotate-12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                </div>

                <!-- Card Total Postingan Tahun Ini -->
                <div class="relative overflow-hidden rounded-xl shadow-lg p-6 text-white border-none" style="background: linear-gradient(to bottom right, #8b5cf6, #d946ef);">
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p id="title-tugas-tahun" class="text-sm font-medium text-purple-100 mb-1">Total Postingan LCS Tahun {{ $yearName }}</p>
                            <h4 id="val-total-tugas-tahun-ini" class="text-4xl font-extrabold">{{ $totalTugasTahunIni ?? 0 }}</h4>
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                    <!-- Decorative SVG -->
                    <svg class="absolute -bottom-4 -right-4 w-32 h-32 text-white/10 transform rotate-12" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
                </div>

                <!-- Card Total Partisipasi -->
                <div class="relative overflow-hidden rounded-xl shadow-lg p-6 text-white border-none" style="background: linear-gradient(to bottom right, #fb923c, #ef4444);">
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-orange-100 mb-1">Persentase Keaktifan (%)</p>
                            <h4 id="val-persentase-keaktifan" class="text-4xl font-extrabold">{{ $persentaseKeaktifan ?? 0 }}%</h4>
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                        </div>
                    </div>
                    <!-- Decorative SVG -->
                    <svg class="absolute -top-4 -right-4 w-32 h-32 text-white/10 transform -rotate-12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
            </div>

            <!-- Leaderboard Section (Top) -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 mb-6">
                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <span id="title-top-pegawai">Top Pegawai Bulan {{ $monthYear }}</span>
                </h4>
                
                <div id="leaderboard-container" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Kolom Kiri (Peringkat 1-3) -->
                    <div class="space-y-3">
                        @foreach($topPegawais->take(3) as $index => $pegawai)
                            <div class="flex items-center py-2.5 px-4 {{ $index < 3 ? 'bg-yellow-50 dark:bg-yellow-900/20' : 'bg-gray-50 dark:bg-gray-700/50' }} rounded-xl border {{ $index < 3 ? 'border-yellow-200 dark:border-yellow-700/50' : 'border-gray-100 dark:border-gray-600' }} transition-transform hover:scale-[1.01] hover:shadow-sm">
                                <div class="flex-shrink-0 mr-4">
                                    @if($index == 0)
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-300 to-yellow-500 flex items-center justify-center text-white font-black text-base shadow-md ring-4 ring-yellow-100 dark:ring-yellow-900/30">1</div>
                                    @elseif($index == 1)
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-white font-black text-base shadow-md ring-4 ring-gray-100 dark:ring-gray-700">2</div>
                                    @elseif($index == 2)
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-300 to-orange-500 flex items-center justify-center text-white font-black text-base shadow-md ring-4 ring-orange-100 dark:ring-orange-900/30">3</div>
                                    @endif
                                </div>
                                <div class="flex-1 flex flex-col sm:flex-row sm:items-center sm:justify-between min-w-0 gap-2 sm:gap-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white break-words leading-tight" title="{{ $pegawai->nama_pegawai }}">
                                            {{ $pegawai->nama_pegawai }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="inline-block text-sm font-bold text-yellow-600 dark:text-yellow-400 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                                            {{ $pegawai->total_lcs }} <span class="font-normal text-xs">LCS</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Kolom Kanan (Peringkat 4-6) -->
                    <div class="space-y-3">
                        @php $rank = 4; @endphp
                        @foreach($topPegawais->skip(3)->take(3) as $pegawai)
                            <div class="flex items-center py-2.5 px-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-600 transition-transform hover:scale-[1.01] hover:shadow-sm">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold text-base">{{ $rank++ }}</div>
                                </div>
                                <div class="flex-1 flex flex-col sm:flex-row sm:items-center sm:justify-between min-w-0 gap-2 sm:gap-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white break-words leading-tight" title="{{ $pegawai->nama_pegawai }}">
                                            {{ $pegawai->nama_pegawai }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="inline-block text-sm font-bold text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                                            {{ $pegawai->total_lcs }} <span class="font-normal text-xs">LCS</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="empty-leaderboard-msg" class="text-center text-sm text-gray-500 py-8" style="{{ count($topPegawais) == 0 ? '' : 'display: none;' }}">
                    Belum ada aktivitas LCS di bulan <span id="empty-msg-month">{{ $monthYear }}</span>.
                </div>
            </div>

            <!-- Charts Section (Bottom) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Line Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <h4 id="title-tren" class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Tren Partisipasi Bulan {{ $monthYear }}</h4>
                    <div class="relative w-full h-[300px]">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
                
                <!-- Doughnut Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 flex flex-col">
                    <h4 id="title-platform" class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Platform Terpopuler Bulan {{ $monthYear }}</h4>
                    <div class="relative w-full h-[300px]">
                        <canvas id="platformChart"></canvas>
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
            const trendChart = new Chart(trendCtx, {
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
                    maintainAspectRatio: false,
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
            const platformChart = new Chart(platformCtx, {
                type: 'bar',
                data: {
                    labels: ['Instagram', 'Facebook', 'Twitter', 'TikTok', 'YouTube'],
                    datasets: [{
                        label: 'Total LCS',
                        data: {!! json_encode($chartPlatformData) !!},
                        backgroundColor: [
                            'rgba(236, 72, 153, 0.8)', // pink-500
                            'rgba(59, 130, 246, 0.8)', // blue-500
                            'rgba(20, 184, 166, 0.8)', // teal-500
                            'rgba(31, 41, 55, 0.8)',   // gray-800
                            'rgba(239, 68, 68, 0.8)'   // red-500
                        ],
                        borderRadius: 6,
                        borderWidth: 0
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { display: true, color: 'rgba(0,0,0,0.05)' }
                        },
                        y: {
                            grid: { display: false }
                        }
                    }
                }
            });

            /**
             * =====================================================
             * REAL-TIME DASHBOARD SYNC (Vetmalance-V2 Pattern)
             * =====================================================
             */
            let stockPollTimer = null;
            let lastActivity = Date.now();
            let pusherConnected = false;
            const POLL_INTERVAL = 10000;          // 10 detik
            const IDLE_TIMEOUT  = 30 * 60 * 1000; // 30 menit
            
            const monthSelect = document.getElementById('filter-month');
            const yearSelect = document.getElementById('filter-year');
            
            monthSelect.addEventListener('change', () => { lastActivity = Date.now(); refreshFromServer(); });
            yearSelect.addEventListener('change', () => { lastActivity = Date.now(); refreshFromServer(); });

            function updateUI(data) {
                // Update Cards
                document.getElementById('val-pegawai-aktif').textContent = data.pegawaiAktif;
                document.getElementById('val-total-tugas').textContent = data.totalTugas;
                document.getElementById('val-total-tugas-tahun-ini').textContent = data.totalTugasTahunIni;
                document.getElementById('val-persentase-keaktifan').textContent = data.persentaseKeaktifan + '%';

                // Update Titles
                if (data.monthName && data.selectedYear) {
                    const monthYear = `${data.monthName} ${data.selectedYear}`;
                    document.getElementById('title-tugas-bulan').textContent = `Postingan LCS Bulan ${monthYear}`;
                    document.getElementById('title-tugas-tahun').textContent = `Total Postingan LCS Tahun ${data.selectedYear}`;
                    document.getElementById('title-top-pegawai').textContent = `Top Pegawai Bulan ${monthYear}`;
                    document.getElementById('empty-msg-month').textContent = monthYear;
                    document.getElementById('title-tren').textContent = `Tren Partisipasi Bulan ${monthYear}`;
                    document.getElementById('title-platform').textContent = `Platform Terpopuler Bulan ${monthYear}`;
                }

                // Update Leaderboard
                let container = document.getElementById('leaderboard-container');
                let emptyMsg = document.getElementById('empty-leaderboard-msg');
                
                if (data.topPegawais.length === 0) {
                    container.innerHTML = '';
                    emptyMsg.style.display = 'block';
                } else {
                    emptyMsg.style.display = 'none';
                    let leftCol = '<div class="space-y-3">';
                    let rightCol = '<div class="space-y-3">';
                    
                    data.topPegawais.forEach((pegawai, index) => {
                        let rank = index + 1;
                        let isTop3 = rank <= 3;
                        let bgClass = isTop3 ? 'bg-yellow-50 dark:bg-yellow-900/20' : 'bg-gray-50 dark:bg-gray-700/50';
                        let borderClass = isTop3 ? 'border-yellow-200 dark:border-yellow-700/50' : 'border-gray-100 dark:border-gray-600';
                        
                        let badgeHtml = '';
                        if (rank === 1) badgeHtml = '<div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-300 to-yellow-500 flex items-center justify-center text-white font-black text-base shadow-md ring-4 ring-yellow-100 dark:ring-yellow-900/30">1</div>';
                        else if (rank === 2) badgeHtml = '<div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-white font-black text-base shadow-md ring-4 ring-gray-100 dark:ring-gray-700">2</div>';
                        else if (rank === 3) badgeHtml = '<div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-300 to-orange-500 flex items-center justify-center text-white font-black text-base shadow-md ring-4 ring-orange-100 dark:ring-orange-900/30">3</div>';
                        else badgeHtml = `<div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold text-base">${rank}</div>`;
                        
                        let cardHtml = `
                            <div class="flex items-center py-2.5 px-4 ${bgClass} rounded-xl border ${borderClass} transition-transform hover:scale-[1.01] hover:shadow-sm">
                                <div class="flex-shrink-0 mr-4">${badgeHtml}</div>
                                <div class="flex-1 flex flex-col sm:flex-row sm:items-center sm:justify-between min-w-0 gap-2 sm:gap-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white break-words leading-tight" title="${pegawai.nama_pegawai}">${pegawai.nama_pegawai}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="inline-block text-sm font-bold ${isTop3 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-600 dark:text-gray-300'} bg-white dark:bg-gray-800 px-3 py-1.5 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">
                                            ${pegawai.total_lcs} <span class="font-normal text-xs">LCS</span>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                            
                        if (isTop3) leftCol += cardHtml;
                        else rightCol += cardHtml;
                    });
                    
                    leftCol += '</div>';
                    rightCol += '</div>';
                    container.innerHTML = leftCol + rightCol;
                }

                // Update Charts
                trendChart.data.labels = data.chartTrendLabels;
                trendChart.data.datasets[0].data = data.chartTrendData;
                trendChart.update();

                platformChart.data.datasets[0].data = data.chartPlatformData;
                platformChart.update();
            }

            function refreshFromServer(showToast = false) {
                if (Date.now() - lastActivity > IDLE_TIMEOUT) {
                    stopPolling();
                    return;
                }
                const m = monthSelect.value;
                const y = yearSelect.value;
                const url = `{{ route('api.dashboard.stats') }}?month=${m}&year=${y}`;
                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        updateUI(data);
                    })
                    .catch(err => console.error('Gagal mengambil data terbaru:', err));
            }

            function startPolling() {
                if (stockPollTimer || pusherConnected) return;
                console.log('[Fail-Safe] Pusher tidak tersedia, memulai AJAX polling...');
                stockPollTimer = setInterval(refreshFromServer, POLL_INTERVAL);
            }

            function stopPolling() {
                if (stockPollTimer) {
                    clearInterval(stockPollTimer);
                    stockPollTimer = null;
                }
            }

            document.addEventListener('visibilitychange', () => {
                if (document.hidden) stopPolling();
                else {
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

            setTimeout(() => {
                if (window.Echo) {
                    try {
                        window.Echo.connector.pusher.connection.bind('connected', () => {
                            console.log('[Pusher] Terkoneksi — polling OFF');
                            pusherConnected = true;
                            stopPolling();
                        });
                        ['disconnected', 'unavailable', 'failed'].forEach(state => {
                            window.Echo.connector.pusher.connection.bind(state, () => {
                                console.log(`[Pusher] ${state} — polling ON`);
                                pusherConnected = false;
                                startPolling();
                            });
                        });

                        window.Echo.channel('admin-notifications')
                            .listen('.AdminDataUpdated', (e) => {
                                refreshFromServer(true);
                            });
                    } catch (err) {
                        console.warn('[Pusher] Error inisialisasi:', err);
                        startPolling();
                    }
                } else {
                    console.log('[Fail-Safe] Echo tidak ditemukan, menggunakan AJAX polling');
                    startPolling();
                }
            }, 1500);
        });
    </script>
</x-app-layout>
