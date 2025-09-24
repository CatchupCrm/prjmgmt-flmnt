<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\TicketStatus;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StatusesRelationManager extends RelationManager
{
    protected static string $relationship = 'statuses';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->status_type === 'custom';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->default(
                        fn ($livewire) => TicketStatus::where('project_id', $livewire->ownerRecord->id)->count() + 1
                    )
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
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
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('order');
    }

    protected function canAttach(): bool
    {
        return false;
    }
}
