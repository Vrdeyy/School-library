<?php

namespace App\Filament\Admin\Components;

use App\Models\BookItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BookItemPrintTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(BookItem::with('book')->whereHas('book'))
            ->columns([
                Tables\Columns\TextColumn::make('book.title')->label('Judul Buku')->searchable()->limit(30),
                Tables\Columns\TextColumn::make('code')->label('Kode Item')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'borrowed' => 'warning',
                        'lost' => 'danger',
                        'maintenance' => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'borrowed' => 'Borrowed',
                        'lost' => 'Lost',
                        'maintenance' => 'Maintenance',
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('print_selected')
                    ->label('Cetak Massal (Label)')
                    ->icon('heroicon-o-printer')
                    ->action(function (Collection $records) {
                        return redirect()->route('admin.print.bulk-books', ['ids' => $records->pluck('id')->toArray()]);
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }
}
