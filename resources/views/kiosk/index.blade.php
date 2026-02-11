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
            
            <!-- Branding (User Card Style) -->
            <div class="text-center mb-16 animate-float relative">
                <div class="absolute -inset-10 bg-blue-600/10 blur-[50px] rounded-full"></div>
                <div class="relative inline-flex flex-col items-center gap-4">
                    <div class="w-20 h-20 bg-white shadow-2xl rounded-full flex items-center justify-center p-4 ring-8 ring-slate-100/50">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="max-w-full max-h-full object-contain">
                    </div>
                    <div class="space-y-1">
                        <h1 class="text-5xl sm:text-7xl font-black tracking-[0.2em] text-slate-900 leading-none uppercase">PERPUSTAKAAN</h1>
                        <p class="text-lg font-bold text-blue-600 tracking-[0.5em] uppercase italic">SMK YAJ DEPOK</p>
                    </div>
                </div>
            </div>

            <!-- Login Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 w-full max-w-6xl">
                <!-- Manual Admin Login -->
                <div class="bg-white/80 backdrop-blur-xl border border-slate-200 p-12 rounded-[3.5rem] relative overflow-hidden group shadow-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100/40 -mr-10 -mt-10 rounded-full blur-3xl group-hover:bg-blue-200/40 transition-all"></div>
                    
                    <div class="relative z-10 space-y-8">
                        <div class="flex items-center gap-5">
                            <div class="bg-blue-100 p-4 rounded-2xl text-blue-600 border border-blue-200">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 uppercase tracking-wider italic">ADMIN_LOGIN</h2>
                                <div class="h-1 w-12 bg-blue-600 mt-1"></div>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Authentication_ID (NIS/Email)</label>
                                <input x-model="adminData.nis" type="text" placeholder="ID Required..." class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl py-5 px-8 text-slate-900 font-mono focus:border-blue-600 outline-none transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Security_PIN</label>
                                <div class="flex gap-3 bg-white border-2 border-slate-900 rounded-2xl overflow-hidden shadow-[6px_6px_0px_#2563eb]">
                                    <div class="bg-slate-100 text-slate-400 px-4 py-4 flex items-center border-r border-slate-200">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                    </div>
                                    <input x-model="adminData.pin" type="password" maxlength="6" class="flex-1 bg-transparent py-4 px-6 text-slate-900 text-center tracking-[1em] font-mono text-2xl outline-none">
                                </div>
                            </div>
                            <button @click="loginAdmin()" :disabled="isProcessing" class="w-full py-6 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-black text-lg transition-all shadow-xl active:scale-[0.98] disabled:opacity-50">
                                <span x-show="!isProcessing">ACTIVATE_TERMINAL</span>
                                <span x-show="isProcessing">VERIFYING...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- QR Admin Login -->
                <div class="bg-slate-50 border border-slate-300 p-12 rounded-[3.5rem] flex flex-col items-center justify-center text-center relative overflow-hidden shadow-2xl">
                    <!-- Tech grid background for light card -->
                    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" 
                         style="background-image: linear-gradient(to right, #000 1px, transparent 1px), linear-gradient(to bottom, #000 1px, transparent 1px); background-size: 20px 20px;"></div>
                    
                    <div x-show="!adminQrActive" class="space-y-8 relative z-10" x-transition>
                        <div class="w-28 h-28 bg-white border-2 border-slate-900 rounded-[2rem] flex items-center justify-center mx-auto shadow-[8px_8px_0px_#2563eb]">
                            <svg class="w-14 h-14 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-3xl font-black text-slate-900 mb-3 uppercase italic tracking-tighter">BADGE_SCAN</h3>
                            <p class="text-slate-500 text-xs font-bold max-w-[240px] mx-auto uppercase tracking-widest leading-relaxed">Use your official administrator security badge for immediate authentication.</p>
                        </div>
                        <button @click="startAdminQr()" class="px-10 py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-xl">
                            INITIALIZE_OPTIC
                        </button>
                    </div>

                    <div x-show="adminQrActive" class="w-full space-y-6 relative z-10" x-transition>
                        <div class="relative aspect-square w-full bg-slate-900 border-4 border-slate-900 rounded-[2.5rem] overflow-hidden shadow-[12px_12px_0px_#2563eb]">
                            <div id="reader-admin" class="w-full h-full"></div>
                        </div>
                        
                        <!-- Camera selection -->
                        <div x-show="cameras.length > 1" class="w-full">
                            <select x-model="selectedCameraId" @change="restartScanner()" 
                                    class="w-full bg-slate-200 border-2 border-slate-900 rounded-2xl py-3 px-4 text-[10px] font-black uppercase tracking-widest text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                <template x-for="camera in cameras" :key="camera.id">
                                    <option :value="camera.id" x-text="camera.label"></option>
                                </template>
                            </select>
                        </div>

                        <button @click="stopAdminQr()" class="text-xs font-black text-red-600 hover:underline uppercase tracking-[0.2em]">ABORT_SCAN</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- ==========================================
         SCREEN 2: TERMINAL ACTIVE (SHELL)
         ========================================== -->
    <template x-if="isKioskActive">
        <div class="flex-grow flex flex-col space-y-8 animate-fade-in">
            
            <!-- Terminal Header (Member NIS style) -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-6 bg-white border-2 border-slate-200 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                <div class="absolute right-0 top-0 h-full w-[30%] bg-blue-100/50 rotate-12 -mr-10 -mt-10 blur-3xl"></div>
                
                <div class="relative z-10 flex items-center gap-6">
                    <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center p-3 shadow-xl ring-4 ring-slate-50">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="max-w-full max-h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-2xl font-black text-slate-900 tracking-widest uppercase italic leading-none">OPERATOR_TERMINAL</h2>
                            <span class="bg-blue-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-[0.2em] shadow-lg shadow-blue-500/20">ACTIVE</span>
                        </div>
                        <p class="text-[10px] font-black text-slate-400 tracking-[0.3em] uppercase mt-2">Authenticated_As: <span class="text-blue-600" x-text="activeAdmin?.name"></span></p>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div x-show="user" class="hidden sm:flex items-center gap-3 px-4 py-2 bg-white/5 rounded-xl border border-white/10 font-mono text-sm">
                        <span class="text-gray-500 uppercase text-[9px] font-black">Time_Remaining:</span>
                        <span class="text-primary-400 font-black" x-text="Math.ceil(timer / 10) + 's'"></span>
                    </div>
                    <button @click="closeKiosk()" class="px-6 py-2 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/20 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">TERMINATE</button>
                </div>
            </div>

            <!-- TERMINAL CONTENT ROUTER -->
            <div class="flex-grow flex flex-col">
                
                <!-- STEP: MEMBER LOGIN -->
                <template x-if="step === 'login'">
                    <div class="flex-grow flex flex-col lg:flex-row gap-10 items-stretch" x-transition>
                        <!-- Method Select -->
                        <div class="flex-grow flex flex-col justify-center space-y-10 lg:w-1/2">
                            <div class="space-y-4">
                                <h2 class="text-4xl sm:text-7xl font-black text-slate-900 tracking-tighter leading-tight uppercase italic">USER_AUTH_GATEWAY</h2>
                                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] italic">Access secure library services using your digital credentials.</p>
                            </div>

                            <div class="space-y-6 max-w-lg">
                                <button @click="loginMethod = 'qr'" 
                                        :class="loginMethod === 'qr' ? 'bg-white border-blue-600 shadow-[8px_8px_0px_#2563eb]' : 'bg-white/40 border-slate-200 opacity-60'" 
                                        class="w-full p-8 rounded-[2.5rem] flex items-center justify-between border-2 transition-all duration-500 group">
                                    <div class="flex items-center gap-6">
                                        <div :class="loginMethod === 'qr' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400'" class="p-5 rounded-2xl shadow-xl transition-colors">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-black text-slate-900 text-2xl uppercase italic tracking-wider">Badge_Optic_Scan</p>
                                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.3em] mt-1">Instant Verification</p>
                                        </div>
                                    </div>
                                    <div x-show="loginMethod === 'qr'" class="w-3 h-3 rounded-full bg-blue-500 animate-ping"></div>
                                </button>

                                <button @click="loginMethod = 'manual'" 
                                        :class="loginMethod === 'manual' ? 'bg-white border-blue-600 shadow-[8px_8px_0px_#2563eb]' : 'bg-white/40 border-slate-200 opacity-60'" 
                                        class="w-full p-8 rounded-[2.5rem] flex items-center justify-between border-2 transition-all duration-500 group">
                                    <div class="flex items-center gap-6">
                                        <div :class="loginMethod === 'manual' ? 'bg-indigo-500 text-white' : 'bg-slate-100 text-slate-400'" class="p-5 rounded-2xl shadow-xl transition-colors">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-black text-slate-900 text-2xl uppercase italic tracking-wider">Manual_Secure_Code</p>
                                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.3em] mt-1">Keypad Entry</p>
                                        </div>
                                    </div>
                                    <div x-show="loginMethod === 'manual'" class="w-3 h-3 rounded-full bg-indigo-500 animate-ping"></div>
                                </button>
                                
                                <div x-show="loginMethod === 'manual'" class="pt-6 space-y-6 animate-fade-in">
                                    <div class="space-y-3">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Member_Identification_Code</label>
                                        <input x-model="memberLoginData.nis" type="text" placeholder="Enter NIS..." class="w-full bg-white border-2 border-slate-200 rounded-2xl py-5 px-8 text-slate-900 focus:border-blue-600 outline-none font-mono">
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Personal_Security_PIN</label>
                                        <div class="flex gap-3 bg-white border-2 border-slate-900 rounded-2xl overflow-hidden shadow-[6px_6px_0px_#2563eb]">
                                            <div class="bg-slate-100 text-slate-400 px-4 py-4 flex items-center border-r border-slate-200">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                            </div>
                                            <input x-model="memberLoginData.pin" type="password" maxlength="6" class="flex-1 bg-transparent py-4 px-6 text-slate-900 text-center tracking-[0.8em] font-mono text-2xl outline-none">
                                        </div>
                                    </div>
                                    <button @click="loginManual()" class="w-full py-5 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-black text-sm uppercase tracking-[0.3em] transition-all shadow-xl shadow-blue-500/10">EXECUTE_LOGIN</button>
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

                                        <!-- Camera selection -->
                                        <div x-show="cameras.length > 1" class="absolute bottom-6 left-6 right-6 z-20">
                                            <select x-model="selectedCameraId" @change="restartScanner()" 
                                                    class="w-full bg-slate-900/80 backdrop-blur-xl border border-slate-700 rounded-2xl py-3 px-6 text-[10px] font-black uppercase tracking-widest text-slate-400 focus:text-white outline-none transition-all">
                                                <template x-for="camera in cameras" :key="camera.id">
                                                    <option :value="camera.id" x-text="camera.label"></option>
                                                </template>
                                            </select>
                                        </div>
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
                    <div class="flex-grow flex flex-col items-center justify-center py-12 animate-fade-in" x-transition>
                        <div class="text-center mb-16 relative">
                            <div class="absolute -inset-10 bg-blue-100/50 blur-[40px] rounded-full"></div>
                            <h2 class="text-4xl sm:text-7xl font-black text-slate-900 tracking-tighter uppercase italic mb-6 relative z-10">CORE_MODULE_SELECT</h2>
                            <p class="text-slate-400 font-bold uppercase tracking-[0.3em] italic relative z-10">Validated_User: <span class="text-slate-900 border-b-2 border-blue-600 pb-1" x-text="user?.name"></span></p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 w-full max-w-6xl">
                            <!-- Borrow -->
                            <button @click="startAction('borrow')" class="group bg-white/80 backdrop-blur-xl border-2 border-slate-200 p-12 rounded-[4rem] text-left hover:border-blue-600 hover:shadow-[16px_16px_0px_#2563eb] transition-all duration-500 relative overflow-hidden shadow-2xl">
                                <div class="absolute top-0 right-0 w-48 h-48 bg-blue-100/50 -mr-16 -mt-16 rounded-full blur-3xl group-hover:bg-blue-200/50 transition-all"></div>
                                
                                <div class="w-24 h-24 bg-blue-600 text-white rounded-[2rem] flex items-center justify-center mb-10 shadow-2xl group-hover:scale-110 transition-transform relative z-10">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </div>
                                <h3 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter mb-4 relative z-10">BORROW_ITEM</h3>
                                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest leading-relaxed pr-8 relative z-10">Initiate new cryptographic loan transaction. Scans required for validation.</p>
                                <div class="mt-10 flex items-center gap-4 text-blue-600 text-[10px] font-black uppercase tracking-[0.4em] relative z-10">
                                    <span>RUN_WORKFLOW</span><svg class="w-5 h-5 group-hover:translate-x-3 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </div>
                            </button>

                            <!-- Return -->
                            <button @click="startAction('return')" class="group bg-white/80 backdrop-blur-xl border-2 border-slate-200 p-12 rounded-[4rem] text-left hover:border-indigo-600 hover:shadow-[16px_16px_0px_#4f46e5] transition-all duration-500 relative overflow-hidden shadow-2xl">
                                <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-100/50 -mr-16 -mt-16 rounded-full blur-3xl group-hover:bg-indigo-200/50 transition-all"></div>

                                <div class="w-24 h-24 bg-indigo-600 text-white rounded-[2rem] flex items-center justify-center mb-10 shadow-2xl group-hover:scale-110 transition-transform relative z-10">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" /></svg>
                                </div>
                                <h3 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter mb-4 relative z-10">RETURN_ITEM</h3>
                                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest leading-relaxed pr-8 relative z-10">Deactivate active loan session. Items must be optically verified.</p>
                                <div class="mt-10 flex items-center gap-4 text-indigo-600 text-[10px] font-black uppercase tracking-[0.4em] relative z-10">
                                    <span>VERIFY_STATE</span><svg class="w-5 h-5 group-hover:translate-x-3 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </div>
                            </button>
                        </div>
                        
                        <button @click="logout()" class="mt-16 text-[10px] font-black text-slate-400 hover:text-slate-900 uppercase tracking-[0.4em] transition-colors border-b border-transparent hover:border-slate-900 pb-1">TERMINATE_CURRENT_IDENTITY</button>
                    </div>
                </template>

                <!-- STEP: BOOK INPUT (THE FIX) -->
                <template x-if="step === 'book_input'">
                    <div class="flex-grow flex flex-col space-y-10 animate-fade-in" x-transition>
                        <div class="flex justify-between items-end">
                            <div class="space-y-2">
                                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.4em]">Current_Task: <span x-text="mode"></span></p>
                                <h2 class="text-4xl sm:text-6xl font-black text-slate-900 tracking-tighter uppercase italic">ITEM_IDENTIFICATION</h2>
                            </div>
                            <button @click="step = 'action_selection'" class="text-[10px] font-black text-slate-400 hover:text-slate-900 uppercase tracking-[0.2em] flex items-center gap-3 group transition-all">
                                <svg class="w-5 h-5 group-hover:-translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                                ABORT_WORKFLOW
                            </button>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 flex-grow items-stretch">
                            <!-- Left: Details & Manual Input -->
                            <div class="flex flex-col space-y-8 lg:w-3/5">
                                <div class="bg-white/80 backdrop-blur-xl border-2 border-slate-200 p-12 rounded-[4rem] space-y-10 flex-grow relative overflow-hidden shadow-2xl">
                                    <div class="absolute top-0 right-0 w-48 h-48 bg-blue-100/40 -mr-16 -mt-16 rounded-full blur-3xl"></div>
                                    
                                    <div class="relative z-10 space-y-6">
                                        <div class="flex items-center gap-3 text-blue-600">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            <h4 class="text-xs font-black uppercase tracking-[0.3em]">MANUAL_ENTRY_UNIT</h4>
                                        </div>
                                        <div class="space-y-4">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Serial_Scan_String / Book_Code</label>
                                            <div class="flex bg-white border-2 border-slate-900 rounded-2xl overflow-hidden shadow-[8px_8px_0px_#2563eb]">
                                                <div class="bg-slate-100 text-slate-900 px-5 py-5 flex items-center border-r border-slate-200">
                                                    <span class="text-[10px] font-black uppercase tracking-tighter">CODE</span>
                                                </div>
                                                <input x-model="bookInputData.code" 
                                                       type="text" 
                                                       placeholder="Format: B-XXXXX..." 
                                                       @keyup.enter="processBookManual()"
                                                       class="flex-1 bg-transparent py-5 px-8 text-2xl font-black tracking-widest text-slate-900 uppercase italic outline-none">
                                            </div>
                                        </div>
                                    </div>
                                    <button @click="processBookManual()" 
                                            :disabled="isProcessing || !bookInputData.code" 
                                            class="w-full py-6 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-black text-xl shadow-2xl transition-all active:scale-[0.98] disabled:opacity-50 relative z-10 uppercase italic tracking-widest">
                                        VERIFY_ITEM_DATA
                                    </button>

                                    <!-- Recent Queue -->
                                    <div class="pt-10 border-t border-slate-200 space-y-6 relative z-10">
                                        <div class="flex justify-between items-center">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">TRANSACTION_BATCH_QUEUE</p>
                                            <span class="bg-blue-100 text-blue-600 px-4 py-1 rounded-full text-[10px] font-black" x-text="scannedBooks.length + ' IDENTIFIED'"></span>
                                        </div>
                                        <div class="flex flex-wrap gap-4">
                                            <template x-for="book in scannedBooks" :key="book.code">
                                                <div class="px-6 py-3 bg-white border-2 border-slate-200 rounded-2xl text-[10px] font-black text-blue-600 italic shadow-lg" x-text="book.code"></div>
                                            </template>
                                            <template x-if="scannedBooks.length === 0">
                                                <div class="p-8 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] w-full text-center">
                                                    <p class="text-[10px] text-slate-300 font-black italic uppercase tracking-[0.4em]">Optic_Sensor_Standby...</p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Scanner Area -->
                            <div class="flex items-center justify-center lg:w-2/5">
                                <div class="bg-slate-50 border border-slate-300 p-8 rounded-[4.5rem] w-full max-w-md aspect-square relative overflow-hidden shadow-2xl group">
                                    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" 
                                         style="background-image: linear-gradient(to right, #000 1px, transparent 1px), linear-gradient(to bottom, #000 1px, transparent 1px); background-size: 20px 20px;"></div>
                                    
                                    <div class="w-full h-full bg-slate-900 rounded-[3.5rem] overflow-hidden border-4 border-slate-900 shadow-[16px_16px_0px_#2563eb] relative">
                                        <div id="reader-book" class="w-full h-full"></div>
                                        <div class="animate-scan"></div>
                                        
                                        <!-- Camera selection -->
                                        <div x-show="cameras.length > 1" class="absolute top-6 left-6 right-6 z-20">
                                            <select x-model="selectedCameraId" @change="restartScanner()" 
                                                    class="w-full bg-slate-900/80 backdrop-blur-xl border border-slate-700 rounded-2xl py-3 px-6 text-[10px] font-black uppercase tracking-widest text-slate-400 focus:text-white outline-none transition-all">
                                                <template x-for="camera in cameras" :key="camera.id">
                                                    <option :value="camera.id" x-text="camera.label"></option>
                                                </template>
                                            </select>
                                        </div>

                                        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-3 bg-slate-900/80 backdrop-blur-xl px-6 py-3 rounded-full border border-blue-500/20 shadow-2xl">
                                            <div class="h-2 w-2 rounded-full bg-blue-500 animate-pulse ring-4 ring-blue-500/20"></div>
                                            <span class="text-[10px] font-black text-white tracking-[0.3em] uppercase italic">OPTIC_READY</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- STEP: FINISHED / SUCCESS -->
                <template x-if="step === 'finished'">
                    <div class="flex-grow flex flex-col items-center justify-center p-12 text-center space-y-16 animate-fade-in" x-transition>
                        <div class="relative">
                            <div class="absolute -inset-20 bg-blue-100/50 rounded-full blur-[80px] animate-pulse"></div>
                            <div class="w-56 h-56 bg-white border-2 border-slate-900 rounded-[4rem] flex items-center justify-center text-slate-900 relative z-10 shadow-[20px_20px_0px_#2563eb]">
                                <svg class="w-28 h-28" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <h2 class="text-6xl sm:text-8xl font-black text-slate-900 tracking-tighter uppercase italic">TASK_SUCCESS.</h2>
                            <p class="text-slate-400 text-xl font-bold tracking-widest uppercase italic">The cryptographic transaction has been logged and finalized.</p>
                        </div>

                        <div class="flex flex-wrap justify-center gap-10">
                            <button @click="logout()" class="px-20 py-8 bg-blue-600 text-white rounded-[2.5rem] font-black text-2xl hover:scale-105 active:scale-95 transition-all shadow-2xl shadow-blue-500/30 uppercase italic tracking-wider">CLOSE_SESSION</button>
                            <button @click="step = 'action_selection'" class="px-20 py-8 bg-white border-2 border-slate-200 text-slate-900 rounded-[2.5rem] font-black text-2xl hover:bg-slate-50 transition-all uppercase italic tracking-wider shadow-xl">NEW_BATCH</button>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </template>

    <!-- FIXED OVERLAYS -->
    <!-- Status Indicator -->
    <div x-show="isProcessing || statusMessage" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[100] animate-fade-in">
        <div class="glass-card px-8 py-4 rounded-2xl flex items-center gap-4 border-primary-500/30">
            <svg class="animate-spin h-5 w-5 text-primary-400" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="text-xs font-black text-white tracking-widest uppercase italic" x-text="statusMessage || 'Processing_Data...'"></span>
        </div>
    </div>

    <!-- Global Feedback Modal -->
    <template x-teleport="body">
        <div x-show="modal.show" class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-black/80 backdrop-blur-md" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="glass-card w-full max-w-xl rounded-[3rem] p-12 text-center animate-float" @click.away="closeModal()">
                <div :class="{
                    'bg-green-500/10 text-green-500 border-green-500/20': modal.type === 'success',
                    'bg-red-500/10 text-red-500 border-red-500/20': modal.type === 'error',
                    'bg-primary-500/10 text-primary-400 border-primary-500/20': modal.type === 'info'
                }" class="w-24 h-24 rounded-[2rem] border flex items-center justify-center mx-auto mb-8 shadow-2xl">
                    <template x-if="modal.type === 'success'"><svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg></template>
                    <template x-if="modal.type === 'error'"><svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></template>
                    <template x-if="modal.type === 'info'"><svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></template>
                </div>
                <h3 class="text-3xl font-black text-white mb-4 tracking-tighter uppercase italic" x-text="modal.title"></h3>
                <p class="text-gray-400 text-lg font-medium leading-relaxed mb-10" x-text="modal.message"></p>
                <button @click="closeModal()" class="w-full py-5 bg-white text-black rounded-2xl font-black text-lg hover:scale-[1.02] active:scale-[0.98] transition-all">CONTINUE</button>
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
        adminData: { nis: '', pin: '' },
        memberLoginData: { nis: '', pin: '' },
        bookInputData: { code: '' },
        
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
            if (!this.adminData.nis || !this.adminData.pin) return this.showModal('error', 'Required', 'Admin credentials cannot be empty.');
            this.executeKioskApi('/api/kiosk/admin-login', this.adminData, (data) => {
                this.activeAdmin = data.admin;
                this.isKioskActive = true;
                this.adminData = { nis: '', pin: '' };
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
            if (!this.memberLoginData.nis || !this.memberLoginData.pin) return this.showModal('error', 'Missing Data', 'Please fill both NIS and PIN.');
            this.executeKioskApi('/api/kiosk/login', this.memberLoginData, (data) => {
                this.user = data.user;
                this.memberLoginData = { nis: '', pin: '' };
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
            this.stopScanner().then(() => {
                if (this.adminQrActive) this.initScanner("reader-admin", (c) => this.loginAdminWithQr(c));
                else if (this.step === 'login' && this.loginMethod === 'qr') this.initScanner("reader");
                else if (this.step === 'book_input') this.initScanner("reader-book");
            });
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
