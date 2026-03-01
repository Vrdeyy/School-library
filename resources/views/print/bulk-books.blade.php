@extends('layouts.print')

@section('title', 'Cetak Massal Label Buku')

@section('content')
<!-- Fonts -->

<style>
    .ONOMATOPOEIA {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 900;
        font-style: italic;
        -webkit-text-stroke: 1px #1e293b;
        color: white;
        filter: drop-shadow(2px 2px 0px #9333ea);
        letter-spacing: -0.02em;
    }
    .sticker-effect {
        filter: 
            drop-shadow(1px 1px 0 white) 
            drop-shadow(-1px -1px 0 white) 
            drop-shadow(1px -1px 0 white) 
            drop-shadow(-1px 1px 0 white)
            drop-shadow(2px 2px 0 rgba(0,0,0,0.1));
    }
    .comic-halftone {
        background-image: radial-gradient(#1e293b 15%, transparent 16%);
        background-size: 6px 6px;
        opacity: 0.05;
    }
    .benday-dots {
        background-image: radial-gradient(#1e293b 20%, transparent 20%);
        background-size: 3px 3px;
    }
    .chromatic-offset {
        text-shadow: 
            -1px -1px 0 rgba(255,0,0,0.3),
            1px 1px 0 rgba(0,255,255,0.3);
    }
    .panel-border {
        border: 4px solid #1e293b;
    }

    /* Street Authenticator Elements */
    .holographic-shimmer {
        background: linear-gradient(135deg, 
            rgba(255, 255, 255, 0) 0%, 
            rgba(255, 255, 255, 0.8) 50%, 
            rgba(255, 255, 255, 0) 100%);
        background-size: 200% 200%;
        animation: shimmer 3s infinite linear;
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .tape-effect {
        position: absolute;
        width: 45px;
        height: 18px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(2px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        z-index: 30;
        transform: rotate(-45deg);
        pointer-events: none;
    }

    .security-stamp {
        position: absolute;
        font-weight: 900;
        padding: 2px 8px;
        border: 3px solid currentColor;
        border-radius: 6px;
        letter-spacing: 0.1em;
        opacity: 0.12;
        user-select: none;
        pointer-events: none;
        z-index: 5;
    }

    .perforated-divider {
        position: relative;
        border-left: 4px dashed #1e293b;
    }
    .perforated-divider::before,
    .perforated-divider::after {
        content: '';
        position: absolute;
        left: -12px;
        width: 20px;
        height: 20px;
        background-color: white; 
        border: 4px solid #1e293b;
        border-radius: 50%;
        z-index: 20;
    }
    .perforated-divider::before { top: -12px; }
    .perforated-divider::after { bottom: -12px; }
    .screentone {
        background-image: radial-gradient(circle, #1e293b 1px, transparent 0);
        background-size: 8px 8px;
        opacity: 0.05;
    }
</style>

<div class="p-8 flex flex-wrap justify-center gap-6 print:p-0 print:gap-0 print:block print-container">
    @foreach($items as $item)
        <div class="print:inline-block print:m-1 print:break-inside-avoid shadow-lg rounded-[2mm] overflow-hidden">
            <div class="relative overflow-hidden bg-white panel-border flex flex-col print:shadow-none print:border-4" 
                 style="width: 100mm; height: 40mm; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                
                <!-- Tape Effects -->
                <div class="tape-effect -top-2 -left-4 opacity-70"></div>
                <div class="tape-effect -bottom-2 -right-4 rotate-[135deg] opacity-50"></div>

                <!-- Background Shapes -->
                <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden text-slate-900">
                    <!-- Halftone Pattern -->
                    <div class="absolute inset-0 z-0 comic-halftone opacity-[0.08]"></div>
                    
                    <!-- Subtle Global Texture -->
                    <div class="absolute inset-0 opacity-[0.15] z-0" 
                         style="background-image: repeating-linear-gradient(135deg, #475569 0, #475569 0.5px, transparent 0.5px, transparent 10px);"></div>

                    <!-- Accent Color Block (Top Corner) -->
                    <div class="absolute -right-5 -top-10 w-[40mm] h-[40mm] bg-purple-100/50 rotate-[45deg] border-b border-purple-200/80"></div>
                </div>

                <!-- Top Identity Bar -->
                <div class="relative z-10 w-full h-[3mm] bg-slate-900 flex items-center px-4 gap-1">
                    <div class="w-1 h-1 bg-white rounded-full opacity-50"></div>
                    <div class="w-1 h-1 bg-white rounded-full opacity-50"></div>
                    <div class="w-1 h-1 bg-white rounded-full opacity-50"></div>
                </div>

                <!-- Main Body: Ticket Stub Layout -->
                <div class="relative z-10 flex flex-1 overflow-hidden">
                    <!-- Left: QR Code Identity Area (SQUARE STUB) -->
                    <div class="w-[40mm] flex flex-col items-center justify-center bg-white p-2 relative overflow-hidden shrink-0">
                        <div class="absolute inset-0 opacity-[0.03] pointer-events-none screentone"></div>
                        
                        <!-- Security Stamp (Over QR area) -->
                        <div class="security-stamp top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -rotate-[25deg] text-slate-900 scale-125">Asli</div>

                        <div class="p-2 bg-white border-4 border-slate-900 rounded-xl shadow-[4px_4px_0px_#9333ea] z-10 flex items-center justify-center">
                            {!! QrCode::size(100)->margin(1)->color(15, 23, 42)->generate($item->qr_payload) !!}
                        </div>
                        <p class="mt-1 text-[1.4mm] font-black text-slate-900 tracking-[0.2mm] italic opacity-70 z-10 underline">Optic ID Manifest</p>
                    </div>

                    <!-- Vertical Perforated Divider -->
                    <div class="perforated-divider h-full"></div>

                    <!-- Right: Info Details (RECTANGULAR BODY) -->
                    <div class="flex-1 bg-slate-50 p-4 flex flex-col relative overflow-hidden min-w-0">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 opacity-[0.05] pointer-events-none screentone"></div>

                        <!-- Decorative Barcode -->
                        <div class="absolute top-3 right-4 opacity-30">
                            <div class="flex gap-[0.2mm] h-[3.5mm] items-end">
                                <div class="bg-slate-900 w-[0.3mm] h-full"></div>
                                <div class="bg-slate-900 w-[0.8mm] h-[80%]"></div>
                                <div class="bg-slate-900 w-[0.2mm] h-full"></div>
                                <div class="bg-slate-900 w-[0.5mm] h-[90%]"></div>
                                <div class="bg-slate-900 w-[0.2mm] h-full"></div>
                            </div>
                        </div>

                        <div class="relative z-10 flex flex-col h-full justify-center gap-3">
                            <!-- Title -->
                            <div class="mb-0">
                                <span class="text-[1.3mm] font-black text-slate-400 tracking-widest mb-0.5 block">Manifest Judul</span>
                                <div class="flex items-start overflow-hidden">
                                    <h2 class="text-[3.5mm] font-black text-slate-900 leading-[1.1] italic chromatic-offset" 
                                        style="text-shadow: 1px 1px 0px white, 2px 2px 0px rgba(0,0,0,0.05);
                                               display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $item->book->title }}
                                    </h2>
                                </div>
                            </div>

                            <!-- Footer Details -->
                            <div class="border-t-2 border-slate-900 border-dotted pt-2 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[1.3mm] font-black text-slate-400 tracking-widest leading-none">Kode Identifikasi</span>
                                    <p class="text-[3mm] font-black text-slate-900 tracking-tight truncate max-w-[45mm]">{{ $item->code }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<style>
    @media print {
        @page {
            size: 100mm 40mm;
            margin: 5mm;
        }
        body { margin: 0; padding: 0; background: white; }
        .print-container { display: block !important; padding: 0 !important; }
    }
</style>
@endsection
