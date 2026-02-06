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
        DB::statement("ALTER TABLE book_items MODIFY COLUMN status ENUM('available', 'borrowed', 'lost', 'maintenance', 'returning') NOT NULL DEFAULT 'available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE book_items MODIFY COLUMN status ENUM('available', 'borrowed', 'lost', 'maintenance') NOT NULL DEFAULT 'available'");
    }
};
