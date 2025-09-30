<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class CreateDailyDataTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:create-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->format('Y_m_d');
        $tableName = "daily_data_$today";

        DB::statement("
        CREATE TABLE IF NOT EXISTS $tableName (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT,
            activity VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL
        )
    ");

    $this->info("Table $tableName created successfully!");
    }
}