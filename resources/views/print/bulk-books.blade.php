@extends('layouts.print')

@section('title', 'Cetak Massal Label Buku')

@section('content')
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    .ONOMATOPOEIA {
        font-family: 'Outfit', sans-serif;
        font-weight: 900;
        text-transform: uppercase;
        font-style: italic;
        -webkit-text-stroke: 1px #1e293b;
        color: white;
        filter: drop-shadow(2px 2px 0px #2563eb);
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
</style>

<div class="p-8 flex flex-wrap justify-center gap-6 print:p-0 print:gap-0 print:block font-['Outfit']">
    @foreach($items as $item)
        <div class="print:inline-block print:m-1 print:break-inside-avoid shadow-lg rounded-[4mm] overflow-hidden">
            <div class="relative overflow-hidden bg-slate-50 border-2 border-slate-900 flex flex-col print:shadow-none print:border-4" 
                 style="width: 85.6mm; height: 53.98mm; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                
                <!-- Background Shapes -->
                <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden text-slate-900">
                    <!-- Halftone Pattern -->
                    <div class="absolute inset-0 z-0 comic-halftone"></div>
                    
                    <!-- Subtle Global Texture -->
                    <div class="absolute inset-0 opacity-[0.05] z-0" 
                         style="background-image: repeating-linear-gradient(135deg, #475569 0, #475569 0.5px, transparent 0.5px, transparent 10px);"></div>

                    <!-- Accent Color Block -->
                    <div class="absolute -right-5 -top-10 w-[45mm] h-[75mm] bg-blue-100/30 rotate-[15deg] border-l border-blue-200/50"></div>
                    
                    <!-- Geometric Pattern (Tech Grid) -->
                    <svg class="absolute right-[-2mm] bottom-[0mm] opacity-[0.15] text-blue-900" 
                         style="width: 35mm; height: 35mm;"
                         fill="none" stroke="currentColor" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid-bulk-label-{{ $item->id }}" width="8" height="8" patternUnits="userSpaceOnUse">
                                <path d="M 8 0 L 0 0 0 8" fill="none" stroke="currentColor" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#grid-bulk-label-{{ $item->id }})" />
                    </svg>

                    <!-- Decorative Dots -->
                    <div class="absolute top-[20mm] left-[40%] w-12 h-6 opacity-10 benday-dots"></div>
                </div>

                <!-- Top Accent Bar -->
                <div class="relative z-10 w-full h-[3mm] bg-black"></div>

                <!-- Body -->
                <div class="relative z-10 px-5 pt-4 flex flex-1 gap-6 items-center">
                    <!-- Left Info Panel -->
                    <div class="flex-1 flex flex-col min-w-0">
                        <!-- Title Container -->
                        <div class="mb-4">
                            <div class="flex items-center gap-1.5 mb-1.5 opacity-60">
                                <span class="text-[1.6mm] font-black text-slate-900 tracking-widest uppercase italic">CATALOG_TITLE</span>
                                <div class="h-1 flex-1 benday-dots"></div>
                            </div>
                            @php
                                $title = $item->book->title;
                                $tLen = mb_strlen($title);
                                $tFontSize = 'text-[3.8mm]';
                                if ($tLen > 100) $tFontSize = 'text-[2.2mm]';
                                elseif ($tLen > 80) $tFontSize = 'text-[2.6mm]';
                                elseif ($tLen > 60) $tFontSize = 'text-[3mm]';
                                elseif ($tLen > 40) $tFontSize = 'text-[3.4mm]';
                            @endphp
                            <div class="h-[14mm] flex items-start overflow-hidden">
                                <h2 class="{{ $tFontSize }} font-black uppercase text-slate-900 leading-[1.1] italic chromatic-offset" 
                                    style="text-shadow: 1.5px 1.5px 0px white, 2.5px 2.5px 0px rgba(0,0,0,0.05);
                                           display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                    {{ $title }}
                                </h2>
                            </div>
                        </div>

                        <!-- ID Badge Style -->
                        <div>
                            <div class="flex items-center gap-1.5 mb-2 opacity-60">
                                <span class="text-[1.6mm] font-black text-slate-900 tracking-widest uppercase italic">ITEM_IDENTIFIER</span>
                                <div class="h-1 flex-1 benday-dots"></div>
                            </div>
                            <div class="flex bg-white border-2 border-slate-900 rounded-lg overflow-hidden shadow-[4px_4px_0px_#2563eb] w-full items-stretch h-[8mm]">
                                <div class="bg-slate-900 text-white px-2 py-1 flex items-center border-r-2 border-slate-900">
                                    <span class="text-[1.8mm] font-black uppercase tracking-tighter italic">Code</span>
                                </div>
                                <div class="flex-1 px-2 py-1 bg-white flex items-center justify-center min-w-0">
                                    @php
                                        $code = $item->code;
                                        $cLen = mb_strlen($code);
                                        $cFontSize = 'text-[3.8mm]';
                                        if ($cLen > 25) $cFontSize = 'text-[2mm]';
                                        elseif ($cLen > 20) $cFontSize = 'text-[2.5mm]';
                                        elseif ($cLen > 15) $cFontSize = 'text-[3mm]';
                                    @endphp
                                    <span class="{{ $cFontSize }} font-black text-slate-900 uppercase break-all leading-none text-center">
                                        {{ $code }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right QR Panel -->
                    <div class="flex flex-col items-center gap-2 shrink-0">
                        <div class="p-2 bg-white border-2 border-slate-900 rounded-xl shadow-[4px_4px_0px_#2563eb]">
                            {!! QrCode::size(95)->margin(1)->color(15, 23, 42)->generate($item->qr_payload) !!}
                        </div>
                        <p class="text-[1.4mm] font-black text-slate-900 tracking-[0.5mm] uppercase italic opacity-70">OPTIC_ID_SCAN</p>
                    </div>
                </div>

                <!-- Footer Bar -->
                <div class="relative z-10 w-full mt-auto h-[2.5mm] bg-blue-600 border-t-2 border-slate-900"></div>
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
        .print\:m-1 { margin: 1mm !important; }
    }
</style>
@endsection
