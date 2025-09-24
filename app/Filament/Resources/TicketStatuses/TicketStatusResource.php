<?php

namespace App\Filament\Resources\TicketStatuses;

use App\Filament\Resources\TicketStatuses\Pages\CreateTicketStatus;
use App\Filament\Resources\TicketStatuses\Pages\EditTicketStatus;
use App\Filament\Resources\TicketStatuses\Pages\ListTicketStatuses;
use App\Filament\Resources\TicketStatuses\Pages\ViewTicketStatus;
use App\Models\TicketStatus;
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

class TicketStatusResource extends Resource
{
    protected static ?string $model = TicketStatus::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Ticket statuses');
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

                                TextInput::make('order')
                                    ->label(__('Status order'))
                                    ->integer()
                                    ->default(fn () => TicketStatus::whereNull('project_id')->count() + 1)
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label(__('Status order'))
                    ->sortable()
                    ->searchable(),

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
            ])
            ->reorderable('order')
            ->defaultSort('order');
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTicketStatuses::route('/'),
            'create' => CreateTicketStatus::route('/create'),
            'view'   => ViewTicketStatus::route('/{record}'),
            'edit'   => EditTicketStatus::route('/{record}/edit'),
        ];
    }
}
