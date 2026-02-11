@extends('layouts.print')

@section('title', 'Cetak Massal Label Buku')

@section('content')
<div class="p-8 flex flex-wrap justify-center gap-6 print:p-0 print:gap-0 print:block">
    @foreach($items as $item)
        <div class="print:inline-block print:m-1 print:break-inside-avoid shadow-lg rounded-[4mm] overflow-hidden">
            <div class="relative overflow-hidden bg-slate-50 border border-slate-300 flex flex-col print:shadow-none print:border" 
                 style="width: 85.6mm; height: 53.98mm; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                
                <!-- Background Shapes (Same as User Card) -->
                <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden text-slate-900">
                    <!-- Subtle Global Texture -->
                    <div class="absolute inset-0 opacity-[0.05]" 
                         style="background-image: repeating-linear-gradient(135deg, #475569 0, #475569 0.5px, transparent 0.5px, transparent 8px);"></div>

                    <!-- Accent Color Block -->
                    <div class="absolute -right-5 -top-10 w-[45mm] h-[75mm] bg-blue-100/30 rotate-[15deg] border-l border-blue-200/50"></div>
                    
                    <!-- Book Icon Watermark -->
                    <svg class="absolute left-[35mm] top-[30mm] opacity-[0.1] text-blue-600" 
                         style="width: 15mm; height: 15mm;"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>

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

                    <!-- Floating Plus Signs -->
                    <div class="absolute top-[5mm] right-[35mm] opacity-20 text-blue-500 font-bold text-[3mm]">+</div>
                    <div class="absolute bottom-[15mm] left-[40mm] opacity-20 text-blue-500 font-bold text-[3mm]">+</div>

                    <!-- Decorative Dots -->
                    <div class="absolute top-[20mm] left-[40%] w-12 h-6 opacity-10" 
                         style="background-image: radial-gradient(#1e40af 1px, transparent 1px); background-size: 5px 5px;"></div>
                </div>

                <!-- Top Accent Bar -->
                <div class="relative z-10 w-full h-[2.5mm] bg-slate-900"></div>

                <!-- Body -->
                <div class="relative z-10 px-5 pt-3 flex flex-1 gap-4 items-center">
                    <!-- Left QR Panel -->
                    <div class="flex flex-col items-center gap-1.5 shrink-0">
                        <div class="p-1.5 bg-white border-2 border-slate-900 rounded-xl shadow-[4px_4px_0px_#2563eb]">
                            {!! QrCode::size(105)->margin(1)->color(15, 23, 42)->generate($item->qr_payload) !!}
                        </div>
                        <p class="text-[1.4mm] font-black text-slate-900 tracking-[0.5mm] uppercase opacity-50 italic">SCAN TO VERIFY</p>
                    </div>

                    <!-- Right Info Panel -->
                    <div class="flex-1 flex flex-col h-full py-1 min-w-0">
                        <!-- Title Container -->
                        <div class="mb-4">
                            <div class="flex items-center gap-1.5 mb-1.5">
                                <span class="text-[1.6mm] font-extrabold text-blue-600 tracking-widest uppercase">Book Title</span>
                                <div class="h-[0.5px] flex-1 bg-blue-100"></div>
                            </div>
                            <h2 class="text-[3.8mm] font-black uppercase text-slate-900 leading-[1.15] line-clamp-3" 
                                style="text-shadow: 0.5px 0.5px 0px white;">
                                {{ $item->book->title }}
                            </h2>
                        </div>

                        <!-- ID Badge Style -->
                        <div>
                            <div class="flex items-center gap-1.5 mb-1.5">
                                <span class="text-[1.6mm] font-extrabold text-slate-400 tracking-widest uppercase">Identification</span>
                                <div class="h-[0.5px] flex-1 bg-slate-200"></div>
                            </div>
                            <div class="flex bg-white border-2 border-slate-900 rounded-lg overflow-hidden shadow-[4px_4px_0px_#2563eb] w-full items-stretch">
                                <div class="bg-slate-900 text-white px-2 py-1 flex items-center">
                                    <span class="text-[1.8mm] font-black uppercase tracking-tighter">CODE</span>
                                </div>
                                <div class="flex-1 px-3 py-1 bg-white flex items-center justify-center min-w-0">
                                    <span class="font-mono text-[3.8mm] font-black text-slate-900 uppercase break-all leading-none text-center">
                                        {{ $item->code }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Bar -->
                <div class="relative z-10 w-full mt-auto h-[1.5mm] bg-blue-600/20"></div>
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
