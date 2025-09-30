<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstagramCommentResource\Pages;
use App\Models\InstagramComment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InstagramCommentResource extends Resource
{
    protected static ?string $model = InstagramComment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Instagram Module';
    protected static ?string $navigationLabel = 'Comments';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('instagram_post_id')
                ->relationship('post', 'title')
                ->label('Post')
                ->required()
                ->searchable()
                ->preload(),

            Forms\Components\TextInput::make('username')
                ->required()
                ->maxLength(100),

            Forms\Components\Textarea::make('comment')
                ->required()
                ->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('post.title')
                ->label('Post')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('username')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('comment')
                ->limit(50)
                ->tooltip(fn ($record) => $record->comment),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstagramComments::route('/'),
            'create' => Pages\CreateInstagramComment::route('/create'),
            'edit' => Pages\EditInstagramComment::route('/{record}/edit'),
        ];
    }
}
