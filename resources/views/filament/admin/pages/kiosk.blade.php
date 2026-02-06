<x-filament-panels::page>
    <style>
        #reader video, #reader-book video {
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
        }
        /* Hide the library's own scanning box to use our custom one */
        #reader__scan_region, #reader-book__scan_region {
            border: none !important;
        }
        /* Ensure the container is truly square */
        .scanner-container {
            aspect-ratio: 1 / 1;
        }
    </style>

    <div x-data="kioskSystem()" x-init="init()" class="bg-gray-900 rounded-xl p-6 min-h-[600px] relative">
        
        <!-- Timeout Progress Bar -->
        <div x-show="user" class="absolute top-0 left-0 h-1 bg-primary-500 transition-all duration-100 rounded-t-xl" :style="'width: ' + (timerPercent) + '%'"></div>

        <!-- Header -->
        <div class="flex justify-between flex-wrap gap-4 items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-white">Self-Service Kiosk</h2>
                <p class="text-sm transition-colors duration-300" :class="statusColor" x-text="statusMessage"></p>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Camera Selection (Always Available) -->
                <div x-show="cameras.length > 0" class="flex items-center gap-2 bg-gray-800/80 backdrop-blur px-3 py-1.5 rounded-lg border border-gray-700 shadow-sm transition-all hover:border-primary-500/50">
                    <svg class="w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <select x-model="selectedCameraId" @change="restartScanner()" class="bg-transparent border-none text-xs text-gray-300 focus:ring-0 cursor-pointer p-0 pr-4 font-bold">
                        <template x-for="cam in cameras" :key="cam.id">
                            <option :value="cam.id" x-text="cam.label || 'Kamera ' + cam.id" class="bg-gray-800"></option>
                        </template>
                    </select>
                </div>

                <!-- User Session (Login only) -->
                <div x-show="user" class="flex items-center gap-4">
                    <span class="text-gray-300 text-xs">Sesi: <span class="font-mono text-primary-400" x-text="Math.ceil(timer / 10)"></span>s</span>
                    <button @click="logout()" class="px-3 py-1.5 bg-red-600/20 hover:bg-red-600 text-red-500 hover:text-white border border-red-600/30 rounded-lg text-xs font-bold transition-all active:scale-95">
                        Logout
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto">
            <!-- STEP 1: LOGIN -->
            <div x-show="step === 'login'" class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center" x-transition>
                <div class="space-y-6">
                    <div class="text-center lg:text-left">
                        <h3 class="text-3xl font-extrabold text-white mb-2">Selamat Datang 👋</h3>
                        <p class="text-gray-400">Silahkan login untuk mulai meminjam atau mengembalikan buku.</p>
                    </div>

                    <!-- Manual Login Option Button -->
                    <button x-show="loginMethod === 'qr'" @click="loginMethod = 'manual'" class="w-full py-4 bg-gray-800 hover:bg-gray-700 text-white rounded-xl border border-gray-700 flex items-center justify-center gap-3 transition">
                        <span>⌨️</span> Login Manual dengan NIS/Email
                    </button>

                    <!-- Manual Login Form -->
                    <div x-show="loginMethod === 'manual'" class="bg-gray-800 p-6 rounded-xl space-y-4 border border-gray-700 shadow-xl" x-transition>
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-lg font-bold text-white">Login Manual</h4>
                            <button @click="loginMethod = 'qr'" class="text-primary-400 text-xs hover:underline">← Gunakan Scan QR</button>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">NIS / Email</label>
                            <input x-model="manualData.nis" type="text" class="w-full bg-gray-700 border-gray-600 focus:border-primary-500 focus:ring-primary-500 rounded-lg py-2.5 px-3 text-white transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">PIN (6 Digit)</label>
                            <input x-model="manualData.pin" type="password" maxlength="6" class="w-full bg-gray-700 border-gray-600 focus:border-primary-500 focus:ring-primary-500 rounded-lg py-2.5 px-3 text-white tracking-[1em] font-mono text-center">
                        </div>
                        <button @click="loginUserManual()" class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-bold shadow-lg shadow-primary-500/20 active:scale-95 transition">
                            Masuk
                        </button>
                    </div>
                </div>

                <div class="relative group">
                    <div x-show="loginMethod === 'qr'" class="scanner-container relative w-full bg-black rounded-2xl overflow-hidden border-4 border-gray-800 group-hover:border-primary-500/30 transition-colors shadow-2xl">
                        <div id="reader" class="w-full h-full"></div>
                        <div class="absolute inset-0 pointer-events-none border-2 border-primary-500/20 rounded-xl">
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 border-2 border-white/30 rounded-lg">
                                <div class="absolute -top-1 -left-1 w-10 h-10 border-t-4 border-l-4 border-primary-500"></div>
                                <div class="absolute -top-1 -right-1 w-10 h-10 border-t-4 border-r-4 border-primary-500"></div>
                                <div class="absolute -bottom-1 -left-1 w-10 h-10 border-b-4 border-l-4 border-primary-500"></div>
                                <div class="absolute -bottom-1 -right-1 w-10 h-10 border-b-4 border-r-4 border-primary-500"></div>
                            </div>
                        </div>
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 bg-gray-900/80 backdrop-blur px-4 py-2 rounded-full text-white text-xs whitespace-nowrap">
                            Scan Kartu Member Anda
                        </div>
                    </div>
                    <div x-show="loginMethod === 'manual'" class="hidden lg:flex flex-col items-center justify-center aspect-square bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-700 text-gray-500">
                        <svg class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        <p>Mode Input Manual Aktif</p>
                    </div>
                </div>
            </div>

            <!-- STEP 2: MODE SELECTION -->
            <div x-show="step === 'action_selection'" class="space-y-8 py-8" x-transition>
                <div class="text-center space-y-2">
                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-gray-800 rounded-full border border-gray-700 mb-4">
                        <div class="h-6 w-6 rounded-full bg-gradient-to-br from-primary-500 to-purple-500 flex items-center justify-center text-[10px] font-bold text-white">
                            <span x-text="user?.name?.[0]"></span>
                        </div>
                        <span class="text-gray-300 font-medium">Hai, <span x-text="user?.name"></span>!</span>
                    </div>
                    <h3 class="text-4xl font-extrabold text-white">Apa yang ingin kamu lakukan?</h3>
                    <p class="text-gray-400">Pilih salah satu layanan di bawah ini</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-4">
                    <button @click="setMode('borrow')" class="group relative bg-gray-800 hover:bg-primary-600 p-8 rounded-3xl transition-all duration-300 text-left overflow-hidden border border-gray-700 hover:border-primary-400 hover:shadow-2xl hover:shadow-primary-500/20 active:scale-[0.98]">
                        <div class="absolute -right-4 -bottom-4 text-9xl opacity-10 group-hover:scale-110 transition-transform duration-500">📖</div>
                        <div class="relative z-10 space-y-4">
                            <div class="h-16 w-16 bg-primary-500/20 rounded-2xl flex items-center justify-center text-4xl group-hover:bg-white/20 transition-colors">📖</div>
                            <div>
                                <h4 class="text-2xl font-bold text-white mb-2">Pinjam Buku</h4>
                                <p class="text-gray-400 group-hover:text-primary-100 transition-colors">Telusuri dan bawa pulang buku favoritmu hari ini.</p>
                            </div>
                            <div class="pt-4 flex items-center text-primary-400 group-hover:text-white font-bold">
                                Mulai Sekarang <span class="ml-2 group-hover:translate-x-2 transition-transform">→</span>
                            </div>
                        </div>
                    </button>

                    <button @click="setMode('return')" class="group relative bg-gray-800 hover:bg-purple-600 p-8 rounded-3xl transition-all duration-300 text-left overflow-hidden border border-gray-700 hover:border-purple-400 hover:shadow-2xl hover:shadow-purple-500/20 active:scale-[0.98]">
                        <div class="absolute -right-4 -bottom-4 text-9xl opacity-10 group-hover:scale-110 transition-transform duration-500">↩️</div>
                        <div class="relative z-10 space-y-4">
                            <div class="h-16 w-16 bg-purple-500/20 rounded-2xl flex items-center justify-center text-4xl group-hover:bg-white/20 transition-colors">↩️</div>
                            <div>
                                <h4 class="text-2xl font-bold text-white mb-2">Kembalikan Buku</h4>
                                <p class="text-gray-400 group-hover:text-purple-100 transition-colors">Selesai membaca? Kembalikan buku dengan mudah di sini.</p>
                            </div>
                            <div class="pt-4 flex items-center text-purple-400 group-hover:text-white font-bold">
                                Mulai Sekarang <span class="ml-2 group-hover:translate-x-2 transition-transform">→</span>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- STEP 3: BOOK INPUT -->
            <div x-show="step === 'book_input'" class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start py-4" x-transition>
                <div class="space-y-6">
                    <div>
                        <button @click="step = 'action_selection'" class="text-gray-400 hover:text-white flex items-center gap-2 mb-4 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Ganti Aksi
                        </button>
                        <h3 class="text-3xl font-extrabold text-white mb-2" x-text="mode === 'borrow' ? 'Pinjam Buku' : 'Kembalikan Buku'"></h3>
                        <p class="text-gray-400" x-text="mode === 'borrow' ? 'Scan QR buku atau masukkan kode item untuk meminjam.' : 'Scan QR buku yang ingin dikembalikan.'"></p>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-xl bg-primary-500/10 flex items-center justify-center text-2xl" x-text="mode === 'borrow' ? '📖' : '↩️'"></div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Aksi Saat Ini</p>
                                <p class="text-white font-bold" x-text="mode === 'borrow' ? 'Sedang Meminjam...' : 'Sedang Mengembalikan...'"></p>
                            </div>
                        </div>

                         <!-- Toggle Manual Entry Form -->
                        <div class="pt-4 border-t border-gray-700">
                             <div x-show="inputMethod === 'qr'" class="space-y-4">
                                <p class="text-sm text-center text-gray-400">Kesulitan mendeteksi QR?</p>
                                <button @click="inputMethod = 'manual'" class="w-full py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-xl border border-gray-600 transition">
                                    ⌨️ Masukkan Kode Manual
                                </button>
                             </div>

                             <div x-show="inputMethod === 'manual'" class="space-y-4" x-transition>
                                <div class="flex justify-between items-center">
                                    <label class="block text-sm font-medium text-gray-300">Kode Item Buku</label>
                                    <button @click="inputMethod = 'qr'" class="text-primary-400 text-xs hover:underline">← Gunakan QR</button>
                                </div>
                                 <input x-model="manualBookCode" 
                                        @keyup.enter="processBookManual()"
                                        type="text" placeholder="Contoh: 12-20240201-ABCD1" 
                                        class="w-full bg-gray-900 border-gray-700 focus:border-primary-500 focus:ring-primary-500 rounded-lg py-3 px-4 text-white">
                                 <button @click="processBookManual()" class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition">
                                     Proses Buku
                                 </button>
                                 <p x-show="statusMessage" 
                                    class="text-sm text-center mt-2 transition-all duration-300" 
                                    :class="statusColor"
                                    x-text="statusMessage"></p>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- QR Scanner Area (Step 3) -->
                <div>
                    <div x-show="inputMethod === 'qr'" class="scanner-container relative w-full bg-black rounded-2xl overflow-hidden border-4 border-gray-800 shadow-2xl">
                        <!-- We use the SAME reader ID so we only need one scanner instance -->
                        <div id="reader-book" class="w-full h-full"></div>
                        <div class="absolute inset-0 pointer-events-none border-2 border-primary-500/20 rounded-xl">
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 border-2 border-white/30 rounded-lg">
                                <div class="absolute -top-1 -left-1 w-10 h-10 border-t-4 border-l-4 border-primary-500"></div>
                                <div class="absolute -top-1 -right-1 w-10 h-10 border-t-4 border-r-4 border-primary-500"></div>
                                <div class="absolute -bottom-1 -left-1 w-10 h-10 border-b-4 border-l-4 border-primary-500"></div>
                                <div class="absolute -bottom-1 -right-1 w-10 h-10 border-b-4 border-r-4 border-primary-500"></div>
                            </div>
                        </div>
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 bg-gray-900/80 backdrop-blur px-6 py-2 rounded-full text-white text-xs whitespace-nowrap flex items-center gap-2">
                             <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                            </span>
                            Scan QR Buku Sekarang
                        </div>
                    </div>
                    <div x-show="inputMethod === 'manual'" class="hidden lg:flex flex-col items-center justify-center aspect-square bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-700 text-gray-500">
                        <svg class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p>Input Manual Sedang Digunakan</p>
                    </div>
        </div>
    </div>

    <!-- Modal Feedback Overlay -->
    <div x-show="modal.show" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="closeModal()" 
             class="bg-gray-800 border border-gray-700 rounded-3xl p-8 max-w-sm w-full shadow-2xl transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full mb-6"
                     :class="{
                        'bg-green-500/20 text-green-400': modal.type === 'success',
                        'bg-red-500/20 text-red-400': modal.type === 'error',
                        'bg-blue-500/20 text-blue-400': modal.type === 'info'
                     }">
                    <template x-if="modal.type === 'success'">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </template>
                    <template x-if="modal.type === 'error'">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </template>
                    <template x-if="modal.type === 'info'">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                </div>
                
                <h3 class="text-2xl font-bold text-white mb-2" x-text="modal.title"></h3>
                <p class="text-gray-400 mb-8" x-text="modal.message"></p>
                
                <button @click="closeModal()" 
                        class="w-full py-4 rounded-xl font-bold transition-all active:scale-95 shadow-lg"
                        :class="{
                            'bg-green-600 hover:bg-green-700 text-white shadow-green-500/20': modal.type === 'success',
                            'bg-red-600 hover:bg-red-700 text-white shadow-red-500/20': modal.type === 'error',
                            'bg-primary-600 hover:bg-primary-700 text-white shadow-primary-500/20': modal.type === 'info'
                        }">
                    Tutup
                </button>
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
            step: 'login', // login, action_selection, book_input
            mode: 'borrow',
            loginMethod: 'qr',
            inputMethod: 'qr',
            statusMessage: 'Scan kartu member atau login manual',
            statusColor: 'text-gray-400',
            isProcessing: false,
            manualData: { nis: '', pin: '' },
            manualBookCode: '',
            lastScan: { content: null, time: 0 },
            cameras: [],
            selectedCameraId: null,
            modal: {
                show: false,
                type: 'success', // success | error | info
                title: '',
                message: ''
            },
            timer: 900,
            timeoutId: null,

            get timerPercent() {
                return (this.timer / 900) * 100;
            },

            init() {
                this.loadCameras();
                this.initScanner("reader");
                this.startTimer();

                // Re-init scanner if step changes to book_input to ensure it's on the right element if needed
                // actually we'll just handle it by starting/stopping based on step
                this.$watch('step', (val) => {
                    this.statusMessage = val === 'login' ? 'Silahkan login terlebih dahulu' : 
                                       (val === 'action_selection' ? 'Pilih aksi yang diinginkan' : 'Silahkan scan buku');
                    
                    this.stopScanner().then(() => {
                        if (val === 'login' && this.loginMethod === 'qr') {
                            this.initScanner("reader");
                        } else if (val === 'book_input' && this.inputMethod === 'qr') {
                            this.initScanner("reader-book");
                        }
                    });
                });

                this.$watch('loginMethod', (val) => {
                    if (val === 'qr' && this.step === 'login') {
                        this.initScanner("reader");
                    } else {
                        this.stopScanner();
                    }
                });

                this.$watch('inputMethod', (val) => {
                    if (val === 'qr' && this.step === 'book_input') {
                        this.initScanner("reader-book");
                    } else {
                        this.stopScanner();
                    }
                });
            },

            async loadCameras() {
                try {
                    const devices = await Html5Qrcode.getCameras();
                    if (devices && devices.length > 0) {
                        this.cameras = devices;
                        // Default to rear camera if possible
                        const backCam = devices.find(d => d.label.toLowerCase().includes('back'));
                        this.selectedCameraId = backCam ? backCam.id : devices[0].id;
                    }
                } catch (e) {
                    console.error("Gagal mendapatkan daftar kamera:", e);
                }
            },

            async restartScanner() {
                const elementId = this.step === 'login' ? 'reader' : (this.step === 'book_input' ? 'reader-book' : null);
                if (elementId) {
                    await this.initScanner(elementId);
                }
            },

            async initScanner(elementId) {
                await this.stopScanner();
                this.scanner = new Html5Qrcode(elementId);
                
                const config = { 
                    fps: 10, 
                    qrbox: { width: 300, height: 300 },
                    aspectRatio: 1.0 
                };

                const startConfig = this.selectedCameraId 
                    ? this.selectedCameraId 
                    : { facingMode: "environment" };

                this.scanner.start(
                    startConfig, 
                    config,
                    (decodedText) => this.handleScan(decodedText)
                ).catch(err => {
                    console.error("Scanner error:", err);
                    if (this.step === 'login') {
                        this.statusMessage = "Gagal akses kamera. Gunakan login manual.";
                        this.loginMethod = 'manual';
                    }
                });
            },

            async stopScanner() {
                if (this.scanner && this.scanner.isScanning) {
                    try {
                        await this.scanner.stop();
                    } catch (e) {}
                }
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
                if (this.isProcessing || this.modal.show) return;
                
                // Cooldown: Ignore same content within 5 seconds
                const now = Date.now();
                if (this.lastScan.content === content && (now - this.lastScan.time) < 5000) {
                    return;
                }

                this.resetTimeout();
                this.isProcessing = true;
                this.lastScan = { content: content, time: now };

                try {
                    if (this.step === 'login') {
                        await this.loginWithQR(content);
                    } else if (this.step === 'book_input') {
                        await this.processBook(content);
                    }
                } finally {
                    setTimeout(() => { this.isProcessing = false; }, 2000);
                }
            },

            showModal(type, title, message) {
                this.modal = { show: true, type, title, message };
                this.statusMessage = message; 
                this.statusColor = type === 'error' ? 'text-red-400 font-bold' : 'text-green-400 font-bold';
                
                // If error, also clear last scan so user can retry immediately if they want after closing
                if (type === 'error') {
                    this.lastScan = { content: null, time: 0 };
                }
            },

            closeModal() {
                this.modal.show = false;
                this.resetTimeout();
            },


            async fetchApi(endpoint, body = {}, useAuth = false) {
                try {
                    console.log(`[Kiosk] Fetching ${endpoint}...`, body);
                    const baseUrl = window.location.origin + '/' + endpoint.replace(/^\//, '');
                    const apiUrl = baseUrl + (baseUrl.includes('?') ? '&' : '?') + 'v=' + Date.now();
                    
                    const headers = { 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    };

                    if (useAuth && this.user?.token) {
                        headers['Authorization'] = 'Bearer ' + this.user.token;
                    }

                    console.log(`[Kiosk] Sending Body:`, JSON.stringify(body));

                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify(body)
                    });

                    console.log(`[Kiosk] Response Received:`, response.status, response.statusText);
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('[Kiosk] API Error Raw:', errorText);
                        let errorData = { message: 'HTTP Error ' + response.status };
                        try { errorData = JSON.parse(errorText); } catch(e) {}
                        
                        this.showModal('error', 'API Error', errorData.message || 'Error ' + response.status);
                        return null;
                    }

                    const data = await response.json();
                    console.log('[Kiosk] API Success:', data);
                    this.statusColor = 'text-green-400 font-bold';
                    return data;
                } catch (e) {
                    console.error("[Kiosk] Network/JS Error:", e);
                    this.showModal('error', 'Network Error', e.message);
                    return null;
                }
            },

            async loginUserManual() {
                if (!this.manualData.nis || !this.manualData.pin) {
                    this.statusMessage = "NIS dan PIN harus diisi!";
                    return;
                }
                const data = await this.fetchApi('/api/kiosk/login', this.manualData);
                if (data?.success) {
                    this.user = data.user;
                    this.step = 'action_selection';
                    this.statusMessage = "Berhasil masuk! Selamat datang, " + data.user.name;
                    this.resetTimeout();
                }
            },

            async loginWithQR(qrContent) {
                const data = await this.fetchApi('/api/kiosk/login', { qr_code: qrContent });
                if (data?.success) {
                    this.user = data.user;
                    this.step = 'action_selection';
                    this.statusMessage = "Berhasil masuk! Selamat datang, " + data.user.name;
                    this.resetTimeout();
                }
            },

            setMode(val) {
                this.mode = val;
                this.step = 'book_input';
                this.resetTimeout();
            },

            async processBook(qrContent) {
                const endpoint = this.mode === 'borrow' ? '/api/kiosk/borrow' : '/api/kiosk/return';
                this.statusMessage = "Memproses buku...";
                this.statusColor = 'text-blue-400 animate-pulse';
                
                const data = await this.fetchApi(endpoint, { book_qr: qrContent }, true);
                
                if (data?.success) {
                    this.showModal('success', 'Berhasil!', data.message);
                    setTimeout(() => {
                        this.closeModal();
                        this.logout();
                    }, 3000);
                } else if (data) {
                    this.showModal('error', 'Gagal', data.message || 'Respons tidak valid');
                }
                this.resetTimeout();
            },

            async processBookManual() {
                console.log('[Kiosk] Manual processing for:', this.manualBookCode);
                if (!this.manualBookCode) {
                    this.statusMessage = "✗ Gagal: Kode buku harus diisi!";
                    return;
                }
                const code = this.manualBookCode;
                this.manualBookCode = ''; // Clear it early to avoid double submissions
                await this.processBook(code);
            },

            logout() {
                this.user = null;
                this.step = 'login';
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
