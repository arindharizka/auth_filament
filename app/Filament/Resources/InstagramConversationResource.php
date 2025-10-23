<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstagramConversationResource\Pages;
use App\Filament\Resources\InstagramConversationResource\RelationManagers;
use App\Models\InstagramConversation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InstagramConversationResource extends Resource
{
    protected static ?string $model = InstagramConversation::class;

    protected static ?string $navigationGroup = 'Social Media';
    protected static ?string $navigationLabel = 'Instagram Conversations';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('account_id')
                    ->label('Account')
                    ->relationship('account', 'username')
                    ->required(),

                Forms\Components\TextInput::make('participant_username')
                    ->label('Participant Username')
                    ->required(),

                Forms\Components\Textarea::make('last_message')
                    ->label('Last Message')
                    ->rows(2)
                    ->nullable(),

                Forms\Components\DateTimePicker::make('last_activity_at')
                    ->label('Last Activity')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account.username')->label('Account'),
                Tables\Columns\TextColumn::make('participant_username')->label('Participant'),
                Tables\Columns\TextColumn::make('last_message')->limit(50)->label('Last Message'),
                Tables\Columns\TextColumn::make('last_activity_at')
                    ->dateTime('d-M-Y H:i')
                    ->label('Last Activity'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Hubungkan ke relation manager untuk messages
    public static function getRelations(): array
    {
        return [
            RelationManagers\MessagesRelationManager::class,
        ];
    }

    // Routing halaman resource di panel Filament
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstagramConversations::route('/'),
            'create' => Pages\CreateInstagramConversation::route('/create'),
            'edit' => Pages\EditInstagramConversation::route('/{record}/edit'),
        ];
    }
}
