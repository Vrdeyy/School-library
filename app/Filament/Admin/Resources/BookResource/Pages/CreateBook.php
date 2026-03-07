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
            $item = $this->record->items()->create([
                'code' => \App\Models\BookItem::generateCode($this->record->id, $i),
                'status' => 'available',
            ]);
            
            $item->generateQrSignature();
        }
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}