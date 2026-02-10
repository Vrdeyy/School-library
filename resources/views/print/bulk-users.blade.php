@extends('layouts.print')

@section('title', 'Cetak Massal Kartu Anggota')

@section('content')
<div class="bulk-container">
    @foreach($users as $user)
        <div class="id-card-wrapper">
             <div class="id-card">
                <!-- Background Pattern -->
                <div class="bg-pattern"></div>
                
                <!-- Header -->
                <div class="header">
                    <div class="logo">
                        <img src="{{ asset('images/logo.png') }}"alt="Logo Perpustakaan" class="logo-img">
                    </div>
                    <div class="org-name">
                        <h3>PERPUSTAKAAN SMK YAJ</h3>
                        <p>Kartu Anggota</p>
                    </div>
                </div>

                <!-- Content -->
                <div class="card-body">
                    <div class="photo-area">
                        <div class="avatar-placeholder">
                            <svg
                                width="22"
                                height="22"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="#6b7280"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="user-details">
                        <h2 class="name">{{ $user->name }}</h2>
                        <div class="detail-row">
                            <span class="label">ID / NIS</span>
                            <span class="value">{{ $user->nis ?? $user->id }}</span>
                        </div>
                        <!-- <div class="detail-row">
                            <span class="label">Status</span>
                            <span class="value">{{ ucfirst($user->role) }}</span>
                        </div> -->
                        @if($user->role === 'user')
                    <div class="detail-row">
                        <span class="label">Kelas</span>
                        <span class="value">{{ $user->kelas ?? '-' }} {{ $user->jurusan ?? '' }}</span>
                    </div>
                @else
                    <div class="detail-row">
                        <span class="label">Status</span>
                        <span class="value">{{ ucfirst($user->role) }}</span>
                    </div>
                @endif
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
    @endforeach
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background: #fff;
    }

    .bulk-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding: 10px;
        justify-content: flex-start;
    }

    .id-card-wrapper {
        page-break-inside: avoid;
        padding: 5px;
        border: 1px dashed #ccc; /* Cutting guide */
    }

    /* Reuse ID Card Styles from user-card.blade.php but simplified where needed */
    /* Copy paste relevant styles with adjustments for bulk context if needed */
    /* ... (Styles from user-card.blade.php) ... */
    
    /* CR80 Size: 85.60mm x 53.98mm */
    .id-card {
        width: 85.6mm;
        height: 53.98mm;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4mm;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .logo-img {
    width: 26px;
    height: 26px;
}


    /* ... rest of styles ... */
    .bg-pattern {
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 70%;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        z-index: 0;
    }
    .header { position: relative; z-index: 1; display: flex; align-items: center; padding: 12px 15px 0px; color: white; gap: 10px; }
    .logo svg { stroke: white; }
    .org-name h3 { margin: 0; font-size: 10px; font-weight: 800; letter-spacing: 0.5px; }
    .org-name p { margin: 0; font-size: 8px; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px; }
    .card-body { position: relative; z-index: 1; display: flex; padding: 10px 15px; gap: 12px; flex: 1; align-items: center; }
    .photo-area { flex-shrink: 0; }
    .avatar-placeholder { width: 45px; height: 45px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; color: #4f46e5; border: 2px solid rgba(255,255,255,0.8); }
    .avatar-placeholder svg {
    display: block;
    width: 22px;
    height: 22px;
    stroke: #6b7280;
    }
    .user-details { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    .name { margin: 0 0 6px; font-size: 10px; font-weight: 800; color: white; text-transform: uppercase; line-height: 1.2; word-break: break-all; }
    .detail-row { display: flex; font-size: 7px; margin-bottom: 2px; }
    .detail-row .label { width: 35px; color: white; font-weight: 600; }
    .detail-row .value { color: white; font-weight: 500; }
    .qr-area { flex-shrink: 0; background: white; padding: 2px; border-radius: 4px; border: 1px solid #e2e8f0; }
    .footer-strip { background: #f8fafc; padding: 4px; text-align: center; border-top: 1px solid #e2e8f0; }
    .footer-strip p { margin: 0; font-size: 6px; color: #94a3b8; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; }
    .avatar-placeholder::before {
    content: none !important;
}

    @media print {
        body { margin: 0; padding: 0; }
        .bulk-container { padding: 0; gap: 0; }
        .id-card-wrapper { border: 1px solid #eee; margin: 0;
        /* -webkit-print-color-adjust: exact; */
        print-color-adjust: exact;
        /* page-break-inside: avoid; */
    } /* Slight border for cutting guide in print */
        .no-print { display: none; }
    }
</style>
@endsection
