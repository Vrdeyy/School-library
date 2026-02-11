<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BookResource\Pages;
use App\Filament\Admin\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('author')
                    ->maxLength(255),
                Forms\Components\TextInput::make('publisher')
                    ->maxLength(255),
                Forms\Components\TextInput::make('year')
                    ->numeric(),
                Forms\Components\TextInput::make('isbn')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('cover_image')
                    ->image()
                    ->disk('public')
                    ->directory('book-covers')
                    ->imageEditor()
                    ->maxSize(1024),
                Forms\Components\TextInput::make('initial_stock')
                    ->label('Jumlah Eksemplar')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->maxValue(100)
                    ->helperText('Kode buku akan di-generate otomatis')
                    ->visibleOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->searchable(),
                Tables\Columns\TextColumn::make('available_stock')
                    ->label('Stock')
                    ->getStateUsing(fn (Book $record) => $record->available_stock . ' / ' . $record->items()->count()),
                Tables\Columns\TextColumn::make('year'),
                Tables\Columns\TextColumn::make('isbn')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('print_label')
                    ->label('Print Label')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->modalHeading('Pilih Eksemplar untuk Dicetak')
                    ->modalContent(function (Book $record) {
                        return view('filament.admin.resources.book-resource.print-modal', ['book' => $record]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export Data')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        // Audit log export
                        \App\Models\AuditLog::create([
                            'user_id' => auth()->id(),
                            'action' => 'admin_export_books',
                            'details' => 'Export data buku & items ke Excel',
                            'ip_address' => request()->ip(),
                            'user_agent' => request()->userAgent(),
                        ]);

                        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BooksExport, 'books-' . now()->format('Y-m-d') . '.xlsx');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
