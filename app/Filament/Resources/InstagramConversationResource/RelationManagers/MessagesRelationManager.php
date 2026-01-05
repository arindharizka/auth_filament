<?php

namespace App\Filament\Resources\InstagramConversationResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';
    protected static ?string $title = 'Instagram DM';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sender')
                    ->badge()
                    ->color(fn ($state) => $state === 'admin' ? 'success' : 'gray')
                    ->label('From'),

                Tables\Columns\TextColumn::make('message_text') // ✅ FIX
                    ->wrap()
                    ->label('Message'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->since(),
            ])
            ->defaultSort('created_at', 'asc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Reply DM')
                    ->form([
                        Forms\Components\Textarea::make('message_text') // ✅ FIX
                            ->label('Reply Message')
                            ->rows(3)
                            ->required(),

                        Forms\Components\Hidden::make('sender')
                            ->default('admin'),

                        Forms\Components\Hidden::make('sender_username')
                            ->default('admin'),
                    ])
                    ->after(function ($record, $livewire) {
                        $conversation = $livewire->ownerRecord;

                        $conversation->update([
                            'last_message' => $record->message_text, // ✅ FIX
                            'last_activity_at' => now(),
                        ]);
                    }),
            ])
            ->actions([]);
    }
}
