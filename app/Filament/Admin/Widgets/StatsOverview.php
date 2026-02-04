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
    protected function getStats(): array
    {
        return [
            Stat::make('Total Buku (Judul)', Book::count())
                ->description('Jumlah judul buku')
                ->icon('heroicon-o-book-open')
                ->color('primary'),

            Stat::make('Total Eksemplar', BookItem::count())
                ->description(BookItem::where('status', 'available')->count() . ' tersedia')
                ->icon('heroicon-o-rectangle-stack')
                ->color('success'),

            Stat::make('Pending Approval', Borrow::where('status', 'pending')->count())
                ->description('Menunggu persetujuan')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Pending Return', Borrow::where('status', 'returning')->count())
                ->description('Menunggu approval pengembalian')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('info'),

            Stat::make('Sedang Dipinjam', Borrow::where('status', 'approved')->count())
                ->description(Borrow::where('status', 'approved')->whereNotNull('due_date')->where('due_date', '<', now())->count() . ' overdue')
                ->icon('heroicon-o-arrow-right-circle')
                ->color('danger'),

            Stat::make('Total Member', User::where('role', 'member')->count())
                ->description(User::where('is_suspended', true)->count() . ' suspended')
                ->icon('heroicon-o-users')
                ->color('gray'),
        ];
    }
}
