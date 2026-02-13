@extends('layouts.print')

@section('title', 'Cetak Massal Kartu Anggota')

@section('content')
<div class="p-8 flex flex-wrap justify-center gap-10 print:p-0 print:gap-0 print:block">
    @foreach($users as $user)
        <div class="print:inline-block print:m-2 print:break-inside-avoid shadow-lg rounded-[4mm]">
            <div class="relative overflow-hidden bg-slate-50 border border-slate-300 rounded-[4mm] flex flex-col print:shadow-none print:border" 
                 style="width: 85.6mm; height: 53.98mm; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                
                <!-- Background Shapes -->
                <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden text-slate-900">
                    <!-- Subtle Global Texture -->
                    <div class="absolute inset-0 opacity-[0.08]" 
                         style="background-image: repeating-linear-gradient(135deg, #475569 0, #475569 0.5px, transparent 0.5px, transparent 8px);"></div>

                    <!-- Accent Color Block -->
                    <div class="absolute -right-5 -top-10 w-[55mm] h-[85mm] bg-blue-100/30 rotate-[15deg] border-l border-blue-200/50"></div>
                    
                    <!-- Book Icon Watermark -->
                    <svg class="absolute left-[35mm] top-[35mm] opacity-[0.15] text-blue-600" 
                         style="width: 13mm; height: 13mm;"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>

                    <!-- Geometric Pattern (Tech Grid) -->
                    <svg class="absolute right-[-2mm] bottom-[0mm] opacity-[0.2] text-blue-900" 
                         style="width: 38mm; height: 38mm;"
                         fill="none" stroke="currentColor" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid-{{ $user->id }}" width="8" height="8" patternUnits="userSpaceOnUse">
                                <path d="M 8 0 L 0 0 0 8" fill="none" stroke="currentColor" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#grid-{{ $user->id }})" />
                    </svg>

                    <!-- Floating Plus Signs -->
                    <div class="absolute top-[8mm] right-[40mm] opacity-30 text-blue-500 font-bold text-[3mm]">+</div>
                    <div class="absolute bottom-[20mm] left-[35mm] opacity-30 text-blue-500 font-bold text-[3mm]">+</div>

                    <!-- Circuit/Accent Line -->
                    <svg class="absolute top-0 right-0 w-[40mm] h-[15mm] opacity-[0.25] text-blue-600" viewBox="0 0 100 40">
                        <path d="M100 5 H70 L60 15 H20" fill="none" stroke="currentColor" stroke-width="0.8" />
                        <circle cx="20" cy="15" r="1.5" fill="currentColor" />
                    </svg>

                    <!-- Bottom Left Accents -->
                    <div class="absolute -bottom-10 -left-10 w-36 h-36 bg-blue-100/20 rounded-full border border-blue-200/50"></div>
                </div>
                
                <!-- Top Accent Bar -->
                <div class="relative z-10 w-full h-[3mm] bg-black"></div>
                
                <!-- Header -->
                <div class="relative z-10 px-5 pt-3 flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center p-1.5 shadow-sm">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="max-w-full max-h-full object-contain">
                        </div>
                        <div>
                            <h1 class="text-[3.5mm] font-black tracking-[0.8mm] text-slate-900 leading-none uppercase">PERPUSTAKAAN</h1>
                            <p class="text-[2mm] font-bold text-blue-600 mt-1 uppercase">SMK YAJ Depok</p>
                        </div>
                    </div>
                    <div class="bg-slate-900 text-white text-[1.6mm] font-black px-2.5 py-1 rounded-md tracking-wider">
                        MEMBER ID
                    </div>
                </div>

                <!-- Body -->
                <div class="relative z-10 px-5 pt-4 flex flex-1 gap-4">
                    <!-- Left Info Panel -->
                    <div class="flex-1">
                        <div class="mb-4">
                            <h2 class="text-[4.5mm] font-extrabold uppercase text-slate-900 leading-tight" 
                                style="text-shadow: 1px 1px 0px white;">{{ $user->name }}</h2>
                            <div class="inline-flex mt-2 bg-white border-2 border-slate-900 rounded-lg overflow-hidden shadow-[3px_3px_0px_#2563eb]">
                                <span class="bg-slate-900 text-white px-2 py-0.5 text-[1.8mm] font-black">ID</span>
                                <span class="px-3 py-0.5 text-[2.2mm] font-bold text-slate-900 uppercase tracking-wider">{{ $user->id_pengenal_siswa ?? $user->id }}</span>
                            </div>
                        </div>

                        <div class="space-y-0">
                            <div class="flex flex-col gap-0.5">
                                <label class="text-[1.5mm] font-extrabold text-slate-500 tracking-wide uppercase">{{ $user->isAdmin() ? 'Jabatan' : 'Kelas & Jurusan' }}</label>
                                <p class="text-[3mm] font-bold text-slate-900">{{ $user->isAdmin() ? 'ADMIN' : ($user->kelas ?? '-') . ' ' . ($user->jurusan ?? '') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right QR Panel -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="p-2 bg-white border-2 border-slate-900 rounded-xl shadow-[4px_4px_0px_#2563eb]">
                            <div class="flex items-center justify-center">
                                {!! QrCode::size(80)->margin(1)->color(15, 23, 42)->generate($user->qr_payload) !!}
                            </div>
                        </div>
                        <p class="text-[1.5mm] font-black text-slate-900 tracking-[0.8mm] uppercase opacity-70">MEMBER QR</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="relative z-10 px-5 pb-1 mt-auto">
                    <div class="pt-1 border-t border-slate-200">
                        <p class="text-[1.5mm] font-bold text-slate-400 text-center uppercase tracking-widest">
                            RESMI • PERPUSTAKAAN SMK YAJ DEPOK
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<style>
    @media print {
        @page {
            margin: 5mm;
        }
        body { margin: 0; padding: 0; background: white; }
        .print\:block { display: block !important; }
        .print\:inline-block { display: inline-block !important; }
        .print\:p-0 { padding: 0 !important; }
        .id-card { 
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact; 
        }
    }
</style>
@endsection
