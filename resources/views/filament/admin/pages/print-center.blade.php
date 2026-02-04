<x-filament-panels::page>
    <div class="space-y-6">
        <p class="text-gray-500">Pilih data dari tabel di bawah, lalu klik tombol "Bulk Actions" -> "Cetak Massal" untuk mencetak banyak QR sekaligus.</p>
        
        <x-filament::section collapsible collapsed>
            <x-slot name="heading">
                Cetak Kartu Anggota
            </x-slot>
            @livewire(\App\Filament\Admin\Components\UserPrintTable::class)
        </x-filament::section>

        <x-filament::section collapsible collapsed>
            <x-slot name="heading">
                Cetak Label Buku
            </x-slot>
            @livewire(\App\Filament\Admin\Components\BookItemPrintTable::class)
        </x-filament::section>
    </div>
</x-filament-panels::page>
