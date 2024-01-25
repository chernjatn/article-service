<?php

namespace App\Filament\Resources;

use App\Enums\Channel;
use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use App\Services\Wp\WpConnectionManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    use SettingComponentTrait;

    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Label')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Основные поля')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Заголовок')
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state, $context) {
                                        if ($context === 'edit') {
                                            return;
                                        }

                                        if (($get('slug') ?? '') !== Str::slug($old)) {
                                            return;
                                        }

                                        $set('slug', Str::slug($state));
                                    })
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('slug')
                                    ->label('URL')
                                    ->required()
                                    ->maxLength(255)
                                    ->rules(['alpha_dash'])
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Forms\Components\Select::make('heading_id')
                                    ->relationship('heading', titleAttribute: 'name')
                                    ->placeholder('Выберите рубрику')
                                    ->label('Рубрика')
                                    ->preload()
                                    ->createOptionForm(fn (Form $form) => HeadingResource::form($form))
                                    ->required(),
                                Forms\Components\Select::make('author_id')
                                    ->label('Автор')
                                    ->placeholder('Выберите автора')
                                    ->relationship('author', 'last_name')
                                    ->preload()
                                    ->createOptionForm(fn (Form $form) => AuthorResource::form($form))
                                    ->required(),
                                Forms\Components\Select::make('channel')
                                    ->label('Проект')
                                    ->placeholder('Выберите проект')
                                    ->options(Channel::class)
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Изображение')
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                                    ->label('Изображение')
                                    ->responsiveImages()
                                    ->conversion('thumb')
                            ]),
                        Forms\Components\Tabs\Tab::make('Слайдеры')
                            ->schema([
                                Forms\Components\Repeater::make('product_ids')
                                    ->label('')
                                    ->schema([
                                        Forms\Components\TagsInput::make('product_ids')
                                            ->label('Слайдер')
                                            ->placeholder('new id')
                                            ->default([])
                                    ])
                                    ->defaultItems(1)
                                    ->addActionLabel('Добавить слайдер')
                                    ->cloneable()
                            ]),
                        Forms\Components\Tabs\Tab::make('Статусы')
                            ->schema([
                                Forms\Components\Checkbox::make('status')
                                    ->label('Активность')
                                    ->default(false),
                                Forms\Components\Checkbox::make('noindex')
                                    ->label('Индексировать')
                                    ->default(true),
                                Forms\Components\Checkbox::make('in_slider')
                                    ->label('В слайдере')
                                    ->default(false),
                            ]),
                        Forms\Components\Tabs\Tab::make('Контент')
                            ->schema([
                                Forms\Components\RichEditor::make('excerpt')
                                    ->label('Отрывок')
                                    ->fileAttachmentsDisk('s3')
                                    ->fileAttachmentsDirectory('attachments')
                                    ->fileAttachmentsVisibility('private')
                                    ->required()
                                    ->default('тест'),
                                Forms\Components\RichEditor::make('content')
                                    ->label('Верстка')
                                    ->fileAttachmentsDisk('s3')
                                    ->fileAttachmentsDirectory('attachments')
                                    ->fileAttachmentsVisibility('private')
                                    ->required()
                                    ->default('тест'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Seo')
                            ->schema([
                                //TODO вынести в ресурс, либо получать через метод
                                Forms\Components\Fieldset::make()
                                    ->relationship('seo')
                                    ->schema([
                                        Forms\Components\Textarea::make('header')
                                            ->required(),
                                        Forms\Components\Textarea::make('title')
                                            ->required(),
                                        Forms\Components\Textarea::make('description')
                                            ->required(),
                                    ])
                                    ->columns(1),
                            ]),
                        Forms\Components\Tabs\Tab::make('Привязки')
                            ->schema([
                                Forms\Components\Select::make('tradeName')
                                    ->label('Торговое наименование')
                                    ->multiple()
                                    ->relationship(name: 'tradeName', titleAttribute: 'name')
                                    ->searchable(['name'])
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('channel'),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author.last_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wp_article_id')
                    ->formatStateUsing(
                        function (int $state) {
                            $config = WpConnectionManager::getConfig();
                            $url = Str::replace('$id', $state, $config['url_edit']);
                            return self::createLink($url, 'See External Resource');
                        }
                    )
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(Channel::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\CreateAction::make(),
                Tables\Actions\ReplicateAction::make(),
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
