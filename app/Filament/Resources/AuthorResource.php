<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorResource\Pages;
use App\Filament\Resources\AuthorResource\RelationManagers;
use App\Models\Author;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Forms\Set as Closure;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->circular(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuthors::route('/'),
            'create' => Pages\CreateAuthor::route('/create'),
            'edit' => Pages\EditAuthor::route('/{record}/edit'),
        ];
    }
}
