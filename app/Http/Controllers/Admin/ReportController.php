<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Borrow;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Print borrows report for a specific month.
     */
    public function printBorrows(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $month = (int) $request->month;
        $year = (int) $request->year;

        $borrows = Borrow::with(['user', 'bookItem.book'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->get();

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $stats = [
            'total' => $borrows->count(),
            'approved' => $borrows->where('status', 'approved')->count(),
            'returned' => $borrows->where('status', 'returned')->count(),
            'pending' => $borrows->where('status', 'pending')->count(),
        ];

        // Log this print action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin_print_report',
            'details' => "Print laporan bulan {$monthNames[$month]} {$year}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('reports.borrows-print', [
            'borrows' => $borrows,
            'month' => $month,
            'year' => $year,
            'monthName' => $monthNames[$month],
            'stats' => $stats,
        ]);
    }
}
