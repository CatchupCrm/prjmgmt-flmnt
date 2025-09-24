<?php

namespace App\Filament\Resources\ProjectStatuses;

use App\Filament\Resources\ProjectStatuses\Pages\CreateProjectStatus;
use App\Filament\Resources\ProjectStatuses\Pages\EditProjectStatus;
use App\Filament\Resources\ProjectStatuses\Pages\ListProjectStatuses;
use App\Filament\Resources\ProjectStatuses\Pages\ViewProjectStatus;
use App\Models\ProjectStatus;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectStatusResource extends Resource
{
    protected static ?string $model = ProjectStatus::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Project statuses');
    }

    public static function getPluralLabel(): ?string
    {
        return static::getNavigationLabel();
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Referential');
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
                                    ->label(__('Status name'))
                                    ->required()
                                    ->maxLength(255),

                                ColorPicker::make('color')
                                    ->label(__('Status color'))
                                    ->required(),

                                Checkbox::make('is_default')
                                    ->label(__('Default status'))
                                    ->helperText(
                                        __('If checked, this status will be automatically affected to new projects')
                                    ),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('color')
                    ->label(__('Status color'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label(__('Status name'))
                    ->sortable()
                    ->searchable(),

                IconColumn::make('is_default')
                    ->label(__('Default status'))
                    ->boolean()
                    ->sortable(),

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
            'index'  => ListProjectStatuses::route('/'),
            'create' => CreateProjectStatus::route('/create'),
            'view'   => ViewProjectStatus::route('/{record}'),
            'edit'   => EditProjectStatus::route('/{record}/edit'),
        ];
    }
}
