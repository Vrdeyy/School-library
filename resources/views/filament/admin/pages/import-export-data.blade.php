<x-filament-panels::page>
    <div x-data="{ 
        activeTab: '{{ session('active_tab', 'users') }}',
        userFile: null,
        bookFile: null,
        userDragOver: false,
        bookDragOver: false,
        userSuccess: '{{ session('user_success') }}',
        userError: '{{ session('user_error') }}',
        bookSuccess: '{{ session('book_success') }}',
        bookError: '{{ session('book_error') }}'
    }">
        {{-- Tab Navigation --}}
        <div class="flex gap-2 mb-6 border-b border-gray-200 dark:border-gray-700">
            <button
                @click="activeTab = 'users'"
                :class="activeTab === 'users' 
                    ? 'border-b-2 border-primary-500 text-primary-600 dark:text-primary-400' 
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                class="px-4 py-2 font-medium transition-colors"
            >
                <x-heroicon-o-users class="w-5 h-5 inline mr-1" />
                Users
            </button>
            <button
                @click="activeTab = 'books'"
                :class="activeTab === 'books' 
                    ? 'border-b-2 border-primary-500 text-primary-600 dark:text-primary-400' 
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                class="px-4 py-2 font-medium transition-colors"
            >
                <x-heroicon-o-book-open class="w-5 h-5 inline mr-1" />
                Books
            </button>
            <button
                @click="activeTab = 'borrows'"
                :class="activeTab === 'borrows' 
                    ? 'border-b-2 border-primary-500 text-primary-600 dark:text-primary-400' 
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                class="px-4 py-2 font-medium transition-colors"
            >
                <x-heroicon-o-clipboard-document-list class="w-5 h-5 inline mr-1" />
                Borrows
            </button>
        </div>

        {{-- USERS TAB --}}
        <div x-show="activeTab === 'users'" x-cloak>
            {{-- User Alerts --}}
            <template x-if="userSuccess">
                <div class="mb-4 p-3 rounded-lg bg-success-50 dark:bg-success-500/10 border border-success-100 dark:border-success-500/20 flex items-center gap-2 animate-in slide-in-from-top-2 duration-300">
                    <x-heroicon-o-check-circle class="w-4 h-4 text-success-500" />
                    <span class="text-[11px] text-success-700 dark:text-success-400 font-bold uppercase tracking-wide" x-text="userSuccess"></span>
                </div>
            </template>
            <template x-if="userError">
                <div class="mb-4 p-3 rounded-lg bg-danger-50 dark:bg-danger-500/10 border border-danger-100 dark:border-danger-500/20 flex items-center gap-2 animate-in slide-in-from-top-2 duration-300">
                    <x-heroicon-o-exclamation-circle class="w-4 h-4 text-danger-500" />
                    <span class="text-[11px] text-danger-700 dark:text-danger-400 font-bold uppercase tracking-wide" x-text="userError"></span>
                </div>
            </template>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Download Section --}}
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-gray-50 dark:bg-white/5 rounded-lg">
                                <x-heroicon-m-arrow-down-tray class="w-4 h-4 text-gray-500" />
                            </div>
                            <span class="text-sm font-semibold">Resources</span>
                        </div>
                    </x-slot>
                    
                    <div class="space-y-2 mt-2">
                        <a href="{{ route('admin.data.users-template') }}" 
                           class="group flex items-center justify-between p-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-gray-800 rounded-xl hover:border-primary-500/50 transition-all shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 flex items-center justify-center text-gray-400 group-hover:bg-primary-50 dark:group-hover:bg-primary-500/10 group-hover:text-primary-500 transition-colors">
                                    <x-heroicon-o-document-duplicate class="w-4 h-4" />
                                </div>
                                <div class="text-left leading-tight">
                                    <p class="text-xs font-bold text-gray-900 dark:text-white">Empty Template</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-tighter">Standard Schema .XLSX</p>
                                </div>
                            </div>
                            <x-heroicon-m-chevron-right class="w-4 h-4 text-gray-300 group-hover:text-primary-500 transition-colors" />
                        </a>
                        
                        <a href="{{ route('admin.data.export-users') }}" 
                           class="group flex items-center justify-between p-3 bg-gray-900 dark:bg-primary-600 rounded-xl hover:bg-black dark:hover:bg-primary-500 transition-all shadow-md">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/70 group-hover:text-white transition-colors">
                                    <x-heroicon-o-table-cells class="w-4 h-4" />
                                </div>
                                <div class="text-left leading-tight">
                                    <p class="text-xs font-bold text-white">Full Dataset</p>
                                    <p class="text-[10px] text-white/60 uppercase tracking-tighter">Current Database State</p>
                                </div>
                            </div>
                            <x-heroicon-m-arrow-down-tray class="w-4 h-4 text-white/50 group-hover:text-white transition-colors" />
                        </a>
                    </div>
                </x-filament::section>

                {{-- Import Section with Drag & Drop --}}
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-primary-50 dark:bg-primary-500/10 rounded-lg">
                                <x-heroicon-m-arrow-up-tray class="w-4 h-4 text-primary-600 dark:text-primary-400" />
                            </div>
                            <span class="text-sm font-semibold">Import Users</span>
                        </div>
                    </x-slot>
                    
                    <form action="{{ route('admin.data.import-users') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div 
                            x-on:dragover.prevent="userDragOver = true"
                            x-on:dragleave.prevent="userDragOver = false"
                            x-on:drop.prevent="userDragOver = false; userFile = $event.dataTransfer.files[0]; $refs.userFileInput.files = $event.dataTransfer.files"
                            class="relative min-h-[160px] rounded-xl transition-all duration-300 group ring-1"
                            :class="userDragOver 
                                ? 'bg-primary-50/50 ring-primary-500 ring-2' 
                                : 'bg-gray-50/50 dark:bg-white/5 ring-gray-200 dark:ring-gray-800 hover:ring-primary-500/50 cursor-pointer'"
                            @click="$refs.userFileInput.click()"
                        >
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required x-ref="userFileInput" @change="userFile = $event.target.files[0]" class="hidden">

                            <div class="flex flex-col items-center justify-center p-6 text-center h-full space-y-3">
                                <template x-if="!userFile">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                            <x-heroicon-o-plus class="w-5 h-5 text-gray-400 group-hover:text-primary-500" />
                                        </div>
                                        <div class="space-y-0.5">
                                            <p class="text-xs font-semibold text-gray-900 dark:text-gray-100">Drop file to upload</p>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-tighter">or click to browse documents</p>
                                        </div>
                                    </div>
                                </template>
                                
                                <template x-if="userFile">
                                    <div class="w-full flex items-center justify-between bg-white dark:bg-gray-900 p-2.5 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm animate-in fade-in zoom-in duration-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-success-50 dark:bg-success-500/10 rounded overflow-hidden flex items-center justify-center">
                                                <x-heroicon-s-document-text class="w-5 h-5 text-success-600 dark:text-success-400" />
                                            </div>
                                            <div class="text-left leading-tight">
                                                <p class="text-[11px] font-bold text-gray-900 dark:text-white truncate max-w-[140px]" x-text="userFile.name"></p>
                                                <p class="text-[10px] text-gray-500 uppercase font-mono" x-text="(userFile.size / 1024).toFixed(1) + ' KB'"></p>
                                            </div>
                                        </div>
                                        <button @click.stop="userFile = null; $refs.userFileInput.value = ''" type="button" class="w-7 h-7 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-800 rounded text-gray-400 hover:text-red-500 transition-colors">
                                            <x-heroicon-m-trash class="w-4 h-4" />
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="userFile" x-transition class="pt-2">
                            <button type="submit" class="w-full py-2 bg-primary-600 dark:bg-primary-500 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-primary-500 transition-all active:scale-[0.98] uppercase tracking-wide">
                                Begin Data Integration
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-8">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="h-px bg-gray-100 dark:bg-gray-800 flex-1"></div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2">Data Schema</span>
                            <div class="h-px bg-gray-100 dark:bg-gray-800 flex-1"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Required</p>
                                <div class="flex flex-col gap-1.5">
                                    <template x-for="field in ['name', 'email', 'password']">
                                        <div class="flex items-center gap-2 text-[11px] font-mono text-primary-600 dark:text-primary-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500/20 flex items-center justify-center"><span class="w-0.5 h-0.5 rounded-full bg-primary-500"></span></span>
                                            <span x-text="field"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Properties</p>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="field in ['phone', 'role', 'id_pengenal_siswa', 'kelas', 'pin', 'limit']">
                                        <span class="text-[10px] font-mono text-gray-500 bg-gray-50 dark:bg-white/5 px-1.5 py-0.5 rounded-md border border-gray-200 dark:border-gray-800" x-text="field"></span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>
        </div>

        {{-- BOOKS TAB --}}
        <div x-show="activeTab === 'books'" x-cloak>
            {{-- Book Alerts --}}
            <template x-if="bookSuccess">
                <div class="mb-4 p-3 rounded-lg bg-success-50 dark:bg-success-500/10 border border-success-100 dark:border-success-500/20 flex items-center gap-2 animate-in slide-in-from-top-2 duration-300">
                    <x-heroicon-o-check-circle class="w-4 h-4 text-success-500" />
                    <span class="text-[11px] text-success-700 dark:text-success-400 font-bold uppercase tracking-wide" x-text="bookSuccess"></span>
                </div>
            </template>
            <template x-if="bookError">
                <div class="mb-4 p-3 rounded-lg bg-danger-50 dark:bg-danger-500/10 border border-danger-100 dark:border-danger-500/20 flex items-center gap-2 animate-in slide-in-from-top-2 duration-300">
                    <x-heroicon-o-exclamation-circle class="w-4 h-4 text-danger-500" />
                    <span class="text-[11px] text-danger-700 dark:text-danger-400 font-bold uppercase tracking-wide" x-text="bookError"></span>
                </div>
            </template>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Download Section --}}
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-gray-50 dark:bg-white/5 rounded-lg">
                                <x-heroicon-m-arrow-down-tray class="w-4 h-4 text-gray-500" />
                            </div>
                            <span class="text-sm font-semibold">Resources</span>
                        </div>
                    </x-slot>
                    
                    <div class="space-y-2 mt-2">
                        <a href="{{ route('admin.data.books-template') }}" 
                           class="group flex items-center justify-between p-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-gray-800 rounded-xl hover:border-primary-500/50 transition-all shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 flex items-center justify-center text-gray-400 group-hover:bg-primary-50 dark:group-hover:bg-primary-500/10 group-hover:text-primary-500 transition-colors">
                                    <x-heroicon-o-document-duplicate class="w-4 h-4" />
                                </div>
                                <div class="text-left leading-tight">
                                    <p class="text-xs font-bold text-gray-900 dark:text-white">Empty Template</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-tighter">Standard Schema .XLSX</p>
                                </div>
                            </div>
                            <x-heroicon-m-chevron-right class="w-4 h-4 text-gray-300 group-hover:text-primary-500 transition-colors" />
                        </a>
                        
                        <a href="{{ route('admin.data.export-books') }}" 
                           class="group flex items-center justify-between p-3 bg-gray-900 dark:bg-primary-600 rounded-xl hover:bg-black dark:hover:bg-primary-500 transition-all shadow-md">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/70 group-hover:text-white transition-colors">
                                    <x-heroicon-o-table-cells class="w-4 h-4" />
                                </div>
                                <div class="text-left leading-tight">
                                    <p class="text-xs font-bold text-white">Full Dataset</p>
                                    <p class="text-[10px] text-white/60 uppercase tracking-tighter">Current Inventory State</p>
                                </div>
                            </div>
                            <x-heroicon-m-arrow-down-tray class="w-4 h-4 text-white/50 group-hover:text-white transition-colors" />
                        </a>
                    </div>
                </x-filament::section>

                {{-- Import Section with Drag & Drop --}}
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-primary-50 dark:bg-primary-500/10 rounded-lg">
                                <x-heroicon-m-arrow-up-tray class="w-4 h-4 text-primary-600 dark:text-primary-400" />
                            </div>
                            <span class="text-sm font-semibold">Inventory Load</span>
                        </div>
                    </x-slot>
                    
                    <form action="{{ route('admin.data.import-books') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div 
                            x-on:dragover.prevent="bookDragOver = true"
                            x-on:dragleave.prevent="bookDragOver = false"
                            x-on:drop.prevent="bookDragOver = false; bookFile = $event.dataTransfer.files[0]; $refs.bookFileInput.files = $event.dataTransfer.files"
                            class="relative min-h-[160px] rounded-xl transition-all duration-300 group ring-1"
                            :class="bookDragOver 
                                ? 'bg-primary-50/50 ring-primary-500 ring-2' 
                                : 'bg-gray-50/50 dark:bg-white/5 ring-gray-200 dark:ring-gray-800 hover:ring-primary-500/50 cursor-pointer'"
                            @click="$refs.bookFileInput.click()"
                        >
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required x-ref="bookFileInput" @change="bookFile = $event.target.files[0]" class="hidden">

                            <div class="flex flex-col items-center justify-center p-6 text-center h-full space-y-3">
                                <template x-if="!bookFile">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                            <x-heroicon-o-arrow-up-on-square class="w-5 h-5 text-gray-400 group-hover:text-primary-500" />
                                        </div>
                                        <div class="space-y-0.5">
                                            <p class="text-xs font-semibold text-gray-900 dark:text-gray-100">Upload Inventory</p>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-tighter">Select Excel or CSV source</p>
                                        </div>
                                    </div>
                                </template>
                                
                                <template x-if="bookFile">
                                    <div class="w-full flex items-center justify-between bg-white dark:bg-gray-900 p-2.5 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm animate-in fade-in duration-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-primary-50 dark:bg-primary-500/10 rounded overflow-hidden flex items-center justify-center">
                                                <x-heroicon-s-book-open class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                                            </div>
                                            <div class="text-left leading-tight">
                                                <p class="text-[11px] font-bold text-gray-900 dark:text-white truncate max-w-[140px]" x-text="bookFile.name"></p>
                                                <p class="text-[10px] text-gray-500 uppercase font-mono" x-text="(bookFile.size / 1024).toFixed(1) + ' KB'"></p>
                                            </div>
                                        </div>
                                        <button @click.stop="bookFile = null; $refs.bookFileInput.value = ''" type="button" class="w-7 h-7 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-800 rounded text-gray-400 hover:text-red-500 transition-colors">
                                            <x-heroicon-m-trash class="w-4 h-4" />
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="bookFile" x-transition class="pt-2">
                            <button type="submit" class="w-full py-2 bg-primary-600 dark:bg-primary-500 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-primary-500 transition-all active:scale-[0.98] uppercase tracking-wide">
                                Deploy Batch Import
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-8">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="h-px bg-gray-100 dark:bg-gray-800 flex-1"></div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2">Data Schema</span>
                            <div class="h-px bg-gray-100 dark:bg-gray-800 flex-1"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Required</p>
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2 text-[11px] font-mono text-primary-600 dark:text-primary-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500/20 flex items-center justify-center"><span class="w-0.5 h-0.5 rounded-full bg-primary-500"></span></span>
                                        <span>title</span>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Attributes</p>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="field in ['author', 'publisher', 'year', 'isbn', 'stock']">
                                        <span class="text-[10px] font-mono text-gray-500 bg-gray-50 dark:bg-white/5 px-1.5 py-0.5 rounded-md border border-gray-200 dark:border-gray-800" x-text="field"></span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::section>
            </div>
        </div>

        {{-- BORROWS TAB --}}
        <div x-show="activeTab === 'borrows'" x-cloak>
            <x-filament::section>
                <x-slot name="heading">
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 inline mr-2" />
                    Export Borrow Data
                </x-slot>
                
                <form action="{{ route('admin.data.export-borrows') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Month
                            </label>
                            <select name="month" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Year
                            </label>
                            <select name="year" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                @foreach(range(now()->year, now()->year - 5) as $y)
                                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        <x-heroicon-o-table-cells class="w-5 h-5" />
                        Export Borrow Data
                    </button>
                </form>
                
                <div class="mt-4 p-3 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-sm text-amber-700 dark:text-amber-400">
                    <x-heroicon-o-information-circle class="w-5 h-5 inline mr-1" />
                    Borrow data is transaction-based and cannot be imported. Use this feature to export records for reporting purposes.
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
