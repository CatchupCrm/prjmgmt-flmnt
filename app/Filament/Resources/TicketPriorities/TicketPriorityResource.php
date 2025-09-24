<?php

namespace App\Filament\Resources\TicketPriorities;

use App\Filament\Resources\TicketPriorities\Pages\CreateTicketPriority;
use App\Filament\Resources\TicketPriorities\Pages\EditTicketPriority;
use App\Filament\Resources\TicketPriorities\Pages\ListTicketPriorities;
use App\Filament\Resources\TicketPriorities\Pages\ViewTicketPriority;
use App\Models\TicketPriority;
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

class TicketPriorityResource extends Resource
{
    protected static ?string $model = TicketPriority::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-badge';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('Ticket priorities');
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
                                    ->label(__('Priority name'))
                                    ->required()
                                    ->maxLength(255),

                                ColorPicker::make('color')
                                    ->label(__('Priority color'))
                                    ->required(),

                                Checkbox::make('is_default')
                                    ->label(__('Default priority'))
                                    ->helperText(
                                        __('If checked, this priority will be automatically affected to new tickets')
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
                    ->label(__('Priority color'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label(__('Priority name'))
                    ->sortable()
                    ->searchable(),

                IconColumn::make('is_default')
                    ->label(__('Default priority'))
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
            'index'  => ListTicketPriorities::route('/'),
            'create' => CreateTicketPriority::route('/create'),
            'view'   => ViewTicketPriority::route('/{record}'),
            'edit'   => EditTicketPriority::route('/{record}/edit'),
        ];
    }
}
