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
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('headings')
                                    ->multiple()
                                    ->relationship('headings', titleAttribute: 'name')
                                    ->placeholder('Выберите рубрику')
                                    ->label('Рубрики')
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
                                        Forms\Components\TextInput::make('first_name')
                                            ->label('Имя')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('last_name')
                                            ->label('Фамилия')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('speciality')
                                            ->label('Специальность')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('place_of_work')
                                            ->label('Место работы')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('education')
                                            ->label('Образование')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('experience')
                                            ->label('Опыт работы')
                                            ->maxLength(255),
                                        Forms\Components\Radio::make('gender')
                                            ->options([
                                                'm' => 'm',
                                                'f' => 'f',
                                            ]),
                                        Forms\Components\Checkbox::make('status')
                                            ->label('Активность')
                                            ->default(false),
                                        Forms\Components\FileUpload::make('Изображение'),
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('channel_id')
                                    ->label('Проект')
                                    ->placeholder('Выберите проект')
                                    ->options(array_flip(Channel::channelIds()))
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Изображение')
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                                    ->label('Изображение')
                                    ->responsiveImages()
                                    ->conversion('thumb')
                            ]),
                        Forms\Components\Tabs\Tab::make('Товары')
                            ->schema([
                                Forms\Components\TagsInput::make('product_ids')
                                    ->placeholder('new id')
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
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('channel_id')
                    ->formatStateUsing(
                        fn (int $state) => array_search($state, Channel::channelIds())
                    ),
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
                    ->options(array_flip(Channel::channelIds())),
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
