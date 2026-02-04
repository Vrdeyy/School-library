<div class="space-y-4">
    @if($book->items->count() > 0)
        <div class="grid grid-cols-1 gap-4">
            @foreach($book->items as $item)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div>
                        <div class="font-bold text-sm">{{ $item->code }}</div>
                        <div class="text-xs text-gray-500 {{ $item->status === 'available' ? 'text-green-600' : 'text-amber-600' }}">
                            {{ ucfirst($item->status) }}
                        </div>
                    </div>
                    <a href="{{ route('admin.print.book-label', $item) }}" target="_blank" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors focus:ring-4 focus:ring-primary-500/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-6 text-gray-500">
            <p>Belum ada eksemplar untuk buku ini.</p>
            <p class="text-sm mt-1">Tambahkan eksemplar di menu Edit terlebih dahulu.</p>
        </div>
    @endif
</div>
