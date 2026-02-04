<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class PrintCenter extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-printer';
    
    protected static ?string $navigationLabel = 'Pusat Cetak';
    
    protected static ?string $title = 'Pusat Cetak QR Massal';
    
    protected static ?int $navigationSort = 90;

    protected static string $view = 'filament.admin.pages.print-center';
}
