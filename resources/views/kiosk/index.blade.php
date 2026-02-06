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
            
            <!-- Branding -->
            <div class="text-center mb-12 animate-float">
                <div class="inline-flex glass-card p-6 rounded-[2.5rem] mb-8 border-white/10 ring-8 ring-primary-500/5">
                    <svg class="w-16 h-16 sm:w-24 sm:h-24 text-primary-400 drop-shadow-[0_0_15px_rgba(59,130,246,0.5)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h1 class="text-5xl sm:text-7xl font-black text-white mb-4 tracking-tight">
                    PERPUS<span class="bg-gradient-to-br from-primary-400 via-indigo-400 to-accent-400 bg-clip-text text-transparent">IDHAM</span>
                </h1>
                <p class="text-gray-500 font-bold tracking-[0.2em] uppercase text-xs">Digital Self-Service Terminal</p>
            </div>

            <!-- Login Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-5xl">
                <!-- Manual Admin Login -->
                <div class="glass-card p-10 rounded-4xl relative overflow-hidden group">
                    <div class="relative z-10 space-y-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-3 bg-primary-500/20 rounded-2xl text-primary-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white uppercase italic tracking-tighter">Access_Security</h2>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2 block">Admin Email / NIS</label>
                                <input x-model="adminData.nis" type="text" placeholder="admin@perpus.id" class="w-full glass-input py-4 px-6 text-white font-medium">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2 block">Security PIN</label>
                                <input x-model="adminData.pin" type="password" maxlength="6" class="w-full glass-input py-4 px-6 text-white text-center tracking-[1em] font-mono text-xl">
                            </div>
                            <button @click="loginAdmin()" :disabled="isProcessing" class="w-full py-5 bg-primary-600 hover:bg-primary-500 text-white rounded-2xl font-black text-lg transition-all shadow-xl shadow-primary-500/20 disabled:opacity-50">
                                <span x-show="!isProcessing">ACTIVATE TERMINAL</span>
                                <span x-show="isProcessing">PROCESSING...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- QR Admin Login -->
                <div class="glass-card p-10 rounded-4xl flex flex-col items-center justify-center text-center relative overflow-hidden">
                    <div x-show="!adminQrActive" class="space-y-6" x-transition>
                        <div class="w-24 h-24 bg-white/5 rounded-3xl flex items-center justify-center mx-auto border border-white/10">
                            <svg class="w-12 h-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2 underline decoration-primary-500/50 underline-offset-4">Badge Authentication</h3>
                            <p class="text-gray-500 text-xs font-medium max-w-[200px] mx-auto">Use your administrator QR code for quick terminal access.</p>
                        </div>
                        <button @click="startAdminQr()" class="px-8 py-3 bg-white/5 hover:bg-white/10 text-white border border-white/10 rounded-xl font-bold text-sm transition-all">
                            Open Scanner
                        </button>
                    </div>

                    <div x-show="adminQrActive" class="w-full space-y-4" x-transition>
                        <div class="relative aspect-square w-full bg-black rounded-3xl overflow-hidden neon-border">
                            <div id="reader-admin" class="w-full h-full"></div>
                        </div>
                        
                        <!-- Camera selection -->
                        <div x-show="cameras.length > 1" class="w-full">
                            <select x-model="selectedCameraId" @change="restartScanner()" 
                                    class="w-full bg-white/5 border border-white/10 rounded-xl py-2 px-3 text-[10px] font-black uppercase tracking-widest text-gray-400 focus:text-white focus:border-primary-500/50 outline-none transition-all">
                                <template x-for="camera in cameras" :key="camera.id">
                                    <option :value="camera.id" x-text="camera.label" class="bg-slate-900"></option>
                                </template>
                            </select>
                        </div>

                        <button @click="stopAdminQr()" class="text-[10px] font-black text-red-500 uppercase tracking-widest">Cancel Scan</button>
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
            
            <!-- Terminal Header -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-6 glass-card p-6 rounded-4xl border-white/5">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-primary-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-white tracking-widest uppercase italic leading-none">TERMINAL_ACTIVE</h2>
                        <p class="text-[10px] font-bold text-gray-500 tracking-widest uppercase mt-1">Operator: <span class="text-primary-400" x-text="activeAdmin?.name"></span></p>
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
                    <div class="flex-grow flex flex-col lg:flex-row gap-8 items-stretch" x-transition>
                        <!-- Method Select -->
                        <div class="flex-grow flex flex-col justify-center space-y-8 lg:w-1/2">
                            <div class="space-y-2">
                                <h2 class="text-4xl sm:text-6xl font-black text-white tracking-tighter leading-tight uppercase italic">Member_Auth</h2>
                                <p class="text-gray-500 font-medium">Scan your member card or enter credentials to continue.</p>
                            </div>

                            <div class="space-y-4 max-w-md">
                                <button @click="loginMethod = 'qr'" :class="loginMethod === 'qr' ? 'neon-border bg-primary-500/10' : 'glass-card opacity-50'" class="w-full p-6 rounded-3xl flex items-center justify-between transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="p-4 bg-primary-500 text-white rounded-2xl shadow-lg">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                        </div>
                                        <div class="text-left"><p class="font-black text-white text-lg">QR Badge Scan</p><p class="text-[10px] text-gray-500 font-bold uppercase">Contactless Method</p></div>
                                    </div>
                                    <div x-show="loginMethod === 'qr'" class="w-2 h-2 rounded-full bg-primary-500 animate-ping"></div>
                                </button>

                                <button @click="loginMethod = 'manual'" :class="loginMethod === 'manual' ? 'neon-border bg-primary-500/10' : 'glass-card opacity-50'" class="w-full p-6 rounded-3xl flex items-center justify-between transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="p-4 bg-indigo-500 text-white rounded-2xl shadow-lg">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </div>
                                        <div class="text-left"><p class="font-black text-white text-lg">Manual Input</p><p class="text-[10px] text-gray-500 font-bold uppercase">NIS & Security PIN</p></div>
                                    </div>
                                    <div x-show="loginMethod === 'manual'" class="w-2 h-2 rounded-full bg-primary-500 animate-ping"></div>
                                </button>
                                
                                <div x-show="loginMethod === 'manual'" class="pt-4 space-y-4 animate-fade-in">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Member NIS</label>
                                        <input x-model="memberLoginData.nis" type="text" placeholder="Enter NIS code..." class="w-full glass-input py-4 px-6 text-white">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Security PIN</label>
                                        <input x-model="memberLoginData.pin" type="password" maxlength="6" class="w-full glass-input py-4 px-6 text-white text-center tracking-[0.5em] font-mono">
                                    </div>
                                    <button @click="loginManual()" class="w-full py-4 bg-primary-600 hover:bg-primary-500 text-white rounded-2xl font-black text-sm uppercase tracking-widest transition-all">Sign In</button>
                                </div>
                            </div>
                        </div>

                        <!-- Scanner View -->
                        <div class="flex-grow flex items-center justify-center lg:w-1/2">
                            <div class="glass-card p-6 rounded-[3rem] w-full max-w-md aspect-square relative overflow-hidden">
                                <template x-if="loginMethod === 'qr'">
                                    <div class="w-full h-full relative">
                                        <div class="w-full h-full bg-black rounded-3xl overflow-hidden neon-border relative">
                                            <div id="reader" class="w-full h-full"></div>
                                            <div class="absolute inset-0 pointer-events-none border-2 border-primary-500/20 rounded-3xl m-8"></div>
                                            <div class="animate-scan"></div>
                                        </div>

                                        <!-- Camera selection -->
                                        <div x-show="cameras.length > 1" class="absolute bottom-4 left-4 right-4 z-20">
                                            <select x-model="selectedCameraId" @change="restartScanner()" 
                                                    class="w-full bg-black/60 backdrop-blur-md border border-white/10 rounded-xl py-2 px-3 text-[9px] font-black uppercase tracking-widest text-gray-400 focus:text-white outline-none transition-all">
                                                <template x-for="camera in cameras" :key="camera.id">
                                                    <option :value="camera.id" x-text="camera.label"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="loginMethod === 'manual'">
                                    <div class="w-full h-full flex flex-col items-center justify-center text-center p-8">
                                        <div class="w-32 h-32 bg-indigo-500/10 rounded-4xl flex items-center justify-center text-indigo-400 mb-6">
                                            <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                        </div>
                                        <h3 class="text-xl font-black text-white uppercase italic">Manual_Mode</h3>
                                        <p class="text-gray-500 text-xs mt-2">Please use the login form on the left to verify your identity.</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- STEP: ACTION SELECTION -->
                <template x-if="step === 'action_selection'">
                    <div class="flex-grow flex flex-col items-center justify-center py-12 animate-fade-in" x-transition>
                        <div class="text-center mb-12">
                            <h2 class="text-4xl sm:text-6xl font-black text-white tracking-tighter uppercase italic mb-4">Select_Workflow</h2>
                            <p class="text-gray-500">Welcome back, <span class="text-white font-bold" x-text="user?.name"></span>. What would you like to do?</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-5xl">
                            <!-- Borrow -->
                            <button @click="startAction('borrow')" class="group glass-card p-10 rounded-5xl text-left hover:border-primary-500/50 transition-all duration-500">
                                <div class="w-20 h-20 bg-primary-600 text-white rounded-3xl flex items-center justify-center mb-8 shadow-2xl group-hover:scale-110 transition-transform">
                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </div>
                                <h3 class="text-3xl font-black text-white uppercase italic tracking-tighter mb-2">Borrow_Book</h3>
                                <p class="text-gray-500 text-sm font-medium pr-8">New library loan transaction. Scan any available book QR to start.</p>
                                <div class="mt-8 flex items-center gap-3 text-primary-400 text-[10px] font-black uppercase tracking-widest">
                                    <span>Execute</span><svg class="w-4 h-4 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </div>
                            </button>

                            <!-- Return -->
                            <button @click="startAction('return')" class="group glass-card p-10 rounded-5xl text-left hover:border-accent-500/50 transition-all duration-500">
                                <div class="w-20 h-20 bg-accent-600 text-white rounded-3xl flex items-center justify-center mb-8 shadow-2xl group-hover:scale-110 transition-transform">
                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" /></svg>
                                </div>
                                <h3 class="text-3xl font-black text-white uppercase italic tracking-tighter mb-2">Return_Book</h3>
                                <p class="text-gray-500 text-sm font-medium pr-8">Complete an existing loan. Scan your books to finalize return.</p>
                                <div class="mt-8 flex items-center gap-3 text-accent-400 text-[10px] font-black uppercase tracking-widest">
                                    <span>Verify</span><svg class="w-4 h-4 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </div>
                            </button>
                        </div>
                        
                        <button @click="logout()" class="mt-12 text-[10px] font-black text-gray-600 hover:text-white uppercase tracking-widest transition-colors">Different Identity?</button>
                    </div>
                </template>

                <!-- STEP: BOOK INPUT (THE FIX) -->
                <template x-if="step === 'book_input'">
                    <div class="flex-grow flex flex-col space-y-8 animate-fade-in" x-transition>
                        <div class="flex justify-between items-end">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-primary-400 uppercase tracking-widest">Workflow: <span x-text="mode"></span></p>
                                <h2 class="text-4xl sm:text-5xl font-black text-white tracking-tighter uppercase italic">Identify_Items</h2>
                            </div>
                            <button @click="step = 'action_selection'" class="text-[10px] font-black text-gray-500 hover:text-white uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                                Change Workflow
                            </button>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 flex-grow items-stretch">
                            <!-- Left: Details & Manual Input -->
                            <div class="flex flex-col space-y-6 lg:w-3/5">
                                <div class="glass-card p-10 rounded-4xl border-white/5 space-y-8 flex-grow">
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-2 text-primary-400">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            <h4 class="text-xs font-black uppercase tracking-widest">Manual_Entry_Terminal</h4>
                                        </div>
                                        <div class="space-y-3">
                                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Serial Number / Book Code</label>
                                            <input x-model="bookInputData.code" 
                                                   type="text" 
                                                   placeholder="Input code here..." 
                                                   @keyup.enter="processBookManual()"
                                                   class="w-full glass-input py-5 px-8 text-2xl font-black tracking-widest text-white uppercase">
                                        </div>
                                    </div>
                                    <button @click="processBookManual()" 
                                            :disabled="isProcessing || !bookInputData.code" 
                                            class="w-full py-6 bg-gradient-to-r from-primary-600 to-indigo-600 hover:from-primary-500 hover:to-indigo-500 text-white rounded-2xl font-black text-xl shadow-2xl transition-all active:scale-95 disabled:opacity-50">
                                        VERIFY_ITEM
                                    </button>

                                    <!-- Recent Queue (Simulated) -->
                                    <div class="pt-6 border-t border-white/5 space-y-4">
                                        <div class="flex justify-between items-center">
                                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Transaction_Batch</p>
                                            <span class="text-xs text-white font-mono" x-text="scannedBooks.length + ' Items'"></span>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="book in scannedBooks" :key="book.code">
                                                <div class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-[10px] font-bold text-primary-400" x-text="book.code"></div>
                                            </template>
                                            <template x-if="scannedBooks.length === 0">
                                                <p class="text-[10px] text-gray-600 font-bold italic uppercase tracking-widest p-4 bg-white/5 rounded-2xl w-full text-center">Awaiting scanned content...</p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Scanner Area -->
                            <div class="flex items-center justify-center lg:w-2/5">
                                <div class="glass-card p-6 rounded-[3.5rem] w-full max-w-sm aspect-square relative overflow-hidden group">
                                    <div class="w-full h-full bg-black rounded-4xl overflow-hidden neon-border relative">
                                        <div id="reader-book" class="w-full h-full"></div>
                                        <div class="animate-scan"></div>
                                        
                                        <!-- Camera selection -->
                                        <div x-show="cameras.length > 1" class="absolute top-4 left-4 right-4 z-20">
                                            <select x-model="selectedCameraId" @change="restartScanner()" 
                                                    class="w-full bg-black/60 backdrop-blur-md border border-white/10 rounded-xl py-2 px-3 text-[9px] font-black uppercase tracking-widest text-gray-400 focus:text-white outline-none transition-all">
                                                <template x-for="camera in cameras" :key="camera.id">
                                                    <option :value="camera.id" x-text="camera.label"></option>
                                                </template>
                                            </select>
                                        </div>

                                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 bg-black/60 backdrop-blur-md px-4 py-2 rounded-full border border-white/10">
                                            <div class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></div>
                                            <span class="text-[9px] font-black text-white tracking-widest uppercase italic">Optic_Ready</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- STEP: FINISHED / SUCCESS -->
                <template x-if="step === 'finished'">
                    <div class="flex-grow flex flex-col items-center justify-center p-12 text-center space-y-12 animate-fade-in" x-transition>
                        <div class="relative">
                            <div class="absolute inset-0 bg-primary-500/20 rounded-full blur-3xl animate-pulse"></div>
                            <div class="w-48 h-48 bg-gradient-to-br from-primary-500 to-indigo-600 rounded-[3rem] flex items-center justify-center text-white relative z-10 shadow-2xl">
                                <svg class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <h2 class="text-6xl font-black text-white tracking-tighter uppercase italic">SUCCESS!</h2>
                            <p class="text-gray-400 text-xl font-medium tracking-wide">Your items have been securely updated in the library vault.</p>
                        </div>

                        <div class="flex flex-wrap justify-center gap-6">
                            <button @click="logout()" class="px-16 py-6 bg-white text-black rounded-3xl font-black text-xl hover:scale-105 transition-all shadow-xl uppercase italic">Close_Terminal</button>
                            <button @click="step = 'action_selection'" class="px-16 py-6 glass-card rounded-3xl font-black text-xl hover:bg-white/10 transition-all uppercase italic">New_Batch</button>
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
