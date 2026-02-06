<?php

use Illuminate\Support\Facades\Route;
use App\Models\Book;
use App\Http\Controllers\Admin\ReportController;

use App\Http\Controllers\KioskPageController;

// Public Catalog as Homepage
Route::get('/', function () {
    $books = Book::with('items')->orderBy('title')->get();
    return view('catalog.index', compact('books'));
})->name('catalog');

// Standalone Kiosk Page
Route::get('/kiosk', [KioskPageController::class, 'index'])->name('kiosk');
Route::post('/api/kiosk/admin-login', [KioskPageController::class, 'adminLogin'])->name('kiosk.admin-login');

// Admin routes (requires auth)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/reports/borrows/print', [ReportController::class, 'printBorrows'])
        ->name('admin.reports.borrows.print');
        
    Route::get('/users/{user}/print-card', [App\Http\Controllers\Admin\PrintController::class, 'userCard'])
        ->name('admin.print.user-card');

    Route::get('/book-items/{item}/print-label', [App\Http\Controllers\Admin\PrintController::class, 'bookLabel'])
        ->name('admin.print.book-label');
        
    Route::get('/print/bulk-users', [App\Http\Controllers\Admin\PrintController::class, 'bulkUsers'])
        ->name('admin.print.bulk-users');

    Route::get('/print/bulk-books', [App\Http\Controllers\Admin\PrintController::class, 'bulkBooks'])
        ->name('admin.print.bulk-books');

    // Data Management (Import/Export)
    Route::prefix('data-management')->group(function () {
        // Templates
        Route::get('/users/template', [App\Http\Controllers\ImportExportController::class, 'usersTemplate'])
            ->name('admin.data.users-template');
        Route::get('/books/template', [App\Http\Controllers\ImportExportController::class, 'booksTemplate'])
            ->name('admin.data.books-template');
        
        // Exports
        Route::get('/users/export', [App\Http\Controllers\ImportExportController::class, 'exportUsers'])
            ->name('admin.data.export-users');
        Route::get('/books/export', [App\Http\Controllers\ImportExportController::class, 'exportBooks'])
            ->name('admin.data.export-books');
        Route::get('/borrows/export', [App\Http\Controllers\ImportExportController::class, 'exportBorrows'])
            ->name('admin.data.export-borrows');
        
        // Imports
        Route::post('/users/import', [App\Http\Controllers\ImportExportController::class, 'importUsers'])
            ->name('admin.data.import-users');
        Route::post('/books/import', [App\Http\Controllers\ImportExportController::class, 'importBooks'])
            ->name('admin.data.import-books');
    });
});
