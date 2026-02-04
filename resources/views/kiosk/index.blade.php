@extends('layouts.kiosk')

@section('content')
<div x-data="kioskSystem()" x-init="init()" class="bg-gray-800/50 backdrop-blur-xl border border-gray-700 rounded-3xl p-8 shadow-2xl relative">
    
    <!-- Timeout Progress Bar -->
    <div x-show="user" class="absolute top-0 left-0 h-1 bg-blue-500 transition-all duration-100" :style="'width: ' + (timerPercent) + '%'"></div>

    <!-- Header -->
    <div class="flex justify-between items-start mb-10">
        <div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-2">
                Self-Service Kiosk
            </h1>
            <p class="text-gray-400" x-text="statusMessage"></p>
        </div>
        <div class="flex flex-col items-end space-y-2">
            <a href="{{ route('catalog') }}" class="px-4 py-2 bg-indigo-600/20 text-indigo-400 rounded-lg border border-indigo-500/30 hover:bg-indigo-600/30 transition text-sm">
                📚 Lihat Katalog
            </a>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
        
        <!-- Left Column: Scanner (Always visible or toggle) -->
        <div class="space-y-4">
            <div x-show="loginMethod === 'qr' || user" class="relative w-full aspect-square bg-black rounded-2xl overflow-hidden shadow-inner border-4 border-gray-700/50 transition-all">
                <div id="reader" class="w-full h-full"></div>
                <!-- Overlay Guide -->
                <div class="absolute inset-0 pointer-events-none border-2 border-blue-500/50 rounded-2xl animate-pulse">
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 border-2 border-white/30 rounded-lg"></div>
                </div>
            </div>

            <!-- Manual Login Form (Shown only when not logged in and method is manual) -->
            <div x-show="!user && loginMethod === 'manual'" class="bg-gray-700/30 p-8 rounded-2xl border border-gray-600 space-y-6" x-transition>
                <h2 class="text-xl font-bold text-white">Login Manual</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">NIS / Email</label>
                        <input x-model="manualData.nis" type="text" class="w-full bg-gray-800 border-gray-600 rounded-lg py-3 px-4 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">PIN (6 Digit)</label>
                        <input x-model="manualData.pin" type="password" maxlength="6" class="w-full bg-gray-800 border-gray-600 rounded-lg py-3 px-4 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none tracking-widest">
                    </div>
                    <button @click="loginUserManual()" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg transition-transform active:scale-95">
                        Masuk
                    </button>
                    <button @click="loginMethod = 'qr'" class="w-full py-2 text-blue-400 hover:text-blue-300 text-sm">
                        &larr; Gunakan Scan QR
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Column: Info & Controls -->
        <div class="flex flex-col justify-center">
            <!-- Initial State: Method Choice -->
            <div x-show="!user && loginMethod === 'qr'" class="space-y-6 text-center" x-transition>
                <div class="p-8 bg-blue-600/10 border border-blue-500/20 rounded-2xl">
                    <svg class="h-16 w-16 text-blue-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <p class="text-white font-medium mb-4 text-lg">Scan kartu member lu buat mulai!</p>
                    <button @click="loginMethod = 'manual'" class="text-blue-400 hover:underline">
                        Gak punya kartu atau gak bsa scan? Login Manual aja
                    </button>
                </div>
            </div>

            <!-- Logged In State -->
            <div x-show="user" class="space-y-6" x-transition @click="resetTimeout()">
                <!-- User Card -->
                <div class="p-6 bg-gray-700/50 rounded-2xl border border-gray-600">
                    <div class="flex items-center space-x-4">
                        <div class="h-14 w-14 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-xl font-bold text-white shadow-lg">
                            <span x-text="user?.name?.[0]"></span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-400">Selamat datang,</p>
                            <h3 class="text-2xl font-bold text-white" x-text="user?.name"></h3>
                        </div>
                        <button @click="logout()" class="p-2 text-gray-400 hover:text-red-400 transition" title="Logout">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        </button>
                    </div>
                </div>

                <!-- Mode Selection -->
                <div class="grid grid-cols-1 gap-4">
                    <button @click="mode = 'borrow'; statusMessage = 'Scan QR Buku buat minjem'"
                        :class="mode === 'borrow' ? 'bg-blue-600 ring-4 ring-blue-500/20' : 'bg-gray-700 opacity-60 hover:opacity-100'"
                        class="p-6 rounded-2xl font-bold flex items-center space-x-4 transition">
                        <span class="text-3xl">📖</span>
                        <div class="text-left">
                            <div class="text-lg text-white">Pinjam Buku</div>
                            <div class="text-xs text-gray-300 font-normal">Pilih mode ini buat peminjaman baru</div>
                        </div>
                    </button>
                    <button @click="mode = 'return'; statusMessage = 'Scan QR Buku buat balikin'"
                        :class="mode === 'return' ? 'bg-purple-600 ring-4 ring-purple-500/20' : 'bg-gray-700 opacity-60 hover:opacity-100'"
                        class="p-6 rounded-2xl font-bold flex items-center space-x-4 transition">
                        <span class="text-3xl">↩️</span>
                        <div class="text-left">
                            <div class="text-lg text-white">Balikin Buku</div>
                            <div class="text-xs text-gray-300 font-normal">Pilih mode ini kalo mau balikin buku</div>
                        </div>
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-xs text-gray-500">Sesi lu bakal kelar otomatis dalam <span x-text="Math.ceil(timer / 10)"></span> detik</p>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function kioskSystem() {
    return {
        scanner: null,
        user: null,
        mode: 'borrow',
        loginMethod: 'qr', // qr | manual
        statusMessage: 'Scan kartu member atau login manual',
        isProcessing: false,
        manualData: { nis: '', pin: '' },
        timer: 900, // 90.0 seconds (100ms units)
        timeoutId: null,

        get timerPercent() {
            return (this.timer / 900) * 100;
        },

        init() {
            this.initScanner();
            this.startTimer();
        },

        initScanner() {
            this.scanner = new Html5Qrcode("reader");
            this.scanner.start(
                { facingMode: "environment" }, 
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => this.handleScan(decodedText)
            ).catch(err => {
                this.statusMessage = "Gagal akses kamera.";
            });
        },

        startTimer() {
            this.timeoutId = setInterval(() => {
                if (this.user) {
                    this.timer--;
                    if (this.timer <= 0) this.logout();
                }
            }, 100);
        },

        resetTimeout() {
            this.timer = 900;
        },

        async handleScan(content) {
            if (this.isProcessing) return;
            this.resetTimeout();
            this.isProcessing = true;

            try {
                if (!this.user) {
                    await this.loginWithQR(content);
                } else {
                    await this.processBook(content);
                }
            } finally {
                setTimeout(() => { this.isProcessing = false; }, 2000);
            }
        },

        async loginWithQR(qrContent) {
            await this.apiCall('/api/kiosk/login', { qr_code: qrContent });
        },

        async loginUserManual() {
            if (!this.manualData.nis || !this.manualData.pin) {
                this.statusMessage = "NIS dan PIN harus diisi!";
                return;
            }
            await this.apiCall('/api/kiosk/login', this.manualData);
        },

        async apiCall(url, body) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify(body)
                });
                const data = await response.json();
                if (data.success) {
                    this.user = data.user;
                    this.statusMessage = "Berhasil masuk!";
                    this.resetTimeout();
                } else {
                    this.statusMessage = "Gagal: " + data.message;
                }
            } catch (e) {
                this.statusMessage = "Koneksi Bermasalah";
            }
        },

        async processBook(qrContent) {
            const endpoint = this.mode === 'borrow' ? '/api/kiosk/borrow' : '/api/kiosk/return';
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': 'Bearer ' + this.user.token 
                },
                body: JSON.stringify({ book_qr: qrContent })
            });

            const data = await response.json();
            if (data.success) {
                this.statusMessage = "Sukses! " + data.message;
                this.resetTimeout();
            } else {
                this.statusMessage = "Gagal: " + data.message;
            }
        },

        logout() {
            this.user = null;
            this.mode = 'borrow';
            this.loginMethod = 'qr';
            this.statusMessage = 'Scan kartu member atau login manual';
            this.manualData = { nis: '', pin: '' };
            this.resetTimeout();
        }
    }
}
</script>
@endpush
@endsection
