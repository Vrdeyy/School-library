<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user'); // admin, user
            $table->string('nis')->nullable()->unique();
            $table->string('pin')->nullable();
            $table->boolean('is_suspended')->default(false);
            $table->integer('borrow_limit')->default(3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nis', 'pin', 'is_suspended', 'borrow_limit']);
        });
    }
};
