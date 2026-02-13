<?php

namespace App\Filament\Admin\Components;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Collection;

class UserPrintTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('kelas')->sortable(),
                Tables\Columns\TextColumn::make('jurusan')->sortable(),
                Tables\Columns\TextColumn::make('role')->badge(),
                Tables\Columns\TextColumn::make('id_pengenal_siswa')
                    ->label('Id Pengenal Siswa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->date()->label('Joined'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options(['admin' => 'Admin', 'user' => 'User']),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('print_selected')
                    ->label('Cetak Massal (ID Card)')
                    ->icon('heroicon-o-printer')
                    ->action(function (Collection $records) {
                        return redirect()->route('admin.print.bulk-users', ['ids' => $records->pluck('id')->toArray()]);
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }
}
