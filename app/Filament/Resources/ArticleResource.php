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
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Название')
                                            ->maxLength(255)
                                            ->required(),
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('author_id')
                                    ->label('Автор')
                                    ->placeholder('Выберите автора')
                                    ->relationship('author', 'last_name')
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\Tabs::make('Label')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('Основные поля')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('first_name')
                                                            ->label('Имя')
                                                            ->maxLength(255)
                                                            ->required(),
                                                        Forms\Components\TextInput::make('last_name')
                                                            ->label('Фамилия')
                                                            ->maxLength(255)
                                                            ->required()
                                                            ->reactive()
                                                            ->afterStateUpdated(function (Set $set, $state, $context) {
                                                                if ($context === 'edit') {
                                                                    return;
                                                                }

                                                                $set('slug', Str::slug($state));
                                                            }),
                                                        Forms\Components\TextInput::make('second_name')
                                                            ->label('Отчество')
                                                            ->maxLength(255)
                                                            ->required(),
                                                        Forms\Components\TextInput::make('slug')
                                                            ->label('Url')
                                                            ->required()
                                                            ->maxLength(255)
                                                            ->rules(['alpha_dash'])
                                                            ->unique(ignoreRecord: true)
                                                            ->maxLength(255),
                                                        Forms\Components\TextInput::make('speciality')
                                                            ->label('Специальность')
                                                            ->maxLength(255)
                                                            ->required(),
                                                        Forms\Components\TextInput::make('place_of_work')
                                                            ->label('Место работы')
                                                            ->maxLength(255)
                                                            ->required(),
                                                        Forms\Components\TextInput::make('education')
                                                            ->label('Образование')
                                                            ->maxLength(255)
                                                            ->required(),
                                                        Forms\Components\TextInput::make('experience')
                                                            ->label('Опыт работы')
                                                            ->required(),
                                                        Forms\Components\Radio::make('gender')
                                                            ->options([
                                                                'm' => 'm',
                                                                'f' => 'f',
                                                            ])
                                                            ->required()
                                                            ->columns(2),
                                                        Forms\Components\Checkbox::make('status')
                                                            ->label('Активность')
                                                            ->default(false)
                                                            ->required(),
                                                    ])->columns(2),
                                                Forms\Components\Tabs\Tab::make('Изображение')
                                                    ->schema([
                                                        Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                                                            ->label('Изображение')
                                                            ->responsiveImages()
                                                            ->conversion('thumb')
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('Вложения')
                                                    ->schema([
                                                        Forms\Components\SpatieMediaLibraryFileUpload::make('documents')
                                                            ->label('Лицензии, награды, сертификаты, грамоты')
                                                            ->multiple()
                                                            ->collection('documents')
                                                    ]),
                                            ]),
                                    ])
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
                                    ->default('тест'),
                                Forms\Components\RichEditor::make('content')
                                    ->label('Верстка')
                                    ->fileAttachmentsDisk('s3')
                                    ->fileAttachmentsDirectory('attachments')
                                    ->fileAttachmentsVisibility('private')
                                    ->default('тест'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Seo')
                            ->schema([
                                Forms\Components\Fieldset::make()
                                    ->relationship('seo')
                                    ->schema([
                                        Forms\Components\TextInput::make('header'),
                                        Forms\Components\TextInput::make('title'),
                                        Forms\Components\TextInput::make('description'),
                                    ]),
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
