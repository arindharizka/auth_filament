<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager; // ✅ penting, namespace yang benar
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Social Media';
    protected static ?string $navigationLabel = 'Posts';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('account_id')
                ->relationship('account', 'username')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('platform')
                ->options([
                    'instagram' => 'Instagram',
                    'facebook'  => 'Facebook',
                ])
                ->required(),

            Forms\Components\TextInput::make('caption')
                ->required()
                ->maxLength(500),

            Forms\Components\TextInput::make('media_url')
                ->label('Media URL')
                ->placeholder('https://example.com/image.jpg'),

            Forms\Components\DateTimePicker::make('scheduled_at')
                ->required()
                ->label('Scheduled At'),

            Forms\Components\Select::make('status')
                ->options([
                    'scheduled' => 'Scheduled',
                    'posted'    => 'Posted',
                ])
                ->default('scheduled')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('account.username')->label('Account')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('platform')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('caption')->limit(30)->searchable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->colors([
                    'primary' => 'scheduled',
                    'success' => 'posted',
                ]),
            Tables\Columns\TextColumn::make('scheduled_at')
                ->dateTime('d-M-Y H:i')
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime('d-M-Y H:i')
                ->sortable(),
        ])
        ->filters([])
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
        // ✅ perbaikan: gunakan class hasil import di atas
        return [
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
