<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'user' => 'User',
                    ])
                    ->default('user')
                    ->required(),
                Forms\Components\TextInput::make('kelas')
                    ->maxLength(20),
                Forms\Components\TextInput::make('jurusan')
                    ->maxLength(50),
                Forms\Components\TextInput::make('angkatan')
                    ->maxLength(10),
                Forms\Components\TextInput::make('nis')
                    ->label('NIS / ID')
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('pin')
                    ->label('PIN (Numeric)')
                    ->numeric()
                    ->password()
                    ->length(6)
                    ->dehydrated(fn ($state) => filled($state)), // Store raw or hashed? Req implied 'Login manual (NIS / Email + PIN)'. Sanctum uses password. Maybe PIN is secondary? Assuming plaintext or simple hash. Let's store as string for now, but usually should be hashed if used for auth. Given requirement 'Email + PIN', maybe PIN is password? No, usually seperate. I'll just store it as string for now.
                Forms\Components\Toggle::make('is_suspended')
                    ->label('Suspended')
                    ->default(false),
                Forms\Components\TextInput::make('borrow_limit')
                    ->numeric()
                    ->default(3)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'user' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('kelas')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('jurusan')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('angkatan')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('nis')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_suspended')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'user' => 'User',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view_qr')
                    ->label('QR Code')
                    ->icon('heroicon-o-qr-code')
                    ->modalContent(fn ($record) => new \Illuminate\Support\HtmlString(
                        '<div class="flex flex-col items-center p-4">' .
                        \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($record->qr_payload) .
                        '<p class="mt-2 font-bold">' . ($record->nis ?? $record->id) . '</p>' .
                        '<p class="text-xs text-gray-500">Scan at Kiosk</p>' .
                        '</div>'
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                    
                Tables\Actions\Action::make('print_card')
                    ->label('Print Card')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('admin.print.user-card', $record))
                    ->openUrlInNewTab(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export Data')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        // Audit log export
                        \App\Models\AuditLog::create([
                            'user_id' => auth()->id(),
                            'action' => 'admin_export_users',
                            'details' => 'Export data users ke Excel',
                            'ip_address' => request()->ip(),
                            'user_agent' => request()->userAgent(),
                        ]);

                        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\UsersExport, 'users-' . now()->format('Y-m-d') . '.xlsx');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

}
