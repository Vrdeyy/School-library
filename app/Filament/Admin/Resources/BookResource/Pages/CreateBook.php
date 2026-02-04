<?php

namespace App\Filament\Admin\Resources\BookResource\Pages;

use App\Filament\Admin\Resources\BookResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;

    protected function afterCreate(): void
    {
        $quantity = $this->data['initial_stock'] ?? 1;
        
        for ($i = 1; $i <= $quantity; $i++) {
            $code = $this->generateUniqueCode($this->record->id, $i);
            
            $item = $this->record->items()->create([
                'code' => $code,
                'status' => 'available',
            ]);
            
            $item->generateQrSignature();
        }
    }

    private function generateUniqueCode(int $bookId, int $sequence): string
    {
        $timestamp = now()->format('ymdHis');
        $random = strtoupper(Str::random(4));
        
        return "{$bookId}-{$timestamp}-{$random}{$sequence}";
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}