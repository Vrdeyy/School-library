<?php

namespace App\Http\Controllers;

use App\Exports\BooksExport;
use App\Exports\BooksTemplateExport;
use App\Exports\BorrowsExport;
use App\Exports\UsersExport;
use App\Exports\UsersTemplateExport;
use App\Imports\BooksImport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller
{
    // =====================
    // TEMPLATE DOWNLOADS
    // =====================
    
    public function usersTemplate()
    {
        return Excel::download(new UsersTemplateExport, 'users_template.xlsx');
    }

    public function booksTemplate()
    {
        return Excel::download(new BooksTemplateExport, 'books_template.xlsx');
    }

    // =====================
    // DATA EXPORTS
    // =====================

    public function exportUsers()
    {
        return Excel::download(new UsersExport, 'users_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportBooks()
    {
        return Excel::download(new BooksExport, 'books_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportBorrows(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        $filename = 'borrows_' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.xlsx';
        
        return Excel::download(new BorrowsExport($month, $year), $filename);
    }

    // =====================
    // DATA IMPORTS
    // =====================

    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            
            return redirect()->back()
                ->with('user_success', 'Users berhasil di-import!')
                ->with('active_tab', 'users');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('user_error', 'Import gagal: ' . $e->getMessage())
                ->with('active_tab', 'users');
        }
    }

    public function importBooks(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new BooksImport, $request->file('file'));
            
            return redirect()->back()
                ->with('book_success', 'Buku berhasil di-import!')
                ->with('active_tab', 'books');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('book_error', 'Import gagal: ' . $e->getMessage())
                ->with('active_tab', 'books');
        }
    }
}
