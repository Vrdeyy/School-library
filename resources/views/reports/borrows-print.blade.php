<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman - {{ $monthName }} {{ $year }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; background: white !important; }
            .report-container { border: none !important; box-shadow: none !important; margin: 0 !important; width: 100% !important; max-width: none !important; }
            .bg-tech { display: none !important; }
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
        }

        .bg-tech {
            background-image: repeating-linear-gradient(135deg, #cbd5e1 0, #cbd5e1 0.5px, transparent 0.5px, transparent 20px);
            opacity: 0.4;
        }

        .report-container {
            width: 210mm;
            min-height: 297mm;
            margin: 2rem auto;
            background: white;
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 p-4">
    <!-- Screen Background -->
    <div class="fixed inset-0 bg-tech pointer-events-none z-0"></div>

    <!-- Actions -->
    <div class="no-print relative z-20 flex justify-center gap-4 mb-8">
        <button onclick="window.print()" class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-xl shadow-blue-500/20 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            PRINT_REPORT
        </button>
        <button onclick="window.close()" class="px-8 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-xl">
            CLOSE_ARCHIVE
        </button>
    </div>

    <div class="report-container border-2 border-slate-200 shadow-2xl p-12 rounded-[2.5rem] bg-white overflow-hidden relative">
        <!-- Logo Background Watermark -->
        <svg class="absolute -right-20 -top-20 w-96 h-96 opacity-[0.03] text-blue-500 -rotate-12 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>

        <!-- Header -->
        <header class="relative z-10 flex flex-col items-center text-center mb-16">
            <div class="w-20 h-20 bg-white shadow-xl rounded-full flex items-center justify-center mb-6 ring-4 ring-slate-50">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain">
            </div>
            <div>
                <h1 class="text-4xl font-black tracking-[0.2em] text-slate-900 uppercase italic leading-none mb-2">MANIFEST_LAPORAN_PEMINJAMAN</h1>
                <p class="text-[10px] font-black text-blue-600 tracking-[0.5em] uppercase border-b-2 border-blue-600 pb-2 inline-block">PERPUSTAKAAN SMK YAJ DEPOK</p>
                <p class="mt-6 text-sm font-bold text-slate-500 uppercase tracking-widest italic">Periode: {{ $monthName }} {{ $year }}</p>
            </div>
        </header>

        <!-- Summary -->
        <div class="grid grid-cols-4 gap-6 mb-16 relative z-10">
            <div class="bg-slate-50 border-2 border-slate-100 p-6 rounded-[2rem] text-center shadow-sm">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">TOTAL_RECORDS</p>
                <p class="text-3xl font-black text-slate-900 tracking-tighter">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-blue-50 border-2 border-blue-100 p-6 rounded-[2rem] text-center shadow-sm">
                <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-1">STATE_ACTIVE</p>
                <p class="text-3xl font-black text-blue-600 tracking-tighter">{{ $stats['approved'] }}</p>
            </div>
            <div class="bg-green-50 border-2 border-green-100 p-6 rounded-[2rem] text-center shadow-sm">
                <p class="text-[9px] font-black text-green-400 uppercase tracking-widest mb-1">STATE_RETURNED</p>
                <p class="text-3xl font-black text-green-600 tracking-tighter">{{ $stats['returned'] }}</p>
            </div>
            <div class="bg-amber-50 border-2 border-amber-100 p-6 rounded-[2rem] text-center shadow-sm">
                <p class="text-[9px] font-black text-amber-400 uppercase tracking-widest mb-1">STATE_PENDING</p>
                <p class="text-3xl font-black text-amber-600 tracking-tighter">{{ $stats['pending'] }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="relative z-10">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b-4 border-slate-900">
                        <th class="py-4 px-2 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">#</th>
                        <th class="py-4 px-2 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest italic">ID_TX</th>
                        <th class="py-4 px-2 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">OPERATOR_CLIENT</th>
                        <th class="py-4 px-2 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">ASSET_IDENT</th>
                        <th class="py-4 px-2 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">ASSET_TITLE</th>
                        <th class="py-4 px-2 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest italic text-center">LOAN_STAMP</th>
                        <th class="py-4 px-2 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest italic text-center">RETURN_STAMP</th>
                        <th class="py-4 px-2 text-right text-[10px] font-black text-slate-500 uppercase tracking-widest">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($borrows as $index => $borrow)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="py-5 px-2 text-[11px] font-bold text-slate-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-5 px-2 text-[11px] font-black text-blue-600 uppercase italic">{{ $borrow->id }}</td>
                        <td class="py-5 px-2">
                            <p class="text-[11px] font-black text-slate-900 uppercase italic line-clamp-1">{{ $borrow->user->name ?? '-' }}</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">{{ $borrow->user->nis ?? 'NIS_UNKNOWN' }}</p>
                        </td>
                        <td class="py-5 px-2 text-[10px] font-black text-slate-600">{{ $borrow->bookItem->code ?? '-' }}</td>
                        <td class="py-5 px-2 text-[11px] font-black text-slate-900 uppercase italic line-clamp-1">{{ $borrow->bookItem->book->title ?? '-' }}</td>
                        <td class="py-5 px-2 text-center">
                            <div class="bg-slate-50 px-2 py-1 rounded-lg border border-slate-100 inline-block">
                                <p class="text-[9px] font-black text-slate-900">{{ $borrow->borrow_date?->format('d/m/Y') ?? '-' }}</p>
                                <p class="text-[8px] font-bold text-blue-500 tracking-tighter">DUE: {{ $borrow->due_date?->format('d/m/Y') ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="py-5 px-2 text-[10px] font-black text-slate-600 text-center">{{ $borrow->return_date?->format('d/m/Y') ?? '-' }}</td>
                        <td class="py-5 px-2 text-right">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-600 border-amber-200',
                                    'approved' => 'bg-blue-100 text-blue-600 border-blue-200',
                                    'returning' => 'bg-cyan-100 text-cyan-600 border-cyan-200',
                                    'returned' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    'rejected' => 'bg-red-100 text-red-600 border-red-200',
                                ];
                                $color = $statusColors[$borrow->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                            @endphp
                            <span class="px-3 py-1.5 rounded-full border text-[8px] font-black uppercase tracking-[0.2em] {{ $color }}">
                                {{ $borrow->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-24 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <svg class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                <p class="text-sm font-black uppercase tracking-[0.4em] italic">ZERO_TRANSACTION_FOUND</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <footer class="mt-20 pt-10 border-t-2 border-slate-900 flex justify-between items-end relative z-10">
            <div class="space-y-2">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">SYSTEM_VERSION_LOG</p>
                <p class="text-[10px] font-black text-slate-900 tracking-wider">TERMINAL_v5.0_PRO_EDITION</p>
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest italic">STAMP: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
            <div class="text-right">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">AUTHORIZED_BY</p>
                <div class="inline-block border-b-2 border-slate-900 pb-2 mb-1">
                    <p class="text-lg font-black text-slate-900 uppercase italic">{{ auth()->user()->name ?? 'Administrator' }}</p>
                </div>
                <p class="text-[8px] font-black text-blue-600 tracking-[0.5em] uppercase">SYSTEM_OPERATOR</p>
            </div>
        </footer>
    </div>
</body>
</html>
