@extends('layouts.print')

@section('title', 'Label Buku - ' . $item->code)

@section('content')
<div class="page-container">
    <div class="book-sticker">
        <!-- Sidebar with Logo & Network -->
        <div class="sticker-left">
            <span class="vertical-text">PERPUSTAKAAN</span>
        </div>
        
        <!-- Main Content -->
        <div class="sticker-main">
            <div class="sticker-header">
                {{ Str::limit($item->book->author, 20) }}
            </div>
            
            <div class="sticker-code">
                {{ $item->code }}
            </div>
            
            <div class="sticker-qr">
                {!! QrCode::size(50)->margin(0)->generate($item->qr_payload) !!}
            </div>
            
            <div class="sticker-footer">
                {{ Str::limit($item->book->title, 25) }}
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@600;800&display=swap');

    body {
        margin: 0;
        padding: 0;
        background: #e2e8f0;
        font-family: 'Inter', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    /* Sticker Size: approx 50mm x 30mm (Common label size) */
    .book-sticker {
        width: 60mm;
        height: 35mm;
        background: white;
        border: 1px solid #cbd5e1;
        border-radius: 2mm;
        display: flex;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .sticker-left {
        width: 6mm;
        background: #1e293b;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .vertical-text {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        font-size: 3mm;
        font-weight: 800;
        letter-spacing: 0.5mm;
        white-space: nowrap;
    }

    .sticker-main {
        flex: 1;
        padding: 2mm;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        text-align: center;
    }

    .sticker-header {
        font-size: 2.5mm;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sticker-code {
        font-family: 'Roboto Mono', monospace;
        font-size: 5mm;
        font-weight: 700;
        color: #000;
        letter-spacing: -0.5px;
        border: 1px solid #000;
        padding: 0.5mm 1.5mm;
        border-radius: 1mm;
    }

    .sticker-qr {
        margin: 1mm 0;
    }

    .sticker-footer {
        font-size: 2.5mm;
        color: #0f1729;
        font-weight: 700;
        line-height: 1.1;
        width: 100%;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media print {
        body {
            background: none;
            display: block;
        }

        .page-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .book-sticker {
            border: 1px dashed #94a3b8; /* Guide for cutting */
            box-shadow: none;
            page-break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print {
            display: none !important;
        }
    }
</style>
@endsection
