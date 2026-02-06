<?php

namespace App\Filament\Admin\Resources\BookResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\BookItem;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\HtmlString;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('status')
                    ->options([
                        'available' => 'Available',
                        'borrowed' => 'Borrowed',
                        'returning' => 'Returning (Kiosk)',
                        'lost' => 'Lost',
                        'maintenance' => 'Maintenance',
                    ])
                    ->default('available')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'borrowed' => 'warning',
                        'returning' => 'primary',
                        'lost' => 'danger',
                        'maintenance' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('qr_signature')
                    ->label('Signature')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view_qr')
                    ->label('QR Code')
                    ->icon('heroicon-o-qr-code')
                    ->modalContent(fn (BookItem $record) => new HtmlString(
                        '<div class="flex justify-center p-4">'.
                        QrCode::size(200)->generate($record->qr_payload).
                        '</div><div class="text-center font-bold mt-2">'. $record->code .'</div>'
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                Tables\Actions\Action::make('print_label')
                    ->label('Print Label')
                    ->icon('heroicon-o-printer')
                    ->url(fn (BookItem $record) => route('admin.print.book-label', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
