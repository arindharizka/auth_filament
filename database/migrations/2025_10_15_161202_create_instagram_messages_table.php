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
        Schema::create('instagram_messages', function (Blueprint $table) {
            $table->id();

            // Ubah bagian ini: gunakan foreign key ke tabel instagram_conversations
            $table->foreignId('instagram_conversation_id')
                ->constrained('instagram_conversations')
                ->cascadeOnDelete();

            // Kolom isi pesan
            $table->string('sender_username');
            $table->text('message_text');
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_messages');
    }
};
