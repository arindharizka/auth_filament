<?php

namespace App\Filament\Resources\InstagramConversationResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';
    protected static ?string $title = 'Messages';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender_username')
                    ->label('Sender')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('message_text')
                    ->label('Message')
                    ->limit(80)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('sent_at')
                    ->label('Sent At')
                    ->dateTime('d-M-Y H:i')
                    ->sortable(),
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
