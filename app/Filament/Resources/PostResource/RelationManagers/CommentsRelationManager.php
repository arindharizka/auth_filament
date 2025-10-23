<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use App\Models\InstagramComment;
use App\Services\InstagramService;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
    protected static ?string $recordTitleAttribute = 'comment_text';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('commenter_username')->label('Commenter')->searchable(),
                Tables\Columns\TextColumn::make('comment_text')->label('Comment')->wrap()->limit(80),
                Tables\Columns\TextColumn::make('reply_text')->label('Reply')->limit(80)->wrap(),
                Tables\Columns\TextColumn::make('commented_at')->label('Commented At')->dateTime('d-M-Y H:i'),
                Tables\Columns\TextColumn::make('replied_at')->label('Replied At')->dateTime('d-M-Y H:i')->sortable(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('fetch_comments')
                    ->label('Fetch Comments')
                    ->action(function () {
                        // owner record = parent Post model
                        $post = $this->getOwnerRecord();
                        $service = new InstagramService();
                        $service->fetchComments($post);

                        $this->notify('success', 'Comments fetched (dummy).');
                        // refresh table
                        $this->redirect($this->getResource()::getUrl('index'));
                    })
                    ->icon('heroicon-o-refresh'),
            ])
            ->actions([
                Tables\Actions\Action::make('reply')
                    ->label('Reply')
                    ->form([
                        Forms\Components\Textarea::make('reply_text')
                            ->label('Reply')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (array $data, Model $record) {
                        $record->update([
                            'reply_text' => $data['reply_text'],
                            'replied_at' => now(),
                        ]);
                        $this->notify('success', 'Reply saved.');
                    })
                    ->modalWidth('lg')
                    ->icon('heroicon-o-chat-bubble-left-right'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
