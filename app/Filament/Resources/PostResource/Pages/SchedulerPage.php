<?php

namespace App\Filament\Pages;

use App\Models\Post;
use App\Services\InstagramService;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class SchedulerPage extends Page implements HasTable
{
    protected static ?string $navigationLabel = 'Scheduler';
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Social Media';
    protected static string $view = 'filament.pages.scheduler-page';

    // implement Table
    use Tables\Concerns\InteractsWithTable;

    protected function getTableQuery()
    {
        return Post::query()
            ->where('status', 'scheduled')
            ->orderBy('scheduled_at', 'asc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('account.username')->label('Account')->sortable(),
            Tables\Columns\TextColumn::make('platform')->sortable(),
            Tables\Columns\TextColumn::make('caption')->limit(50),
            Tables\Columns\TextColumn::make('scheduled_at')->dateTime('d-M-Y H:i')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime('d-M-Y H:i'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('run_now')
                ->label('Run now')
                ->requiresConfirmation()
                ->action(function (Post $record) {
                    // jalankan logic publish dummy (atau panggil service nyata)
                    $service = app(InstagramService::class);
                    $result = $service->publish($record);

                    // ubah status jadi posted jika sukses
                    if (!empty($result['success'])) {
                        $record->update(['status' => 'posted']);
                        $this->notify('success', "Post {$record->id} posted (dummy).");
                    } else {
                        $this->notify('danger', "Gagal posting: {$result['message']}");
                    }
                })
                ->color('success'),

            Action::make('cancel')
                ->label('Cancel')
                ->requiresConfirmation()
                ->action(function (Post $record) {
                    $record->update(['status' => 'cancelled']);
                    $this->notify('success', "Post {$record->id} schedule cancelled.");
                })
                ->color('danger'),

            Tables\Actions\EditAction::make()->url(fn (Post $record) => route('filament.resources.posts.edit', $record)),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkAction::make('run_selected')
                ->label('Run selected')
                ->action(function (array $records) {
                    $service = app(InstagramService::class);
                    foreach ($records as $record) {
                        /** @var Post $post */
                        $post = Post::find($record['id']);
                        $res = $service->publish($post);
                        if (!empty($res['success'])) {
                            $post->update(['status' => 'posted']);
                        }
                    }
                    $this->notify('success', 'Selected posts processed.');
                }),
            Tables\Actions\BulkAction::make('cancel_selected')
                ->label('Cancel selected')
                ->action(function (array $records) {
                    foreach ($records as $r) {
                        Post::where('id', $r['id'])->update(['status' => 'cancelled']);
                    }
                    $this->notify('success', 'Selected schedules cancelled.');
                }),
        ];
    }
}
