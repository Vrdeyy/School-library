@extends('layouts.print')

@section('title', 'Kartu Anggota - ' . $user->name)

@section('content')
<div class="page-container">
    <div class="id-card">
        <!-- Background Pattern -->
        <div class="bg-pattern"></div>
        
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="org-name">
                <h3>PERPUSTAKAAN DIGITAL</h3>
                <p>Kartu Anggota</p>
            </div>
        </div>

        <!-- Content -->
        <div class="card-body">
            <div class="photo-area">
                <!-- Placeholder avatar if no photo -->
                <div class="avatar-placeholder">
                    {{ substr($user->name, 0, 1) }}
                </div>
            </div>
            
            <div class="user-details">
                <h2 class="name">{{ $user->name }}</h2>
                <div class="detail-row">
                    <span class="label">ID / NIS</span>
                    <span class="value">{{ $user->nis ?? $user->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Status</span>
                    <span class="value">{{ ucfirst($user->role) }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Bergabung</span>
                    <span class="value">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            <div class="qr-area">
                {!! QrCode::size(80)->margin(0)->generate($user->qr_payload) !!}
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-strip">
            <p>Kartu ini wajib dibawa saat peminjaman buku</p>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

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

    /* CR80 Size: 85.60mm x 53.98mm */
    .id-card {
        width: 85.6mm;
        height: 53.98mm;
        background: white;
        border-radius: 4mm;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .bg-pattern {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 60%;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        border-bottom-left-radius: 50% 20%;
        border-bottom-right-radius: 50% 20%;
        z-index: 0;
    }

    .header {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        padding: 12px 15px 5px;
        color: white;
        gap: 10px;
    }

    .logo svg {
        stroke: white;
    }

    .org-name h3 {
        margin: 0;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .org-name p {
        margin: 0;
        font-size: 8px;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card-body {
        position: relative;
        z-index: 1;
        display: flex;
        padding: 10px 15px;
        gap: 12px;
        flex: 1;
        align-items: center;
    }

    .photo-area {
        flex-shrink: 0;
    }

    .avatar-placeholder {
        width: 45px;
        height: 45px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 800;
        color: #4f46e5;
        border: 2px solid rgba(255,255,255,0.8);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .user-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .name {
        margin: 0 0 6px;
        font-size: 12px;
        font-weight: 800;
        color: #1e293b;
        text-transform: uppercase;
        line-height: 1.2;
    }

    .detail-row {
        display: flex;
        font-size: 7px;
        margin-bottom: 2px;
    }

    .detail-row .label {
        width: 35px;
        color: #64748b;
        font-weight: 600;
    }

    .detail-row .value {
        color: #334155;
        font-weight: 500;
    }

    .qr-area {
        flex-shrink: 0;
        background: white;
        padding: 2px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
    }

    .footer-strip {
        background: #f8fafc;
        padding: 4px;
        text-align: center;
        border-top: 1px solid #e2e8f0;
    }

    .footer-strip p {
        margin: 0;
        font-size: 6px;
        color: #94a3b8;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    @media print {
        body {
            background: none;
            display: block;
            margin: 0;
        }

        .page-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Center on page */
        }

        .id-card {
            border: 1px solid #ddd; /* Light border for cutting guide */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            page-break-inside: avoid;
        }

        .no-print {
            display: none !important;
        }
    }
</style>
@endsection
