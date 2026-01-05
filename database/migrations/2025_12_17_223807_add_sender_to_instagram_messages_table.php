<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('instagram_messages', function (Blueprint $table) {
            $table->string('sender')->after('instagram_conversation_id');
        });
    }

    public function down(): void
    {
        Schema::table('instagram_messages', function (Blueprint $table) {
            $table->dropColumn('sender');
        });
    }
};
