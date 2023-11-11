<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorResource\Pages;
use App\Filament\Resources\AuthorResource\RelationManagers;
use App\Models\Author;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('Имя')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Фамилия')
                            ->maxLength(255)
                            ->required(),
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
                            ->maxLength(255)
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
                        Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                            ->label('Изображение')
                            ->responsiveImages()
                            ->conversion('thumb')
                            ->required(),
                    ])
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
