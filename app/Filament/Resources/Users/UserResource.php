<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Users');
    }

    public static function getPluralLabel(): ?string
    {
        return static::getNavigationLabel();
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Permissions');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('Full name'))
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->label(__('Email address'))
                                    ->email()
                                    ->required()
                                    ->rule(
                                        fn ($record) => 'unique:users,email,'
                                            . ($record ? $record->id : 'NULL')
                                            . ',id,deleted_at,NULL'
                                    )
                                    ->maxLength(255),

                                CheckboxList::make('roles')
                                    ->label(__('Permission roles'))
                                    ->required()
                                    ->columns(3)
                                    ->relationship('roles', 'name'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Full name'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label(__('Email address'))
                    ->sortable()
                    ->searchable(),

                TagsColumn::make('roles.name')
                    ->label(__('Roles'))
                    ->limit(2),

                TextColumn::make('email_verified_at')
                    ->label(__('Email verified at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('socials')
                    ->label(__('Linked social networks'))
                    ->view('partials.filament.resources.social-icon'),

                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view'   => ViewUser::route('/{record}'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }
}
