<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2 text-danger-600">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6" />
                    <span>Konfigurasi Pemeliharaan Data</span>
                </div>
            </x-slot>

            <div class="space-y-6">
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    Pilih kategori data yang ingin Anda bersihkan secara permanen dari database.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Checkbox Borrows --}}
                    <label class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-200 dark:border-gray-800 cursor-pointer hover:border-primary-500 transition-all">
                        <x-filament::input.checkbox wire:model.live="resetBorrows" class="mt-1" />
                        <div>
                            <span class="block text-sm font-bold text-gray-900 dark:text-white">Hapus Data Peminjaman</span>
                            <span class="block text-[10px] text-gray-500 uppercase tracking-tight">Tabel: borrows</span>
                        </div>
                    </label>

                    {{-- Checkbox Books --}}
                    <label class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-200 dark:border-gray-800 cursor-pointer hover:border-primary-500 transition-all">
                        <x-filament::input.checkbox wire:model.live="resetBooks" class="mt-1" />
                        <div>
                            <span class="block text-sm font-bold text-gray-900 dark:text-white">Hapus Data Buku & Item</span>
                            <span class="block text-[10px] text-gray-500 uppercase tracking-tight">Tabel: books, book_items</span>
                        </div>
                    </label>

                    {{-- Checkbox Audit Logs --}}
                    <label class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-200 dark:border-gray-800 cursor-pointer hover:border-primary-500 transition-all">
                        <x-filament::input.checkbox wire:model.live="resetAuditLogs" class="mt-1" />
                        <div>
                            <span class="block text-sm font-bold text-gray-900 dark:text-white">Hapus Audit Logs</span>
                            <span class="block text-[10px] text-gray-500 uppercase tracking-tight">Tabel: audit_logs</span>
                        </div>
                    </label>

                    {{-- Checkbox Users --}}
                    <label class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-200 dark:border-gray-800 cursor-pointer hover:border-primary-500 transition-all">
                        <x-filament::input.checkbox wire:model.live="resetUsers" class="mt-1" />
                        <div>
                            <span class="block text-sm font-bold text-gray-900 dark:text-white">Hapus Data User</span>
                            <span class="block text-[10px] text-gray-500 uppercase tracking-tight text-danger-500">Kecuali 1 Admin terpilih</span>
                        </div>
                    </label>
                </div>

                {{-- Admin Selection (Only visible if resetUsers is active) --}}
                <div x-show="$wire.resetUsers" x-transition class="p-5 bg-danger-50 dark:bg-danger-500/5 rounded-2xl border border-danger-200 dark:border-danger-500/20 space-y-4 animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="flex items-center gap-2 text-danger-700 dark:text-danger-400">
                        <x-heroicon-s-shield-check class="w-5 h-5" />
                        <span class="text-sm font-bold uppercase tracking-tight">Proteksi Akun Admin</span>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Pilih Admin yang akan tetap dipertahankan untuk login:</label>
                        <x-filament::input.wrapper>
                            <select wire:model="keepAdminId" class="block w-full border-none bg-transparent py-1.5 text-gray-900 focus:ring-0 sm:text-sm sm:leading-6 dark:text-white">
                                <option value="">-- Pilih Admin --</option>
                                @foreach($this->admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                                @endforeach
                            </select>
                        </x-filament::input.wrapper>
                        <p class="text-[10px] text-danger-600 dark:text-danger-400 italic mt-1">* Seluruh user lain (User/Admin lainnya) akan dihapus secara permanen.</p>
                    </div>
                </div>

                <div class="pt-4 border-t dark:border-gray-800 flex justify-end">
                    <x-filament::button
                        color="danger"
                        icon="heroicon-o-trash"
                        wire:click="resetData"
                        wire:confirm="PERINGATAN! Tindakan ini akan menghapus data yang Anda pilih secara permanen. Apakah Anda benar-benar yakin?"
                        :disabled="!($resetUsers || $resetBooks || $resetBorrows || $resetAuditLogs)"
                    >
                        Jalankan Pemeliharaan Terpilih
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>

        <div class="p-4 bg-primary-50 dark:bg-primary-500/10 rounded-xl border border-primary-100 dark:border-primary-500/20 flex gap-3">
            <x-heroicon-o-information-circle class="w-5 h-5 text-primary-600 dark:text-primary-400 flex-shrink-0" />
            <div class="text-xs text-primary-700 dark:text-primary-400 leading-tight">
                <strong>Tips:</strong> Anda bisa memilih beberapa kategori sekaligus. Jika Anda memilih "Hapus Data User", sistem akan secara otomatis mewajibkan Anda memilih satu akun Admin untuk tetap bisa mengakses dashboard setelah pembersihan selesai.
            </div>
        </div>
    </div>
</x-filament-panels::page>
