<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Settings';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'kiosk_login' => 'success',
                        'kiosk_borrow_request' => 'warning',
                        'kiosk_return_request' => 'info',
                        'admin_export_users' => 'success',
                        'admin_export_books' => 'success',
                        'admin_approve_return' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('details')
                    ->limit(50),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'kiosk_login' => 'Login',
                        'kiosk_borrow_request' => 'Borrow Request',
                        'kiosk_return_request' => 'Return Request',
                        'admin_export_users' => 'Export Users',
                        'admin_export_books' => 'Export Books',
                        'admin_approve_return' => 'Approve Return',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAuditLogs::route('/'),
        ];
    }
}
