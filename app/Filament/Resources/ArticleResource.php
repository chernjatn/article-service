<?php

namespace App\Filament\Resources;

use App\Enums\Channel;
use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Статусы')
                    ->schema([
                        Forms\Components\Checkbox::make('status')
                            ->label('Активность')
                            ->default(false),
                        Forms\Components\Checkbox::make('noindex')
                            ->label('Индексировать')
                            ->default(true),
                    ]),

                Forms\Components\Section::make('Основные поля')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Заголовок')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('author')
                            ->label('Автор')
                            ->maxLength(255),
                        Forms\Components\Select::make('channel_id')
                            ->label('Проект')
                            ->options(array_flip(Channel::channelIds()))
                            ->required(),
                        Forms\Components\FileUpload::make('Изображение'),
                    ]),

                Forms\Components\Section::make('Контент')
                    ->schema([
                        Forms\Components\Textarea::make('heading')
                            ->label('Рубрика')
                            ->default(''),
                        Forms\Components\MarkdownEditor::make('content')
                            ->label('Верстка')
                            ->fileAttachmentsDisk('s3')
                            ->fileAttachmentsDirectory('attachments')
                            ->fileAttachmentsVisibility('private')
                            ->default('{}'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(array_flip(Channel::channelIds())),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\CreateAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
