<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
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
        return $form
            ->schema([
                Card::make()->schema([
                    Select::make('account_id')
                        ->relationship('account', 'username')
                        ->label('Account')
                        ->searchable()
                        ->required()
                        ->reactive(),

                    Select::make('platform')
                        ->options([
                            'instagram' => 'Instagram',
                            'facebook' => 'Facebook',
                        ])
                        ->required()
                        ->reactive(),

                    Textarea::make('caption')
                        ->label('Caption')
                        ->rows(4)
                        ->reactive()
                        ->maxLength(500)
                        ->required(),

                    FileUpload::make('media')
                        ->label('Upload Media')
                        ->image()
                        ->imagePreviewHeight('150')
                        ->directory('posts')
                        ->reactive(),

                    DateTimePicker::make('scheduled_at')
                        ->label('Scheduled At')
                        ->required()
                        ->reactive(),

                    Select::make('status')
                        ->options([
                            'scheduled' => 'Scheduled',
                            'posted' => 'Posted',
                        ])
                        ->default('scheduled')
                        ->required(),
                ])->columns(2),

                // 🔹 Preview otomatis konten post
                Card::make()
                    ->schema([
                        Placeholder::make('preview')
                            ->label('Preview Konten')
                            ->content(fn (callable $get) => self::renderPreviewHtml($get)),
                    ])
                    ->columnSpan('full')
                    ->visible(fn (callable $get) => filled($get('caption')) || filled($get('media'))),
            ]);
    }

    // 🔸 Fungsi helper preview HTML
    protected static function renderPreviewHtml(callable $get): string
    {
        $platform = $get('platform') ?? '-';
        $accountId = $get('account_id') ?? '-';
        $caption = e($get('caption') ?? '');
        $scheduled = $get('scheduled_at') ?? '-';

        $media = $get('media');
        $imageUrl = null;

        if (is_array($media) && isset($media[0])) {
            $imageUrl = $media[0]->temporaryUrl() ?? null;
        } elseif (is_string($media)) {
            $imageUrl = asset('storage/' . $media);
        }

        $imageHtml = $imageUrl
            ? "<img src=\"{$imageUrl}\" alt=\"media\" style=\"max-width:300px;height:auto;border-radius:8px;\" />"
            : "<div style='width:300px;height:200px;border:1px dashed #ccc;display:flex;align-items:center;justify-content:center;color:#777;'>No Image</div>";

        return <<<HTML
<div style="display:flex;gap:16px;align-items:flex-start;margin-top:8px;">
    <div>{$imageHtml}</div>
    <div style="flex:1;">
        <div style="font-size:12px;color:#666;margin-bottom:4px;">
            Platform: <strong>{$platform}</strong> — Account ID: {$accountId}
        </div>
        <div style="background:#fafafa;border-radius:8px;padding:12px;border:1px solid #eee;">
            <div style="white-space:pre-wrap;word-wrap:break-word;">{$caption}</div>
        </div>
        <div style="margin-top:8px;font-size:12px;color:#888;">
            Scheduled at: <strong>{$scheduled}</strong>
        </div>
    </div>
</div>
HTML;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('account.username')->label('Account')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('platform')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('caption')->limit(30)->searchable(),
                Tables\Columns\ImageColumn::make('media')->label('Media'),
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
