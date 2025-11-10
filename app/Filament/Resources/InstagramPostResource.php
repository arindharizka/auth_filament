<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstagramPostResource\Pages;
use App\Models\InstagramPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InstagramPostResource extends Resource
{
    protected static ?string $model = InstagramPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Instagram Module';
    protected static ?string $navigationLabel = 'Posts';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('caption')
                ->rows(4),

            Forms\Components\DateTimePicker::make('posted_at')
                ->label('Posted At'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('caption')
                ->limit(30)
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('posted_at')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
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
        return [
            // nanti diisi CommentsRelationManager biar bisa lihat komentar
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstagramPosts::route('/'),
            'create' => Pages\CreateInstagramPost::route('/create'),
            'edit' => Pages\EditInstagramPost::route('/{record}/edit'),
        ];
    }
}
