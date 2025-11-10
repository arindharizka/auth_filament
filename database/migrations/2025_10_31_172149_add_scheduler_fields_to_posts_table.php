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
        Schema::table('posts', function (Blueprint $table) {
            // ✅ Cek dulu apakah kolom belum ada sebelum menambahkannya
            if (!Schema::hasColumn('posts', 'status')) {
                $table->enum('status', ['scheduled', 'posted', 'draft', 'cancelled'])
                      ->default('scheduled');
            }

            if (!Schema::hasColumn('posts', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable();
            }

            if (!Schema::hasColumn('posts', 'account_id')) {
                $table->foreignId('account_id')
                      ->nullable()
                      ->constrained('accounts')
                      ->nullOnDelete();
            }

            if (!Schema::hasColumn('posts', 'media')) {
                $table->string('media')->nullable(); // jika pakai FileUpload
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // ✅ Hapus kolom jika migration di-rollback
            if (Schema::hasColumn('posts', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('posts', 'scheduled_at')) {
                $table->dropColumn('scheduled_at');
            }

            if (Schema::hasColumn('posts', 'account_id')) {
                $table->dropForeign(['account_id']);
                $table->dropColumn('account_id');
            }

            if (Schema::hasColumn('posts', 'media')) {
                $table->dropColumn('media');
            }
        });
    }
};
