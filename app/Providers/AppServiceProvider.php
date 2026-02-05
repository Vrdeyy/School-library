<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Livewire\Livewire::component('app.filament.admin.components.user-print-table', \App\Filament\Admin\Components\UserPrintTable::class);
        \Livewire\Livewire::component('app.filament.admin.components.book-item-print-table', \App\Filament\Admin\Components\BookItemPrintTable::class);
    }
}
