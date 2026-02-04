<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman - {{ $monthName }} {{ $year }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 18px;
            font-weight: normal;
            color: #666;
        }
        .header .period {
            margin-top: 10px;
            font-size: 14px;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .summary-item .label {
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4a90a4;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pending { background: #ffc107; color: #000; }
        .status-approved { background: #28a745; color: #fff; }
        .status-returning { background: #17a2b8; color: #fff; }
        .status-returned { background: #6c757d; color: #fff; }
        .status-rejected { background: #dc3545; color: #fff; }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        .print-only {
            display: none;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            .print-only { display: block; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 14px; cursor: pointer; background: #4a90a4; color: white; border: none; border-radius: 5px;">
            🖨️ Cetak Laporan
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; font-size: 14px; cursor: pointer; background: #6c757d; color: white; border: none; border-radius: 5px; margin-left: 10px;">
            ✕ Tutup
        </button>
    </div>

    <div class="header">
        <h1>PERPUSTAKAAN</h1>
        <h2>Laporan Peminjaman Buku</h2>
        <div class="period">Periode: {{ $monthName }} {{ $year }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="value">{{ $stats['total'] }}</div>
            <div class="label">Total Transaksi</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ $stats['approved'] }}</div>
            <div class="label">Dipinjam</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ $stats['returned'] }}</div>
            <div class="label">Dikembalikan</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ $stats['pending'] }}</div>
            <div class="label">Pending</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Kode Buku</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrows as $index => $borrow)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $borrow->id }}</td>
                <td>{{ $borrow->user->name ?? '-' }}</td>
                <td>{{ $borrow->bookItem->code ?? '-' }}</td>
                <td>{{ $borrow->bookItem->book->title ?? '-' }}</td>
                <td>{{ $borrow->borrow_date?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $borrow->due_date?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $borrow->return_date?->format('d/m/Y') ?? '-' }}</td>
                <td>
                    <span class="status status-{{ $borrow->status }}">
                        {{ strtoupper($borrow->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; color: #666;">Tidak ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        <p>Oleh: {{ auth()->user()->name ?? 'Admin' }}</p>
    </div>
</body>
</html>
