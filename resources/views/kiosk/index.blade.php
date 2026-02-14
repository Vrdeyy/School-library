@extends('layouts.kiosk')

@section('content')
<style>
    /* Custom Scanner & UI Overrides */
    #reader video, #reader-book video, #reader-admin video {
        object-fit: cover !important;
        width: 100% !important;
        height: 100% !important;
        border-radius: 1.5rem;
    }
    #reader__scan_region, #reader-book__scan_region, #reader-admin__scan_region {
        border: none !important;
    }
    [x-cloak] { display: none !important; }
    
    @keyframes scan {
        0%, 100% { top: 0; }
        50% { top: 100%; }
    }
    .animate-scan {
        position: absolute;
        width: 100%;
        height: 4px;
        background: linear-gradient(to bottom, transparent, #3b82f6);
        box-shadow: 0 0 15px #3b82f6;
        animation: scan 3s linear infinite;
        z-index: 10;
    }
</style>

<div x-data="kioskSystem" x-cloak class="flex-grow flex flex-col">
    
    <!-- ==========================================
         SCREEN 1: WELCOME & ADMIN ACTIVATION
         ========================================== -->
    <template x-if="!isKioskActive">
        <div class="flex-grow flex flex-col items-center justify-center p-4" 
             x-transition:enter="transition ease-out duration-500" 
             x-transition:enter-start="opacity-0 scale-95" 
             x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Branding (Comic Style) -->
            <div class="text-center mb-8 sm:mb-16 relative">
                <!-- Decorative zig-zag behind logo -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[140%] h-8 sm:h-12 zig-zag opacity-5 -rotate-6"></div>
                
                <div class="relative inline-flex flex-col items-center gap-4 sm:gap-6">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-white border-2 sm:border-4 border-slate-900 shadow-[4px_4px_0px_#2563eb] sm:shadow-[8px_8px_0px_#2563eb] rounded-xl sm:rounded-2xl flex items-center justify-center p-3 sm:p-4 animate-float-gentle relative">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="max-w-full max-h-full object-contain">
                        <!-- Tiny burst badge -->
                        <div class="absolute -top-2 -right-2 sm:-top-4 sm:-right-4 w-7 h-7 sm:w-10 sm:h-10 bg-blue-600 comic-burst border-2 border-slate-900 flex items-center justify-center rotate-12">
                            <span class="text-[6px] sm:text-[8px] font-black text-white italic">KIOSK</span>
                        </div>
                    </div>
                    <div class="space-y-1 sm:space-y-2">
                        <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tighter text-slate-900 leading-none uppercase italic chromatic-offset">PERPUSTAKAAN</h1>
                        <p class="text-xs sm:text-lg md:text-xl font-black text-blue-600 tracking-[0.2em] sm:tracking-[0.4em] uppercase italic flex items-center justify-center gap-2 sm:gap-4">
                            <span class="h-[1px] sm:h-[2px] w-4 sm:w-8 bg-blue-600"></span>
                            SMK YAJ DEPOK
                            <span class="h-[1px] sm:h-[2px] w-4 sm:w-8 bg-blue-600"></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Login Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-12 w-full max-w-6xl">
                <!-- Manual Admin Login -->
                <div class="bg-white/90 backdrop-blur-xl panel-border p-6 sm:p-12 rounded-2xl sm:rounded-3xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-blue-100/40 -mr-8 -mt-8 sm:-mr-10 sm:-mt-10 rounded-full blur-2xl sm:blur-3xl benday-dots transition-all"></div>
                    
                    <div class="relative z-10 space-y-6 sm:space-y-8">
                        <div class="flex items-center gap-3 sm:gap-5">
                            <div class="bg-blue-600 text-white p-3 sm:p-4 rounded-lg sm:rounded-xl border-2 border-slate-900 shadow-[3px_3px_0px_#1e293b] sm:shadow-[4px_4px_0px_#1e293b]">
                                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tighter italic">ADMIN_LOGIN</h2>
                                <div class="h-1.5 w-12 sm:h-2 sm:w-16 bg-blue-600 mt-1"></div>
                            </div>
                        </div>
                        
                        <div class="space-y-4 sm:space-y-6">
                            <div class="space-y-2 sm:space-y-3">
                                <label class="text-[8px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">IDENTIFICATION_ID / EMAIL</label>
                                <input x-model="adminData.id_pengenal_siswa" 
                                       type="text" 
                                       placeholder="ID_REQUIRED..." 
                                       @keyup.enter="loginAdmin()"
                                       class="w-full bg-slate-50 border-[3px] sm:border-4 border-slate-900 rounded-xl sm:rounded-2xl py-3 sm:py-5 px-5 sm:px-8 text-sm sm:text-base text-slate-900 font-black focus:bg-white outline-none transition-all uppercase italic">
                            </div>
                            <div class="space-y-2 sm:space-y-3">
                                <label class="text-[8px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">SECURITY_PIN_TOKEN</label>
                                    <div class="flex gap-2 sm:gap-3 bg-white border-[3px] sm:border-4 border-slate-900 rounded-xl sm:rounded-2xl overflow-hidden shadow-[4px_4px_0px_#2563eb] sm:shadow-[8px_8px_0px_#2563eb] relative">
                                        <div class="bg-slate-100 text-slate-900 px-3 sm:px-5 py-3 sm:py-4 flex items-center border-r-[3px] sm:border-r-4 border-slate-900">
                                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                        </div>
                                        <input x-model="adminData.pin" 
                                               :type="showAdminPin ? 'text' : 'password'" 
                                               maxlength="6" 
                                               @keyup.enter="loginAdmin()"
                                               class="flex-1 bg-transparent py-3 sm:py-4 px-4 sm:px-6 text-slate-900 text-center tracking-[0.5em] sm:tracking-[1em] font-black text-xl sm:text-2xl outline-none">
                                        <button @click="showAdminPin = !showAdminPin" type="button" class="px-3 sm:px-4 text-slate-400 hover:text-slate-600 transition-colors">
                                            <template x-if="!showAdminPin">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </template>
                                            <template x-if="showAdminPin">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                            </template>
                                        </button>
                                    </div>
                            </div>
                            <button @click="loginAdmin()" :disabled="isProcessing" class="w-full py-4 sm:py-6 bg-slate-900 hover:bg-slate-800 text-white rounded-xl sm:rounded-2xl font-black text-base sm:text-xl transition-all shadow-[4px_4px_0px_#2563eb] sm:shadow-[8px_8px_0px_#2563eb] active:translate-x-1 active:translate-y-1 active:shadow-none disabled:opacity-50 uppercase italic tracking-widest">
                                <span x-show="!isProcessing">ACTIVATE_TERMINAL</span>
                                <span x-show="isProcessing">VERIFYING...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- QR Admin Login -->
                <div class="bg-slate-50 border-[3px] sm:border-4 border-slate-900 p-6 sm:p-12 rounded-2xl sm:rounded-3xl flex flex-col items-center justify-center text-center relative overflow-hidden shadow-[8px_8px_0px_#1e293b] sm:shadow-[12px_12px_0px_#1e293b]">
                    <!-- Ben-day background -->
                    <div class="absolute inset-0 opacity-[0.05] pointer-events-none benday-dots"></div>
                    
                    <div x-show="!adminQrActive" class="space-y-6 sm:space-y-8 relative z-10" x-transition>
                        <div class="w-20 h-20 sm:w-32 sm:h-32 bg-white border-[3px] sm:border-4 border-slate-900 rounded-2xl sm:rounded-[2.5rem] flex items-center justify-center mx-auto shadow-[6px_6px_0px_#2563eb] sm:shadow-[10px_10px_0px_#2563eb] animate-float-gentle">
                            <svg class="w-10 h-10 sm:w-16 sm:h-16 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl sm:text-3xl font-black text-slate-900 mb-2 sm:mb-3 uppercase italic tracking-tighter chromatic-offset">BADGE_SCAN</h3>
                            <p class="text-slate-500 text-[8px] sm:text-xs font-black max-w-[200px] sm:max-w-[240px] mx-auto uppercase tracking-widest leading-relaxed">Use your official administrator security badge for immediate authentication.</p>
                        </div>
                        <button @click="startAdminQr()" class="w-full sm:w-auto px-8 sm:px-12 py-4 sm:py-5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl sm:rounded-2xl font-black text-xs sm:text-sm uppercase tracking-[0.2em] sm:tracking-[0.3em] transition-all shadow-[6px_6px_0px_#1e293b] sm:shadow-[8px_8px_0px_#1e293b] hover:-translate-y-1 active:translate-y-1 active:shadow-none">
                            INITIALIZE_OPTIC
                        </button>
                    </div>

                    <div x-show="adminQrActive" class="w-full space-y-4 sm:space-y-6 relative z-10" x-transition>
                        <div class="relative aspect-square w-full max-w-sm mx-auto bg-slate-900 border-[6px] sm:border-8 border-slate-900 rounded-3xl sm:rounded-[3rem] overflow-hidden shadow-[8px_8px_0px_#2563eb] sm:shadow-[16px_16px_0px_#2563eb]">
                            <div id="reader-admin" class="w-full h-full"></div>
                            <!-- Comic scanline -->
                            <div class="absolute inset-0 pointer-events-none zig-zag opacity-20 h-4 sm:h-6"></div>
                        </div>
                        
                        <!-- Camera selection & Controls -->
                        <div x-show="cameras.length > 0" class="w-full max-w-sm mx-auto flex gap-2">
                            <select x-model="selectedCameraId" @change="restartScanner()" 
                                    class="flex-1 bg-white border-[3px] sm:border-4 border-slate-900 rounded-xl py-3 px-4 text-[8px] sm:text-xs font-black uppercase tracking-widest text-slate-900 outline-none transition-all">
                                <template x-for="camera in cameras" :key="camera.id">
                                    <option :value="camera.id" x-text="camera.label || 'Camera ' + camera.id"></option>
                                </template>
                            </select>
                            <button @click="loadCameras()" class="p-3 bg-white border-[3px] border-slate-900 rounded-xl hover:bg-slate-50 transition-all shadow-[2px_2px_0px_#1e293b] active:shadow-none active:translate-x-0.5 active:translate-y-0.5" title="Refresh Cameras">
                                <svg class="w-4 h-4 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            </button>
                            <button x-show="cameras.length > 1" @click="cycleCamera()" class="p-3 bg-blue-600 border-[3px] border-slate-900 rounded-xl text-white hover:bg-blue-500 transition-all shadow-[2px_2px_0px_#1e293b] active:shadow-none active:translate-x-0.5 active:translate-y-0.5" title="Flip Camera">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            </button>
                        </div>

                        <button @click="stopAdminQr()" class="text-[10px] sm:text-sm font-black text-red-600 hover:text-red-700 uppercase tracking-[0.3em] italic bg-white px-4 sm:px-6 py-2 border-2 border-slate-900 rounded-lg shadow-[3px_3px_0px_#ef4444] sm:shadow-[4px_4px_0px_#ef4444]">ABORT_SCAN</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- ==========================================
         SCREEN 2: TERMINAL ACTIVE (SHELL)
         ========================================== -->
    <template x-if="isKioskActive">
        <div class="flex-grow flex flex-col space-y-10 animate-fade-in px-2 sm:px-0">
            
            <!-- Terminal Header (Comic Style) -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 sm:gap-6 bg-white panel-border p-4 sm:p-6 md:p-8 rounded-2xl md:rounded-3xl relative overflow-hidden">
                <div class="absolute right-0 top-0 h-full w-[30%] bg-blue-100/50 rotate-12 -mr-10 -mt-10 blur-3xl benday-dots"></div>
                
                <div class="relative z-10 flex items-center gap-4 sm:gap-6">
                    <div class="h-12 w-12 sm:h-16 sm:w-16 bg-white border-2 border-slate-900 rounded-lg sm:rounded-xl flex items-center justify-center p-2 sm:p-3 shadow-[4px_4px_0px_#1e293b]">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="max-w-full max-h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <h2 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tighter uppercase italic leading-none chromatic-offset">OPERATOR_TERMINAL</h2>
                            <span class="bg-blue-600 text-white text-[8px] sm:text-[10px] font-black px-2 sm:px-3 py-1 rounded border-2 border-slate-900 uppercase tracking-widest shadow-[3px_3px_0px_#1e293b]">ACTIVE</span>
                        </div>
                        <p class="text-[8px] sm:text-[10px] font-black text-slate-400 tracking-[0.2em] sm:tracking-[0.3em] uppercase mt-1 sm:mt-2">Authenticated_As: <span class="text-blue-600" x-text="activeAdmin?.name"></span></p>
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-6 relative z-10 w-full md:w-auto mt-2 md:mt-0">
                    <div x-show="user" class="flex-grow md:flex-grow-0 flex items-center justify-center gap-2 sm:gap-3 px-3 sm:px-4 py-2 bg-slate-900 text-white rounded-lg border-2 border-slate-900 font-black italic text-[10px] sm:text-xs shadow-[4px_4px_0px_#2563eb]">
                        <span class="text-slate-400 uppercase text-[8px]">TIMER:</span>
                        <span class="text-blue-400" x-text="Math.ceil(timer / 10) + 's'"></span>
                    </div>
                    <button @click="closeKiosk()" class="flex-shrink-0 px-4 sm:px-6 py-2 bg-white border-[3px] border-red-600 text-red-600 hover:bg-red-600 hover:text-white rounded-lg sm:rounded-xl text-[8px] sm:text-[10px] font-black uppercase tracking-widest transition-all shadow-[4px_4px_0px_#ef4444] hover:shadow-none active:translate-x-1 active:translate-y-1">TERMINATE</button>
                </div>
            </div>

            <!-- TERMINAL CONTENT ROUTER -->
            <div class="flex-grow flex flex-col">
                
                <!-- STEP: MEMBER LOGIN -->
                <template x-if="step === 'login'">
                    <div class="flex-grow flex flex-col lg:flex-row gap-12 items-stretch" x-transition>
                        <!-- Method Select -->
                        <div class="flex-grow flex flex-col justify-center space-y-6 sm:space-y-12 lg:w-1/2">
                            <div class="space-y-2 sm:space-y-4">
                                <h2 class="text-3xl sm:text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-tight uppercase italic chromatic-offset">USER_AUTH_GATEWAY</h2>
                                <p class="text-[10px] sm:text-sm md:text-base text-slate-400 font-black uppercase tracking-[0.2em] sm:tracking-[0.3em] italic">Access secure library services using your digital credentials.</p>
                            </div>

                            <div class="space-y-6 max-w-lg">
                                <button @click="loginMethod = 'qr'" 
                                        :class="loginMethod === 'qr' ? 'bg-white border-blue-600 shadow-[6px_6px_0px_#2563eb] sm:shadow-[10px_10px_0px_#2563eb]' : 'bg-slate-50 border-slate-200 opacity-60'" 
                                        class="w-full p-4 sm:p-8 rounded-2xl sm:rounded-3xl flex items-center justify-between border-2 sm:border-4 transition-all duration-500 group relative overflow-hidden">
                                    <div class="absolute inset-0 opacity-[0.03] benday-dots"></div>
                                    <div class="flex items-center gap-4 sm:gap-6 relative z-10">
                                        <div :class="loginMethod === 'qr' ? 'bg-blue-600 text-white border-slate-900' : 'bg-slate-100 text-slate-400 border-slate-200'" class="p-3 sm:p-5 rounded-lg sm:rounded-2xl border-2 shadow-[3px_3px_0px_#1e293b] sm:shadow-[4px_4px_0px_#1e293b] transition-colors">
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-black text-slate-900 text-lg sm:text-2xl uppercase italic tracking-tighter">Badge_Optic_Scan</p>
                                            <p class="text-[8px] sm:text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] sm:tracking-[0.3em] mt-1">Instant Verification</p>
                                        </div>
                                    </div>
                                    <div x-show="loginMethod === 'qr'" class="w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-blue-600 animate-ping relative z-10"></div>
                                </button>

                                <button @click="loginMethod = 'manual'" 
                                        :class="loginMethod === 'manual' ? 'bg-white border-indigo-600 shadow-[6px_6px_0px_#4f46e5] sm:shadow-[10px_10px_0px_#4f46e5]' : 'bg-slate-50 border-slate-200 opacity-60'" 
                                        class="w-full p-4 sm:p-8 rounded-2xl sm:rounded-3xl flex items-center justify-between border-2 sm:border-4 transition-all duration-500 group relative overflow-hidden">
                                    <div class="absolute inset-0 opacity-[0.03] benday-dots"></div>
                                    <div class="flex items-center gap-4 sm:gap-6 relative z-10">
                                        <div :class="loginMethod === 'manual' ? 'bg-indigo-600 text-white border-slate-900' : 'bg-slate-100 text-slate-400 border-slate-200'" class="p-3 sm:p-5 rounded-lg sm:rounded-2xl border-2 shadow-[3px_3px_0px_#1e293b] sm:shadow-[4px_4px_0px_#1e293b] transition-colors">
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-black text-slate-900 text-lg sm:text-2xl uppercase italic tracking-tighter">Manual_Secure_Code</p>
                                            <p class="text-[8px] sm:text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] sm:tracking-[0.3em] mt-1">Keypad Entry</p>
                                        </div>
                                    </div>
                                    <div x-show="loginMethod === 'manual'" class="w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-indigo-600 animate-ping relative z-10"></div>
                                </button>
                                
                                <div x-show="loginMethod === 'manual'" class="pt-8 space-y-8 animate-fade-in relative z-10">
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] ml-1">MEMBER_IDENT_CODE</label>
                                        <input x-model="memberLoginData.id_pengenal_siswa" 
                                               type="text" 
                                               placeholder="ENTER_USER_ID..." 
                                               @keyup.enter="loginManual()"
                                               class="w-full bg-slate-50 border-4 border-slate-900 rounded-2xl py-5 px-8 text-xl font-black text-slate-900 placeholder:text-slate-300 focus:bg-white outline-none italic uppercase">
                                    </div>
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] ml-1">PERSONAL_SECURITY_PIN</label>
                                        <div class="flex gap-4 bg-white border-4 border-slate-900 rounded-2xl overflow-hidden shadow-[8px_8px_0px_#2563eb] relative">
                                            <div class="bg-slate-100 text-slate-900 px-6 py-5 flex items-center border-r-4 border-slate-900">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                            </div>
                                            <input x-model="memberLoginData.pin" 
                                                   :type="showUserPin ? 'text' : 'password'" 
                                                   maxlength="6" 
                                                   @keyup.enter="loginManual()"
                                                   class="flex-1 bg-transparent py-4 px-6 text-slate-900 text-center tracking-[0.8em] font-black text-3xl outline-none">
                                            <button @click="showUserPin = !showUserPin" type="button" class="px-6 text-slate-400 hover:text-slate-600 transition-colors">
                                                <template x-if="!showUserPin">
                                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                </template>
                                                <template x-if="showUserPin">
                                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                                </template>
                                            </button>
                                        </div>
                                    </div>
                                    <button @click="loginManual()" 
                                            :disabled="isProcessing"
                                            class="w-full py-6 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-black text-lg uppercase tracking-[0.3em] transition-all shadow-[8px_8px_0px_#1e293b] hover:-translate-y-1 active:translate-y-1 active:shadow-none italic">
                                        <span x-show="!isProcessing">EXECUTE_LOGIN</span>
                                        <span x-show="isProcessing">VERIFYING...</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Scanner View -->
                        <div class="flex-grow flex items-center justify-center lg:w-1/2">
                            <div class="bg-slate-50 border border-slate-300 p-10 rounded-[4rem] w-full max-w-lg aspect-square relative overflow-hidden shadow-2xl">
                                <!-- Tech grid background -->
                                <div class="absolute inset-0 opacity-[0.03] pointer-events-none" 
                                     style="background-image: linear-gradient(to right, #000 1px, transparent 1px), linear-gradient(to bottom, #000 1px, transparent 1px); background-size: 20px 20px;"></div>

                                <template x-if="loginMethod === 'qr'">
                                    <div class="w-full h-full relative z-10">
                                        <div class="w-full h-full bg-slate-900 rounded-[2.5rem] overflow-hidden border-4 border-slate-900 shadow-[12px_12px_0px_#2563eb] relative">
                                            <div id="reader" class="w-full h-full"></div>
                                            <div class="absolute inset-0 pointer-events-none border-2 border-blue-500/20 rounded-[2.5rem] m-10"></div>
                                            <div class="animate-scan"></div>
                                        </div>

                                        <!-- No camera selection here, follows Admin choice -->
                                    </div>
                                </template>
                                <template x-if="loginMethod === 'manual'">
                                    <div class="w-full h-full flex flex-col items-center justify-center text-center p-12 relative z-10">
                                        <div class="w-36 h-36 bg-blue-600/10 border-2 border-blue-600/30 rounded-[2.5rem] flex items-center justify-center text-blue-600 mb-8 shadow-2xl">
                                            <svg class="w-20 h-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                        </div>
                                        <h3 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">MANUAL_MODE</h3>
                                        <p class="text-slate-500 text-sm font-bold mt-4 uppercase tracking-widest">Awaiting cryptographic input from control panel.</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- STEP: ACTION SELECTION -->
                <template x-if="step === 'action_selection'">
                    <div class="flex-grow flex flex-col items-center justify-center py-6 sm:py-12 animate-fade-in" x-transition>
                        <div class="text-center mb-10 sm:mb-16 relative">
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[140%] h-12 sm:h-16 zig-zag opacity-5 -rotate-3"></div>
                            <h2 class="text-3xl sm:text-5xl md:text-7xl font-black text-slate-900 tracking-tighter uppercase italic mb-4 sm:mb-6 relative z-10 chromatic-offset">CORE_MODULE_SELECT</h2>
                            <p class="text-[10px] sm:text-base text-slate-400 font-black uppercase tracking-[0.2em] sm:tracking-[0.3em] italic relative z-10">Validated_User: <span class="text-blue-600 border-b-2 sm:border-b-4 border-slate-900 pb-1" x-text="user?.name"></span></p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-12 w-full max-w-6xl">
                            <!-- Borrow -->
                            <button @click="startAction('borrow')" class="group bg-white/90 backdrop-blur-xl panel-border p-6 sm:p-12 rounded-2xl sm:rounded-[3.5rem] text-left hover:border-blue-600 hover:shadow-[10px_10px_0px_#2563eb] sm:shadow-[16px_16px_0px_#2563eb] transition-all duration-500 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 sm:w-48 sm:h-48 bg-blue-100/50 -mr-12 -mt-12 sm:-mr-16 sm:-mt-16 rounded-full blur-2xl sm:blur-3xl benday-dots transition-all"></div>
                                
                                <div class="w-16 h-16 sm:w-24 sm:h-24 bg-blue-600 text-white rounded-xl sm:rounded-2xl flex items-center justify-center mb-6 sm:mb-10 border-2 sm:border-4 border-slate-900 shadow-[4px_4px_0px_#1e293b] sm:shadow-[6px_6px_0px_#1e293b] group-hover:scale-110 transition-transform relative z-10">
                                    <svg class="w-8 h-8 sm:w-12 sm:h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </div>
                                <h3 class="text-2xl sm:text-4xl font-black text-slate-900 uppercase italic tracking-tighter mb-2 sm:mb-4 relative z-10 chromatic-offset text-nowrap">BORROW_ITEM</h3>
                                <p class="text-slate-400 text-[10px] sm:text-sm font-black uppercase tracking-widest leading-relaxed pr-6 sm:pr-8 relative z-10 italic">Initiate new cryptographic loan transaction. Scans required for validation.</p>
                                <div class="mt-6 sm:mt-10 flex items-center gap-3 sm:gap-4 text-blue-600 text-[8px] sm:text-[10px] font-black uppercase tracking-[0.3em] sm:tracking-[0.4em] relative z-10">
                                    <span class="bg-slate-900 text-white px-3 py-1 sm:px-4 sm:py-2 border-2 border-slate-900 shadow-[2px_2px_0px_#1e293b] sm:shadow-[3px_3px_0px_#1e293b]">RUN_WORKFLOW</span><svg class="w-4 h-4 sm:w-6 sm:h-6 group-hover:translate-x-3 transition-transform text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </div>
                            </button>

                            <!-- Return -->
                            <button @click="startAction('return')" class="group bg-white/90 backdrop-blur-xl panel-border p-6 sm:p-12 rounded-2xl sm:rounded-[3.5rem] text-left hover:border-indigo-600 hover:shadow-[10px_10px_0px_#4f46e5] sm:shadow-[16px_16px_0px_#4f46e5] transition-all duration-500 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 sm:w-48 sm:h-48 bg-indigo-100/50 -mr-12 -mt-12 sm:-mr-16 sm:-mt-16 rounded-full blur-2xl sm:blur-3xl benday-dots transition-all"></div>
                                
                                <div class="w-16 h-16 sm:w-24 sm:h-24 bg-indigo-600 text-white rounded-xl sm:rounded-2xl flex items-center justify-center mb-6 sm:mb-10 border-2 sm:border-4 border-slate-900 shadow-[4px_4px_0px_#1e293b] sm:shadow-[6px_6px_0px_#1e293b] group-hover:scale-110 transition-transform relative z-10">
                                    <svg class="w-8 h-8 sm:w-12 sm:h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" /></svg>
                                </div>
                                <h3 class="text-2xl sm:text-4xl font-black text-slate-900 uppercase italic tracking-tighter mb-2 sm:mb-4 relative z-10 chromatic-offset text-nowrap">RETURN_ITEM</h3>
                                <p class="text-slate-400 text-[10px] sm:text-sm font-black uppercase tracking-widest leading-relaxed pr-6 sm:pr-8 relative z-10 italic">Deactivate active loan session. Items must be optically verified.</p>
                                <div class="mt-6 sm:mt-10 flex items-center gap-3 sm:gap-4 text-indigo-600 text-[8px] sm:text-[10px] font-black uppercase tracking-[0.3em] sm:tracking-[0.4em] relative z-10">
                                    <span class="bg-slate-900 text-white px-3 py-1 sm:px-4 sm:py-2 border-2 border-slate-900 shadow-[2px_2px_0px_#1e293b] sm:shadow-[3px_3px_0px_#1e293b]">VERIFY_STATE</span><svg class="w-4 h-4 sm:w-6 sm:h-6 group-hover:translate-x-3 transition-transform text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </div>
                            </button>
                        </div>
                        
                        <button @click="logout()" class="mt-8 sm:mt-16 text-[8px] sm:text-[10px] font-black text-slate-400 hover:text-red-600 uppercase tracking-[0.3em] sm:tracking-[0.4em] transition-colors border-b-2 border-transparent hover:border-red-600 pb-1 italic">TERMINATE_CURRENT_IDENTITY</button>
                    </div>
                </template>

                <!-- STEP: BOOK INPUT -->
                <template x-if="step === 'book_input'">
                    <div class="flex-grow flex flex-col animate-fade-in" x-transition>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 w-full max-w-6xl mx-auto flex-grow items-start">
                            <!-- Left: Details & Manual Input -->
                            <div class="bg-white/90 backdrop-blur-xl panel-border p-6 sm:p-12 rounded-2xl sm:rounded-3xl relative overflow-hidden group">
                                <div class="absolute top-0 right-0 w-32 h-32 sm:w-48 sm:h-48 bg-blue-100/40 -mr-12 -mt-12 sm:-mr-16 sm:-mt-16 rounded-full blur-2xl sm:blur-3xl benday-dots transition-all"></div>
                                
                                <div class="relative z-10 space-y-6 sm:space-y-8">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4 sm:gap-5">
                                            <div class="bg-blue-600 text-white p-3 sm:p-4 rounded-lg sm:rounded-xl border-2 border-slate-900 shadow-[3px_3px_0px_#1e293b] sm:shadow-[4px_4px_0px_#1e293b]">
                                                <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12h6m-6 4h6m2 5H7a2 2 0 00-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            </div>
                                            <div>
                                                <h2 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tighter italic chromatic-offset">ITEM_IDENTIFICATION</h2>
                                                <div class="h-1.5 w-12 sm:h-2 sm:w-16 bg-blue-600 mt-1"></div>
                                            </div>
                                        </div>
                                        <button @click="step = 'action_selection'" class="bg-white border-2 border-slate-900 p-2 sm:p-3 rounded-lg sm:rounded-xl shadow-[3px_3px_0px_#ef4444] hover:shadow-none active:translate-x-1 active:translate-y-1 transition-all group">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>

                                    <div class="space-y-4 sm:space-y-6">
                                        <div class="space-y-2 sm:space-y-3">
                                            <label class="text-[8px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">IDENTIFICATION_CODE / SERIAL</label>
                                            <div class="flex bg-slate-50 border-[3px] sm:border-4 border-slate-900 rounded-xl sm:rounded-2xl overflow-hidden shadow-[6px_6px_0px_#2563eb] sm:shadow-[8px_8px_0px_#2563eb]">
                                                <div class="bg-slate-100 text-slate-900 px-4 sm:px-6 py-3 sm:py-5 flex items-center border-r-[3px] sm:border-r-4 border-slate-900">
                                                    <span class="text-[8px] sm:text-[10px] font-black uppercase tracking-tighter italic">DATA_ID:</span>
                                                </div>
                                                <input x-model="bookInputData.code" 
                                                       type="text" 
                                                       placeholder="B-XXXXX-XX..." 
                                                       @keyup.enter="processBookManual()"
                                                       class="flex-1 bg-transparent py-3 sm:py-5 px-4 sm:px-8 text-lg sm:text-2xl font-black tracking-widest text-slate-900 uppercase italic outline-none">
                                            </div>
                                        </div>
                                        <button @click="processBookManual()" 
                                                :disabled="isProcessing || !bookInputData.code" 
                                                class="w-full py-4 sm:py-6 bg-slate-900 hover:bg-slate-800 text-white rounded-xl sm:rounded-2xl font-black text-base sm:text-xl shadow-[4px_4px_0px_#2563eb] sm:shadow-[8px_8px_0px_#2563eb] transition-all active:translate-x-1 active:translate-y-1 active:shadow-none disabled:opacity-50 uppercase italic tracking-widest">
                                            VERIFY_ITEM_DATA
                                        </button>
                                    </div>

                                    <!-- Recent Queue Area -->
                                    <div class="pt-8 border-t-4 border-dashed border-slate-200 space-y-6">
                                        <div class="flex justify-between items-center">
                                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-[0.4em]">BATCH_QUEUE_STATE</p>
                                            <span class="bg-blue-600 text-white px-4 py-1.5 border-2 border-slate-900 rounded-lg text-[10px] font-black shadow-[3px_3px_0px_#1e293b]" x-text="scannedBooks.length + ' PENDING'"></span>
                                        </div>
                                        <div class="flex flex-wrap gap-3">
                                            <template x-for="book in scannedBooks" :key="book.code">
                                                <div class="px-5 py-2.5 bg-white border-4 border-slate-900 rounded-xl text-[10px] font-black text-blue-600 italic shadow-[4px_4px_0px_#1e293b]" x-text="book.code"></div>
                                            </template>
                                            <template x-if="scannedBooks.length === 0">
                                                <p class="text-[10px] text-slate-300 font-black italic uppercase tracking-[0.5em] py-4 w-full text-center border-4 border-dotted border-slate-100 rounded-2xl">-- WAITING_FOR_OPTIC_INITIALIZATION --</p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Scanner Area -->
                            <div class="bg-slate-50 border-4 border-slate-900 p-12 rounded-3xl flex flex-col items-center justify-center text-center relative overflow-hidden shadow-[12px_12px_0px_#1e293b] min-h-[500px]">
                                <div class="absolute inset-0 opacity-[0.05] pointer-events-none benday-dots"></div>
                                
                                <div class="w-full space-y-8 relative z-10">
                                    <div class="space-y-3">
                                        <h3 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter chromatic-offset">OPTIC_SENSOR_SCAN</h3>
                                        <p class="text-slate-500 text-[10px] font-black max-w-[280px] mx-auto uppercase tracking-widest">Align item QR code with the optic sensor boundary for immediate verification.</p>
                                    </div>

                                    <div class="relative aspect-square w-full max-w-sm mx-auto bg-slate-900 border-8 border-slate-900 rounded-[3rem] overflow-hidden shadow-[16px_16px_0px_#2563eb]">
                                        <div id="reader-book" class="w-full h-full"></div>
                                        <div class="absolute inset-0 pointer-events-none zig-zag opacity-10 h-6"></div>
                                        <div class="absolute inset-0 pointer-events-none border-[32px] border-slate-900/10"></div>
                                        
                                        <!-- Animated Scan Line -->
                                        <div class="absolute w-full h-1 bg-blue-400/50 shadow-[0_0_15px_#60a5fa] animate-scan z-20"></div>
                                    </div>

                                    <!-- No camera selection here, follows Admin choice -->

                                    <div class="flex items-center justify-center gap-4">
                                        <div class="h-3 w-3 rounded-full bg-blue-600 animate-ping"></div>
                                        <span class="text-[10px] font-black text-slate-900 tracking-[0.4em] uppercase italic">OPTIC_POWER_ON</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- STEP: FINISHED / SUCCESS -->
                <template x-if="step === 'finished'">
                    <div class="flex-grow flex flex-col items-center justify-center p-6 sm:p-12 text-center space-y-10 sm:space-y-16 animate-fade-in" x-transition>
                        <div class="relative">
                            <!-- Burst background effect -->
                            <div class="absolute -inset-16 sm:-inset-24 bg-blue-600/10 comic-burst animate-pulse blur-xl"></div>
                            <div class="w-40 h-40 sm:w-60 sm:h-60 bg-white border-2 sm:border-4 border-slate-900 rounded-[2rem] sm:rounded-[3rem] flex items-center justify-center text-slate-900 relative z-10 shadow-[12px_12px_0px_#2563eb] sm:shadow-[24px_24px_0px_#2563eb] rotate-3">
                                <svg class="w-20 h-20 sm:w-32 sm:h-32" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <!-- Floating onomatopoeia -->
                                <div class="absolute -top-6 -right-8 sm:-top-8 sm:-right-12 animate-bounce">
                                    <span class="ONOMATOPOEIA text-2xl sm:text-4xl rotate-12">YES!</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4 sm:space-y-6 relative z-10">
                            <h2 class="text-4xl sm:text-6xl md:text-8xl font-black text-slate-900 tracking-tighter uppercase italic chromatic-offset">TASK_SUCCESS.</h2>
                            <p class="text-[10px] sm:text-xl font-black tracking-widest uppercase italic text-slate-400">The cryptographic transaction has been logged and finalized.</p>
                        </div>
                        <div class="flex flex-wrap justify-center gap-6 sm:gap-10 relative z-10">
                            <button @click="logout()" class="px-10 py-5 sm:px-20 sm:py-8 bg-slate-900 text-white rounded-2xl sm:rounded-3xl font-black text-lg sm:text-2xl hover:bg-slate-800 transition-all shadow-[6px_6px_0px_#2563eb] sm:shadow-[12px_12px_0px_#2563eb] active:translate-x-1 active:translate-y-1 active:shadow-none uppercase italic tracking-wider">CLOSE_SESSION</button>
                            <button @click="step = 'action_selection'" class="px-10 py-5 sm:px-20 sm:py-8 bg-white border-2 sm:border-4 border-slate-900 text-slate-900 rounded-2xl sm:rounded-3xl font-black text-lg sm:text-2xl hover:bg-slate-50 transition-all uppercase italic tracking-wider shadow-[6px_6px_0px_#1e293b] sm:shadow-[12px_12px_0px_#1e293b] active:translate-x-1 active:translate-y-1 active:shadow-none">NEW_BATCH</button>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </template>

    <!-- FIXED OVERLAYS -->
    <!-- Status Indicator -->
    <div x-show="isProcessing || statusMessage" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[100] animate-fade-in">
        <div class="bg-slate-900 border-2 border-white px-8 py-4 rounded-2xl flex items-center gap-4 shadow-[8px_8px_0px_rgba(0,0,0,0.2)]">
            <svg class="animate-spin h-6 w-6 text-blue-400" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-xs font-black text-white tracking-[0.3em] uppercase italic" x-text="statusMessage || 'Processing_Data...'"></span>
        </div>
    </div>

    <!-- Global Feedback Modal (Comic Style) -->
    <template x-teleport="body">
        <div x-show="modal.show" class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-sm" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white border-4 border-slate-900 w-full max-w-xl rounded-[3rem] p-12 text-center relative overflow-hidden shadow-[32px_32px_0px_rgba(30,41,59,0.3)]" @click.away="closeModal()">
                <div class="absolute inset-0 opacity-[0.05] benday-dots pointer-events-none"></div>
                
                <div :class="{
                    'bg-green-100 text-green-600 border-green-600': modal.type === 'success',
                    'bg-red-100 text-red-600 border-red-600': modal.type === 'error',
                    'bg-blue-100 text-blue-600 border-blue-600': modal.type === 'info'
                }" class="w-24 h-24 rounded-3xl border-4 flex items-center justify-center mx-auto mb-8 shadow-[8px_8px_0px_#1e293b] relative z-10 rotate-3">
                    <template x-if="modal.type === 'success'"><svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg></template>
                    <template x-if="modal.type === 'error'"><svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg></template>
                    <template x-if="modal.type === 'info'"><svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></template>
                </div>

                <h3 class="text-4xl font-black text-slate-900 mb-4 tracking-tighter uppercase italic chromatic-offset relative z-10" x-text="modal.title"></h3>
                <p class="text-slate-500 text-lg font-black leading-relaxed mb-10 italic uppercase tracking-wider relative z-10" x-text="modal.message"></p>
                
                <button @click="closeModal()" class="w-full py-6 bg-slate-900 text-white rounded-2xl font-black text-xl hover:bg-slate-800 shadow-[8px_8px_0px_#2563eb] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all uppercase italic tracking-widest relative z-10">CONTINUE_TASK</button>
            </div>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('kioskSystem', () => ({
        // Terminal State
        isKioskActive: false,
        activeAdmin: null,
        adminQrActive: false,
        isProcessing: false,
        statusMessage: '',
        
        // Data Models
        adminData: { id_pengenal_siswa: '', pin: '' },
        memberLoginData: { id_pengenal_siswa: '', pin: '' },
        bookInputData: { code: '' },
        
        showAdminPin: false,
        showUserPin: false,
        
        // Session State
        user: null,
        step: 'login', // login, action_selection, book_input, finished
        mode: 'borrow', // borrow, return
        loginMethod: 'qr', // qr, manual
        scannedBooks: [],
        
        // Infrastructure
        scanner: null,
        cameras: [],
        selectedCameraId: null,
        lastScan: { content: null, time: 0 },
        timer: 900,
        
        // Feedback
        modal: { show: false, type: 'info', title: '', message: '' },

        init() {
            this.loadCameras();
            setInterval(() => {
                if (this.user && this.timer > 0) {
                    this.timer--;
                    if (this.timer === 0) this.logout();
                }
            }, 100);

            // Life-cycle Watchers
            this.$watch('isKioskActive', (active) => {
                if (!active) this.stopScanner();
                else if (this.step === 'login' && this.loginMethod === 'qr') this.initScanner("reader");
            });

            this.$watch('step', (s) => {
                this.stopScanner().then(() => {
                    if (s === 'login' && this.loginMethod === 'qr') this.initScanner("reader");
                    if (s === 'book_input') this.initScanner("reader-book");
                });
            });

            this.$watch('loginMethod', (m) => {
                if (m === 'qr' && this.step === 'login') this.initScanner("reader");
                else this.stopScanner();
            });
        },

        // --- AUTH LOGIC ---

        async loginAdmin() {
            if (!this.adminData.id_pengenal_siswa || !this.adminData.pin) return this.showModal('error', 'Required', 'Admin credentials cannot be empty.');
            this.executeKioskApi('/api/kiosk/admin-login', this.adminData, (data) => {
                this.adminData = { id_pengenal_siswa: '', pin: '' };
                this.activeAdmin = data.admin;
                this.isKioskActive = true;
                this.showAdminPin = false;
                this.showModal('success', 'Terminal Activated', 'Welcome back, ' + data.admin.name);
                setTimeout(() => this.closeModal(), 2000);
            });
        },

        async loginAdminWithQr(content) {
            this.executeKioskApi('/api/kiosk/admin-login', { qr_code: content }, (data) => {
                this.activeAdmin = data.admin;
                this.isKioskActive = true;
                this.adminQrActive = false;
                this.showModal('success', 'Access Granted', 'Terminal activated by admin badge.');
                setTimeout(() => this.closeModal(), 2000);
            });
        },

        async loginManual() {
            if (!this.memberLoginData.id_pengenal_siswa || !this.memberLoginData.pin) return this.showModal('error', 'Missing Data', 'Please fill both ID Pengenal Siswa and PIN.');
            this.executeKioskApi('/api/kiosk/login', this.memberLoginData, (data) => {
                this.user = data.user;
                this.memberLoginData = { id_pengenal_siswa: '', pin: '' };
                this.showUserPin = false;
                this.step = 'action_selection';
                this.resetTimeout();
            });
        },

        async loginWithQR(content) {
            this.executeKioskApi('/api/kiosk/login', { qr_code: content }, (data) => {
                this.user = data.user;
                this.step = 'action_selection';
                this.resetTimeout();
            });
        },

        // --- TRANSACTION LOGIC ---

        startAction(type) {
            this.mode = type;
            this.step = 'book_input';
            this.resetTimeout();
        },

        async processBook(qrContent) {
            const endpoint = this.mode === 'borrow' ? '/api/kiosk/borrow' : '/api/kiosk/return';
            this.executeKioskApi(endpoint, { book_qr: qrContent }, (data) => {
                // For borrow, we might add to queue. For return, it's usually instant.
                this.scannedBooks.push({ title: 'Book Verified', code: qrContent });
                this.showModal('success', 'Verified', data.message || 'Book processed successfully.');
                this.step = 'finished';
            }, true);
        },

        async processBookManual() {
            if (!this.bookInputData.code || this.isProcessing) return;
            const code = this.bookInputData.code;
            this.bookInputData.code = '';
            await this.processBook(code);
        },

        // --- INFRASTRUCTURE ---

        async executeKioskApi(endpoint, body, onSuccess, useAuth = false) {
            if (this.isProcessing) return;
            this.isProcessing = true;
            this.statusMessage = 'Communicating with server...';
            
            try {
                const res = await this.fetchApi(endpoint, body, useAuth);
                if (res?.success) {
                    onSuccess(res);
                } else {
                    this.showModal('error', 'Process Failed', res?.message || 'Server returned an invalid response.');
                }
            } catch (e) {
                this.showModal('error', 'Network Error', 'Could not reach the library network. Please check connection.');
            } finally {
                this.isProcessing = false;
                this.statusMessage = '';
            }
        },

        async fetchApi(endpoint, body = {}, useAuth = false) {
            const url = `${window.location.origin}/${endpoint.replace(/^\//, '')}`;
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
            if (useAuth && this.user?.token) headers['Authorization'] = `Bearer ${this.user.token}`;

            const response = await fetch(url, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(body)
            });
            return response.json();
        },

        async initScanner(id, customHandler = null) {
            await this.stopScanner();
            if (!document.getElementById(id)) return;
            
            this.scanner = new Html5Qrcode(id);
            this.scanner.start(
                this.selectedCameraId || { facingMode: "environment" },
                { fps: 20, qrbox: { width: 280, height: 280 }, aspectRatio: 1.0 },
                (text) => {
                    const now = Date.now();
                    if (this.lastScan.content === text && (now - this.lastScan.time) < 3000) return;
                    this.lastScan = { content: text, time: now };
                    
                    if (customHandler) customHandler(text);
                    else this.handleScan(text);
                }
            ).catch(e => console.warn("Scanner failed to start", e));
        },

        async stopScanner() {
            if (this.scanner && this.scanner.isScanning) {
                try { await this.scanner.stop(); } catch(e) {}
            }
            this.scanner = null;
        },

        async loadCameras() {
            try {
                const devices = await Html5Qrcode.getCameras();
                if (devices?.length > 0) {
                    this.cameras = devices;
                    this.selectedCameraId = devices.find(d => d.label.toLowerCase().includes('back'))?.id || devices[0].id;
                }
            } catch(e) {}
        },

        async restartScanner() {
            if (!this.scanner && !this.adminQrActive && this.step !== 'book_input' && !(this.step === 'login' && this.loginMethod === 'qr')) return;
            
            this.stopScanner().then(() => {
                if (this.adminQrActive) this.initScanner("reader-admin", (c) => this.loginAdminWithQr(c));
                else if (this.step === 'login' && this.loginMethod === 'qr') this.initScanner("reader");
                else if (this.step === 'book_input') this.initScanner("reader-book");
            });
        },

        cycleCamera() {
            if (this.cameras.length <= 1) return;
            const currentIndex = this.cameras.findIndex(c => c.id === this.selectedCameraId);
            const nextIndex = (currentIndex + 1) % this.cameras.length;
            this.selectedCameraId = this.cameras[nextIndex].id;
            this.restartScanner();
        },

        handleScan(content) {
            if (this.isProcessing || this.modal.show) return;
            this.resetTimeout();
            if (this.step === 'login') this.loginWithQR(content);
            else if (this.step === 'book_input') this.processBook(content);
        },

        startAdminQr() {
            this.adminQrActive = true;
            this.$nextTick(() => this.initScanner("reader-admin", (c) => this.loginAdminWithQr(c)));
        },

        stopAdminQr() {
            this.adminQrActive = false;
            this.stopScanner();
        },

        // --- HELPERS ---

        resetTimeout() { this.timer = 900; },
        
        logout() {
            this.user = null;
            this.step = 'login';
            this.scannedBooks = [];
            this.resetTimeout();
        },

        closeKiosk() {
            this.isKioskActive = false;
            this.activeAdmin = null;
            this.logout();
        },

        showModal(type, title, message) { this.modal = { show: true, type, title, message }; },
        closeModal() { this.modal.show = false; }
    }));
});
</script>
@endpush
