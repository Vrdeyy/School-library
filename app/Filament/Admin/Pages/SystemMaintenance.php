<?php

namespace App\Filament\Admin\Pages;

use App\Models\AuditLog;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\Borrow;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class SystemMaintenance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationLabel = 'Pemeliharaan Sistem';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 10;
    protected static ?string $title = 'Pemeliharaan Sistem';

    protected static string $view = 'filament.admin.pages.system-maintenance';

    public $resetUsers = false;
    public $resetBooks = false;
    public $resetBorrows = false;
    public $resetAuditLogs = false;
    public $keepAdminId = null;

    public function mount()
    {
        $this->keepAdminId = auth()->id();
    }

    public function getAdminsProperty()
    {
        return User::where('role', 'admin')->get();
    }

    public function resetData()
    {
        // Validasi jika user atau semuanya dipilih tapi admin ke-reset
        if (($this->resetUsers) && !$this->keepAdminId) {
            Notification::make()->danger()->title('Error')->body('Pilih admin yang ingin dipertahankan!')->send();
            return;
        }

        if (!$this->resetUsers && !$this->resetBooks && !$this->resetBorrows && !$this->resetAuditLogs) {
            Notification::make()->warning()->title('Peringatan')->body('Pilih minimal satu kategori data untuk di-reset!')->send();
            return;
        }

        try {
            DB::beginTransaction();
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $messages = [];

            if ($this->resetBorrows) {
                DB::table('borrows')->delete();
                $messages[] = 'Riwayat peminjaman dihapus';
            }

            if ($this->resetBooks) {
                DB::table('book_items')->delete();
                DB::table('books')->delete();
                $messages[] = 'Data buku & item dihapus';
            }

            if ($this->resetAuditLogs) {
                DB::table('audit_logs')->delete();
                $messages[] = 'Audit logs dihapus';
            }

            if ($this->resetUsers) {
                DB::table('users')->where('id', '!=', $this->keepAdminId)->delete();
                $messages[] = 'Data user dihapus (Kecuali admin terpilih)';
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();

            // Jalankan ALTER TABLE diluar transaksi untuk menghindari Error "No Active Transaction"
            // Karena ALTER TABLE di MySQL memicu implicit commit.
            if ($this->resetBorrows) {
                DB::statement('ALTER TABLE borrows AUTO_INCREMENT = 1;');
            }
            if ($this->resetBooks) {
                DB::statement('ALTER TABLE book_items AUTO_INCREMENT = 1;');
                DB::statement('ALTER TABLE books AUTO_INCREMENT = 1;');
            }
            if ($this->resetAuditLogs) {
                DB::statement('ALTER TABLE audit_logs AUTO_INCREMENT = 1;');
            }

            Notification::make()
                ->success()
                ->title('Maintenance Berhasil')
                ->body(implode(', ', $messages) . '.')
                ->send();

            // Reset checkboxes
            $this->resetUsers = false;
            $this->resetBooks = false;
            $this->resetBorrows = false;
            $this->resetAuditLogs = false;

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            Notification::make()
                ->danger()
                ->title('Gagal Reset')
                ->body($e->getMessage())
                ->send();
        }
    }
}
