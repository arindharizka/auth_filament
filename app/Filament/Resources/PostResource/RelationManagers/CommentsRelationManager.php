<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use App\Models\InstagramComment;
use App\Services\InstagramService;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification; // ✅ tambahkan ini

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
    protected static ?string $recordTitleAttribute = 'comment_text';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('commenter_username')
                    ->label('Commenter')
                    ->searchable(),
                Tables\Columns\TextColumn::make('comment_text')
                    ->label('Comment')
                    ->wrap()
                    ->limit(80),
                Tables\Columns\TextColumn::make('reply_text')
                    ->label('Reply')
                    ->wrap()
                    ->limit(80),
                Tables\Columns\TextColumn::make('commented_at')
                    ->label('Commented At')
                    ->dateTime('d-M-Y H:i'),
                Tables\Columns\TextColumn::make('replied_at')
                    ->label('Replied At')
                    ->dateTime('d-M-Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('fetch_comments')
                    ->label('Fetch Comments')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->action(function (): void {
                        $post = $this->getOwnerRecord();

                        $comments = [
                            ['user' => 'user_a', 'comment' => 'Nice post!'],
                            ['user' => 'user_b', 'comment' => 'Great content!'],
                        ];

                        foreach ($comments as $data) {
                            InstagramComment::create([
                                'post_id' => $post->id,
                                'commenter_username' => $data['user'],
                                'comment_text' => $data['comment'],
                                'commented_at' => now(),
                            ]);
                        }

                        Notification::make()
                            ->title('Berhasil!')
                            ->body('Dummy comments successfully fetched.')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('reply')
                    ->label('Reply')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->form([
                        Forms\Components\Textarea::make('reply_text')
                            ->label('Reply')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (array $data, Model $record): void {
                        $record->update([
                            'reply_text' => $data['reply_text'],
                            'replied_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Reply Saved')
                            ->body('Reply has been successfully saved.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
