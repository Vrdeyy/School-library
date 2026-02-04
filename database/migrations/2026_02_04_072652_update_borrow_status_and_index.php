<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update Enum to include 'returning'
        DB::statement("ALTER TABLE borrows MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'returned', 'returning') NOT NULL DEFAULT 'pending'");

        // 2. Add a non-unique index to support the foreign key before dropping the unique one
        Schema::table('borrows', function (Blueprint $table) {
            $table->index('book_item_id', 'borrows_book_item_id_index');
        });

        // 3. Drop old unique index
        Schema::table('borrows', function (Blueprint $table) {
            $table->dropUnique('unique_active_borrow');
        });

        // 4. Add Better Functional Unique Index (MySQL 8+)
        DB::statement("CREATE UNIQUE INDEX unique_active_borrow ON borrows (book_item_id, (IF(status IN ('pending', 'approved', 'returning'), 'active', NULL)))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP INDEX unique_active_borrow ON borrows");
        
        Schema::table('borrows', function (Blueprint $table) {
            $table->unique(['book_item_id', 'status'], 'unique_active_borrow');
            $table->dropIndex('borrows_book_item_id_index');
        });

        DB::statement("ALTER TABLE borrows MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'returned') NOT NULL DEFAULT 'pending'");
    }
};
