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

    protected static ?string $navigationGroup = 'Social Media';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Instagram Comments';
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    /* ================= FORM ================= */

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('post_id')
                ->relationship('post', 'caption')
                ->label('Post')
                ->required()
                ->searchable()
                ->preload(),

            Forms\Components\TextInput::make('username')
                ->label('Username')
                ->required()
                ->maxLength(100),

            Forms\Components\Textarea::make('comment')
                ->label('Comment')
                ->required()
                ->rows(3),
        ]);
    }

    /* ================= TABLE ================= */

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('commented_at', 'desc') // 🔧 REVISI 5
            ->columns([

                // 🔧 REVISI 1 — USERNAME
                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->searchable(),

                // 🔧 REVISI 1 — COMMENT
                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(40)
                    ->wrap(),

                // 🔧 REVISI 4 — POST DENGAN TOOLTIP
                Tables\Columns\TextColumn::make('post.caption')
                    ->label('Post')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->post?->caption),

                // 🔧 REVISI 2 — STATUS BADGE
                Tables\Columns\BadgeColumn::make('is_replied')
                    ->label('Status')
                    ->colors([
                        'danger' => false,
                        'success' => true,
                    ])
                    ->formatStateUsing(fn ($state) =>
                        $state ? 'Sudah dibalas' : 'Belum dibalas'
                    ),

                Tables\Columns\TextColumn::make('commented_at')
                    ->label('Waktu')
                    ->since(),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('is_replied')
                    ->label('Status Balasan')
                    ->options([
                        0 => 'Belum dibalas',
                        1 => 'Sudah dibalas',
                    ]),
            ])

            ->actions([
                // 🔧 REVISI 3 — TOMBOL REPLY KONDISIONAL
                Tables\Actions\Action::make('reply')
                    ->label('Reply')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->form([
                        Forms\Components\Textarea::make('reply_message')
                            ->label('Balasan')
                            ->required(),
                    ])
                    ->action(function ($record) {
                        $record->update([
                            'is_replied' => true,
                        ]);
                    })
                    ->visible(fn ($record) => ! $record->is_replied),
            ])

            ->bulkActions([]);
    }

    /* ================= PAGES ================= */

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstagramComments::route('/'),
        ];
    }
}
