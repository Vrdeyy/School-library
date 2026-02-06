<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class ImportExportData extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationLabel = 'Import / Export';
    protected static ?string $navigationGroup = 'System Tools';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Import / Export Data';
    
    protected static string $view = 'filament.admin.pages.import-export-data';
}
