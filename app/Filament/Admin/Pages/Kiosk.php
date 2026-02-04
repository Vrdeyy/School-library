<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class Kiosk extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';
    
    protected static ?string $navigationLabel = 'Kiosk Mode';
    
    protected static ?string $title = 'Self-Service Kiosk';
    
    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.admin.pages.kiosk';
    
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
