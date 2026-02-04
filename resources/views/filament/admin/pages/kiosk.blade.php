<x-filament-panels::page>
    <div x-data="kioskSystem()" x-init="init()" class="bg-gray-900 rounded-xl p-6 min-h-[600px] relative">
        
        <!-- Timeout Progress Bar -->
        <div x-show="user" class="absolute top-0 left-0 h-1 bg-primary-500 transition-all duration-100 rounded-t-xl" :style="'width: ' + (timerPercent) + '%'"></div>

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-white">Self-Service Kiosk</h2>
                <p class="text-gray-400 text-sm" x-text="statusMessage"></p>
            </div>
            <div x-show="user" class="flex items-center gap-4">
                <span class="text-gray-300 text-sm">Sesi: <span x-text="Math.ceil(timer / 10)"></span>s</span>
                <button @click="logout()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm">
                    Logout
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left: Scanner -->
            <div>
                <div x-show="(loginMethod === 'qr' && !user) || (user && inputMethod === 'qr')" class="relative w-full aspect-square bg-black rounded-xl overflow-hidden border-2 border-gray-700">
                    <div id="reader" class="w-full h-full"></div>
                    <div class="absolute inset-0 pointer-events-none border-2 border-primary-500/30 rounded-xl">
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 border-2 border-white/20 rounded-lg"></div>
                    </div>
                    <!-- Toggle Manual Button Overlay -->
                     <button x-show="user" @click="inputMethod = 'manual'" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 bg-gray-800/80 hover:bg-gray-800 text-white rounded-full text-sm backdrop-blur-sm border border-gray-600 pointer-events-auto">
                        Input Kode Manual ⌨️
                    </button>
                </div>

                <!-- Manual Book Input Form (Logged In) -->
                <div x-show="user && inputMethod === 'manual'" class="bg-gray-800 p-6 rounded-xl space-y-4" x-transition>
                    <h3 class="text-lg font-bold text-white">Input Kode Buku</h3>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Kode Item Buku</label>
                        <input x-model="manualBookCode" type="text" class="w-full bg-gray-700 border-gray-600 rounded-lg py-2 px-3 text-white" placeholder="Contoh: 12-20240201-ABCD1">
                    </div>
                    <button @click="processBookManual()" class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-bold">
                        Proses
                    </button>
                    <button @click="inputMethod = 'qr'" class="w-full text-primary-400 hover:text-primary-300 text-sm">
                        ← Gunakan Scan QR
                    </button>
                </div>

                <!-- Manual Login Form (Not Logged In) -->
                <div x-show="!user && loginMethod === 'manual'" class="bg-gray-800 p-6 rounded-xl space-y-4" x-transition>
                    <h3 class="text-lg font-bold text-white">Login Manual</h3>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">NIS / Email</label>
                        <input x-model="manualData.nis" type="text" class="w-full bg-gray-700 border-gray-600 rounded-lg py-2 px-3 text-white">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">PIN (6 Digit)</label>
                        <input x-model="manualData.pin" type="password" maxlength="6" class="w-full bg-gray-700 border-gray-600 rounded-lg py-2 px-3 text-white tracking-widest">
                    </div>
                    <button @click="loginUserManual()" class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-bold">
                        Masuk
                    </button>
                    <button @click="loginMethod = 'qr'" class="w-full text-primary-400 hover:text-primary-300 text-sm">
                        ← Gunakan Scan QR
                    </button>
                </div>
            </div>

            <!-- Right: Info & Controls -->
            <div class="flex flex-col justify-center">
                <!-- Not logged in -->
                <div x-show="!user && loginMethod === 'qr'" class="text-center space-y-4" x-transition>
                    <div class="p-6 bg-primary-600/10 border border-primary-500/20 rounded-xl">
                        <svg class="h-16 w-16 text-primary-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <p class="text-white font-medium mb-4">Scan kartu member untuk mulai!</p>
                        <button @click="loginMethod = 'manual'" class="text-primary-400 hover:underline text-sm">
                            Login Manual →
                        </button>
                    </div>
                </div>

                <!-- Logged in -->
                <div x-show="user" class="space-y-4" x-transition @click="resetTimeout()">
                    <!-- User Card -->
                    <div class="p-4 bg-gray-800 rounded-xl flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-primary-500 to-purple-500 flex items-center justify-center text-xl font-bold text-white">
                            <span x-text="user?.name?.[0]"></span>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Selamat datang,</p>
                            <h3 class="text-xl font-bold text-white" x-text="user?.name"></h3>
                        </div>
                    </div>

                    <!-- Mode Selection -->
                    <div class="space-y-3">
                        <button @click="mode = 'borrow'; statusMessage = 'Scan QR Buku untuk meminjam'"
                            :class="mode === 'borrow' ? 'bg-primary-600 ring-2 ring-primary-500/50' : 'bg-gray-800 hover:bg-gray-700'"
                            class="w-full p-4 rounded-xl font-bold flex items-center gap-4 transition">
                            <span class="text-2xl">📖</span>
                            <div class="text-left">
                                <div class="text-white">Pinjam Buku</div>
                                <div class="text-xs text-gray-300 font-normal">Scan buku yang ingin dipinjam</div>
                            </div>
                        </button>
                        <button @click="mode = 'return'; statusMessage = 'Scan QR Buku untuk mengembalikan'"
                            :class="mode === 'return' ? 'bg-purple-600 ring-2 ring-purple-500/50' : 'bg-gray-800 hover:bg-gray-700'"
                            class="w-full p-4 rounded-xl font-bold flex items-center gap-4 transition">
                            <span class="text-2xl">↩️</span>
                            <div class="text-left">
                                <div class="text-white">Kembalikan Buku</div>
                                <div class="text-xs text-gray-300 font-normal">Scan buku yang ingin dikembalikan</div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
    function kioskSystem() {
        return {
            scanner: null,
            user: null,
            mode: 'borrow',
            loginMethod: 'qr',
            inputMethod: 'qr',
            statusMessage: 'Scan kartu member atau login manual',
            isProcessing: false,
            manualData: { nis: '', pin: '' },
            manualBookCode: '',
            timer: 900,
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
                    this.statusMessage = "Gagal akses kamera. Gunakan login manual.";
                    this.loginMethod = 'manual';
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
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify(body)
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.user = data.user;
                        this.statusMessage = "Berhasil masuk! Pilih aksi di bawah.";
                        this.resetTimeout();
                    } else {
                        this.statusMessage = "Gagal: " + data.message;
                    }
                } catch (e) {
                    this.statusMessage = "Koneksi bermasalah";
                }
            },

            async processBook(qrContent) {
                const endpoint = this.mode === 'borrow' ? '/api/kiosk/borrow' : '/api/kiosk/return';
                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Authorization': 'Bearer ' + this.user.token 
                        },
                        body: JSON.stringify({ book_qr: qrContent })
                    });

                    const data = await response.json();
                    this.statusMessage = data.success ? "✓ " + data.message : "✗ " + data.message;
                    this.resetTimeout();
                } catch (e) {
                    this.statusMessage = "Koneksi bermasalah";
                }
            },

            async processBookManual() {
                if (!this.manualBookCode) {
                    this.statusMessage = "Kode buku harus diisi!";
                    return;
                }
                await this.processBook(this.manualBookCode);
                this.manualBookCode = '';
            },

            logout() {
                this.user = null;
                this.mode = 'borrow';
                this.loginMethod = 'qr';
                this.inputMethod = 'qr';
                this.statusMessage = 'Scan kartu member atau login manual';
                this.manualData = { nis: '', pin: '' };
                this.resetTimeout();
            }
        }
    }
    </script>
    @endpush
</x-filament-panels::page>
