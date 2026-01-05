<?php

namespace App\Filament\Pages;

use App\Models\Post;
use Filament\Pages\Page;

class InstagramCalendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Instagram Calendar';
    protected static ?string $navigationGroup = 'Social Media';

    protected static string $view = 'filament.pages.instagram-calendar';

    /**
     * Data post Instagram terjadwal
     * Dipakai di Blade (Calendar UI)
     */
    public function getPosts(): array
    {
        return Post::where('platform', 'instagram')
            ->whereNotNull('scheduled_at')
            ->get()
            ->map(function ($post) {
                return [
                    'title' => str($post->caption)->limit(20),
                    'start' => $post->scheduled_at,
                    'color' => $post->status === 'posted'
                        ? '#16a34a'   // hijau = posted
                        : '#f59e0b',  // kuning = scheduled
                ];
            })
            ->toArray();
    }
}
