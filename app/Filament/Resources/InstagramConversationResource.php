<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstagramConversationResource\Pages;
use App\Filament\Resources\InstagramConversationResource\RelationManagers\MessagesRelationManager;
use App\Models\InstagramConversation;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InstagramConversationResource extends Resource
{
    protected static ?string $model = InstagramConversation::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Instagram Inbox';
    protected static ?string $navigationGroup = 'Social Media';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('participant_username')
                    ->label('Username')
                    ->searchable(),

                Tables\Columns\TextColumn::make('last_message')
                    ->label('Last Message')
                    ->limit(40),

                Tables\Columns\TextColumn::make('last_activity_at')
                    ->label('Last Active')
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // 👈 tombol View
            ])
            ->defaultSort('last_activity_at', 'desc');
    }

    /**
     * 🔗 RELATION MANAGERS
     * Ini yang bikin tab "Messages" muncul di halaman View
     */
    public static function getRelations(): array
    {
        return [
            MessagesRelationManager::class,
        ];
    }

    /**
     * 📄 RESOURCE PAGES
     */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInstagramConversations::route('/'),
            'create' => Pages\CreateInstagramConversation::route('/create'),
            'edit'   => Pages\EditInstagramConversation::route('/{record}/edit'),
            'view'   => Pages\ViewInstagramConversation::route('/{record}'),
        ];
    }
}
