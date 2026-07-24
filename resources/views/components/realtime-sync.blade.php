@props(['type', 'channel' => 'admin-notifications', 'event' => 'AdminDataUpdated'])
<script>
/**
 * =====================================================
 * REAL-TIME {{ strtoupper($type) }} SYNC
 * Strategi: Pusher (utama) + AJAX Polling (fail-safe)
 * =====================================================
 */
document.addEventListener('DOMContentLoaded', function() {
    let pollTimer = null;
    let lastActivity = Date.now();
    let pusherConnected = false;
    const POLL_INTERVAL = 10000;
    const IDLE_TIMEOUT  = 30 * 60 * 1000;
    
    function updateUI(htmlText) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlText, 'text/html');
        
        // Gunakan ID unik #realtime-content sebagai target utama
        const newContent = doc.querySelector('#realtime-content');
        const currentContent = document.querySelector('#realtime-content');
        
        if (newContent && currentContent) {
            currentContent.innerHTML = newContent.innerHTML;
            
            // Re-inisialisasi Flowbite modal setelah DOM diperbarui
            // agar tombol data-modal-toggle tetap berfungsi
            if (typeof window.initFlowbite === 'function') {
                window.initFlowbite();
            }
            
            // Re-inisialisasi Flatpickr datepicker jika ada di dalam konten baru
            if (typeof flatpickr === 'function') {
                const rtContent = document.querySelector('#realtime-content');
                if (rtContent) {
                    rtContent.querySelectorAll('.datepicker').forEach(el => {
                        if (!el._flatpickr && !el.classList.contains('form-control')) {
                            flatpickr(el, {
                                dateFormat: "Y-m-d",
                                altInput: true,
                                altFormat: "d/m/Y",
                                allowInput: true
                            });
                        }
                    });
                }
            }

            // Restore state tombol "Tandai Selesai" yang sudah pernah diklik
            if (typeof restoreEnabledButtons === 'function') {
                restoreEnabledButtons();
            }
            
            // Restore state checkbox yang sudah di-unlock via klik medsos
            if (typeof restoreUnlockedCheckboxes === 'function') {
                restoreUnlockedCheckboxes();
            }
        }
    }

    function refreshFromServer() {
        if (Date.now() - lastActivity > IDLE_TIMEOUT) {
            stopPolling();
            return;
        }
        // Hindari browser cache agar selalu mengambil HTML terbaru dari server
        const fetchUrl = window.location.href.split('#')[0] + (window.location.href.includes('?') ? '&' : '?') + '_t=' + Date.now();
        
        fetch(fetchUrl, {
            method: 'GET',
            cache: 'no-store',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-cache',
                'Pragma': 'no-cache'
            }
        })
        .then(res => res.text())
        .then(html => updateUI(html))
        .catch(err => console.warn('[Fail-Safe] Poll error:', err));
    }

    function startPolling() {
        if (pollTimer || pusherConnected) return;
        console.log('[Fail-Safe] Pusher tidak tersedia, memulai AJAX polling...');
        pollTimer = setInterval(refreshFromServer, POLL_INTERVAL);
    }

    function stopPolling() {
        if (pollTimer) {
            clearInterval(pollTimer);
            pollTimer = null;
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

                // Listener 1: Admin Channel (Selalu ada)
                window.Echo.channel('admin-notifications')
                    .listen('.App\\\\Events\\\\AdminDataUpdated', (e) => {
                        // Jika AdminDataUpdated(posting), maka halaman tugas pegawai dan rekap laporan juga harus ter-update
                        if (e.type === '{{ $type }}' || (e.type === 'posting' && ('{{ $type }}' === 'tugas' || '{{ $type }}' === 'laporan'))) {
                            triggerUpdate();
                        }
                    });

                // Listener 2: Custom Channel (jika diberikan via props dan berbeda dari admin-notifications)
                @if(isset($channel) && $channel !== 'admin-notifications')
                window.Echo.channel('{{ $channel }}')
                    .listen('.App\\\\Events\\\\{{ $event ?? 'PegawaiDataUpdated' }}', (e) => {
                        if (e.type === '{{ $type }}') {
                            triggerUpdate();
                        }
                    });
                @endif
                
                function triggerUpdate() {
                    refreshFromServer();
                    
                    let notif = document.createElement('div');
                    notif.className = 'fixed bottom-4 left-1/2 -translate-x-1/2 px-4 py-2 bg-gray-900 text-white text-sm rounded shadow-lg z-[9999] transition-opacity duration-500 flex items-center space-x-2';
                    notif.innerHTML = `<svg class="w-4 h-4 text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg> <span>Data diperbarui (Real-Time)</span>`;
                    document.body.appendChild(notif);
                    
                    setTimeout(() => {
                        notif.style.opacity = '0';
                        setTimeout(() => notif.remove(), 500);
                    }, 3000);
                }
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
