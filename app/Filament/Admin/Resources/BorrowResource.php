<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BorrowResource\Pages;
use App\Models\Borrow;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\BorrowsExport;
use Maatwebsite\Excel\Facades\Excel;

class BorrowResource extends Resource
{
    protected static ?string $model = Borrow::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('book_item_id')
                    ->relationship('bookItem', 'code')
                    ->required(),
                Forms\Components\DateTimePicker::make('borrow_date'),
                Forms\Components\DateTimePicker::make('due_date'),
                Forms\Components\DateTimePicker::make('return_date'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'returning' => 'Returning (Kiosk)',
                        'returned' => 'Returned',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bookItem.book.title')
                    ->label('Book Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bookItem.code')
                    ->label('Item Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('borrow_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->dateTime()
                    ->sortable()
                    ->color(fn (Borrow $record) => $record->is_overdue ? 'danger' : null),
                Tables\Columns\TextColumn::make('return_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'returning' => 'primary',
                        'returned' => 'info',
                        'rejected' => 'danger',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'returning' => 'Returning',
                        'returned' => 'Returned',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->form([
                        Forms\Components\Select::make('month')
                            ->options([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ])
                            ->default(now()->month)
                            ->required(),
                        Forms\Components\TextInput::make('year')
                            ->numeric()
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Audit log export
                        \App\Models\AuditLog::create([
                            'user_id' => auth()->id(),
                            'action' => 'admin_export_report',
                            'details' => "Export laporan bulan {$data['month']}/{$data['year']}",
                            'ip_address' => request()->ip(),
                            'user_agent' => request()->userAgent(),
                        ]);
                        
                        $filename = "laporan-peminjaman-{$data['year']}-" . str_pad($data['month'], 2, '0', STR_PAD_LEFT) . ".xlsx";
                        return Excel::download(new BorrowsExport((int)$data['month'], (int)$data['year']), $filename);
                    }),
                    
                Tables\Actions\Action::make('print')
                    ->label('Cetak Laporan')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->form([
                        Forms\Components\Select::make('month')
                            ->options([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ])
                            ->default(now()->month)
                            ->required(),
                        Forms\Components\TextInput::make('year')
                            ->numeric()
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $url = route('admin.reports.borrows.print', [
                            'month' => $data['month'],
                            'year' => $data['year'],
                        ]);
                        
                        return redirect()->away($url);
                    })
                    ->openUrlInNewTab(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Borrow $record) {
                        $record->update([
                            'status' => 'approved',
                            'borrow_date' => now(),
                            'due_date' => now()->addDays(7),
                            'approved_by' => auth()->id(),
                        ]);
                        $record->bookItem()->update(['status' => 'borrowed']);
                    })
                    ->visible(fn (Borrow $record) => $record->status === 'pending'),
                
                Tables\Actions\Action::make('approve_return')
                    ->label('Approve Return')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function (Borrow $record) {
                        $record->update([
                            'status' => 'returned',
                            'return_date' => now(),
                            'approved_by' => auth()->id(),
                        ]);
                        $record->bookItem()->update(['status' => 'available']);
                    })
                    ->visible(fn (Borrow $record) => $record->status === 'returning'),

                Tables\Actions\Action::make('return')
                    ->label('Manual Return')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(function (Borrow $record) {
                        $record->update([
                            'status' => 'returned',
                            'return_date' => now(),
                        ]);
                        $record->bookItem()->update(['status' => 'available']);
                    })
                    ->visible(fn (Borrow $record) => $record->status === 'approved'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBorrows::route('/'),
            'create' => Pages\CreateBorrow::route('/create'),
            'edit' => Pages\EditBorrow::route('/{record}/edit'),
        ];
    }
}
