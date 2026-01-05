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
    Schema::table('instagram_messages', function (Blueprint $table) {
        if (Schema::hasColumn('instagram_messages', 'message')) {
            $table->dropColumn('message');
        }
    });
}

public function down(): void
{
    Schema::table('instagram_messages', function (Blueprint $table) {
        $table->text('message')->nullable();
    });
}

};
