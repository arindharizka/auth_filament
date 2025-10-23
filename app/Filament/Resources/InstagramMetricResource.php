<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstagramMetricResource\Pages;
use App\Models\InstagramMetric;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InstagramMetricResource extends Resource
{
    protected static ?string $model = InstagramMetric::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Social Media';
    protected static ?string $navigationLabel = 'Instagram Metrics';

    public static function form(Forms\Form $form): Forms\Form
    {
        // Karena data metrics di-generate otomatis (bukan input manual)
        // maka form dikosongkan saja
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post.id')->label('Post ID'),
                Tables\Columns\TextColumn::make('post.caption')->limit(40)->label('Caption'),
                Tables\Columns\TextColumn::make('post.account.username')->label('Account'),
                Tables\Columns\TextColumn::make('likes')->sortable(),
                Tables\Columns\TextColumn::make('comments')->sortable(),
                Tables\Columns\TextColumn::make('reach')->sortable(),
                Tables\Columns\TextColumn::make('impressions')->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recorded At')
                    ->dateTime('d-M-Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // hanya untuk lihat detail
            ])
            ->bulkActions([]); // tidak perlu delete/edit massal
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstagramMetrics::route('/'),
            'create' => Pages\CreateInstagramMetric::route('/create'),
            'edit' => Pages\EditInstagramMetric::route('/{record}/edit'),
        ];
    }
}
