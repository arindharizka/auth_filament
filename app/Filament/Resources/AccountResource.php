<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('platform')
                ->label('Platform')
                ->options([
                    'instagram' => 'Instagram',
                    'facebook'  => 'Facebook',
                ])
                ->required(),

            Forms\Components\TextInput::make('username')
                ->label('Username')
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\Textarea::make('integration_data')
                ->label('Integration Data (JSON)')
                ->rows(5)
                ->placeholder('{"access_token": "your_token", "user_id": "123456"}')
                ->helperText('Masukkan data integrasi API (misalnya access_token atau user_id) dalam format JSON.')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('platform')
                    ->label('Platform')
                    ->sortable(),

                Tables\Columns\TextColumn::make('integration_data')
                    ->label('Integration Data')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index'  => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit'   => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
