<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Book;
use App\Models\BookItem;
use App\Models\Borrow;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Trends for last 7 days
        $borrowTrend = [];
        $bookTrend = [];
        $itemTrend = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $borrowTrend[] = Borrow::whereDate('borrow_date', $date)->count();
            $bookTrend[] = Book::whereDate('created_at', $date)->count();
            $itemTrend[] = BookItem::whereDate('created_at', $date)->count();
        }

        return [
            Stat::make('Selamat Datang,', auth()->user()->name)
                ->description('Semoga harimu menyenangkan!')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('success')
                ->icon('heroicon-o-user-circle'),

            Stat::make('Total Buku (Judul)', Book::count())
                ->description('Koleksi judul unik')
                ->descriptionIcon('heroicon-m-book-open')
                ->chart($bookTrend) 
                ->icon('heroicon-o-book-open')
                ->color('primary'),

            Stat::make('Total Eksemplar', BookItem::count())
                ->description(BookItem::where('status', 'available')->count() . ' tersedia di rak')
                ->descriptionIcon('heroicon-m-check-circle')
                ->chart($itemTrend)
                ->icon('heroicon-o-rectangle-stack')
                ->color('success'),

            Stat::make('Menunggu Approval', Borrow::where('status', 'pending')->count())
                ->description('Permintaan baru')
                ->descriptionIcon('heroicon-m-clock')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Sedang Dipinjam', Borrow::where('status', 'approved')->count())
                ->description(Borrow::where('status', 'approved')->whereNotNull('due_date')->where('due_date', '<', now())->count() . ' buku terlambat')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->chart($borrowTrend)
                ->icon('heroicon-o-arrow-right-circle')
                ->color('danger'),

            Stat::make('Total Member', User::where('role', '!=', 'admin')->count())
                ->description(User::where('is_suspended', true)->count() . ' akun suspend')
                ->descriptionIcon('heroicon-m-users')
                ->icon('heroicon-o-users')
                ->color('info'),
        ];
    }
}
