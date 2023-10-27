<?php

namespace App\Filament\Resources;

use App\Enums\Channel;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Статусы')
                    ->schema([
                        Forms\Components\Checkbox::make('status')
                            ->label('Активность')
                            ->default(false),
                        Forms\Components\Checkbox::make('noindex')
                            ->label('Индексировать')
                            ->default(true),
                    ]),

                Forms\Components\Fieldset::make('Основные поля')
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

                Forms\Components\Fieldset::make('Контент')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->label('Контент'),
                        Forms\Components\RichEditor::make('heading')
                            ->label('Рубрика')
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
