<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $inverseRelationship = 'projectsAffected';

    public static function attach(Schema $schema): Schema
    {
        return $schema
            ->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('User full name'))
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('pivot.role')
                    ->label(__('User role'))
                    ->enum(config('system.projects.affectations.roles.list'))
                    ->colors(config('system.projects.affectations.roles.colors'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
            ])
            ->headerActions([
                CreateAction::make(),
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('role')
                            ->label(__('User role'))
                            ->searchable()
                            ->default(fn () => config('system.projects.affectations.roles.default'))
                            ->options(fn () => config('system.projects.affectations.roles.list'))
                            ->required(),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalWidth('xl')
                    ->schema(fn (EditAction $action): array => [
                        Select::make('role')
                            ->label(__('User role'))
                            ->searchable()
                            ->options(fn () => config('system.projects.affectations.roles.list'))
                            ->required(),
                    ]),
                DeleteAction::make(),
                DetachAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
                DetachBulkAction::make(),
            ]);
    }

    protected function canCreate(): bool
    {
        return false;
    }

    protected function canDelete(Model $record): bool
    {
        return false;
    }

    protected function canDeleteAny(): bool
    {
        return false;
    }
}
