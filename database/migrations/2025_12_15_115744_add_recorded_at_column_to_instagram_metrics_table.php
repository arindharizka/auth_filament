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
        Schema::table('instagram_metrics', function (Blueprint $table) {
            // Tambah kolom recorded_at kalau belum ada
            if (!Schema::hasColumn('instagram_metrics', 'recorded_at')) {
                $table->timestamp('recorded_at')->after('impressions');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instagram_metrics', function (Blueprint $table) {
            // Hapus kolom recorded_at kalau ada
            if (Schema::hasColumn('instagram_metrics', 'recorded_at')) {
                $table->dropColumn('recorded_at');
            }
        });
    }
};
