<?php

declare(strict_types=1);

namespace App\Http\Livewire\Timesheet;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Tables\Columns\TextColumn;
use App\Models\Ticket;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

abstract class TimeLogged extends Component implements HasTable, HasActions
{
    use InteractsWithActions;
    use InteractsWithTable;

    public Ticket $ticket;

    protected function getFormModel(): Model|string|null
    {
        return $this->ticket;
    }

    protected function getTableQuery(): Builder
    {
        return $this->ticket->hours()->getQuery();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('user.name')
                ->label(__('Owner'))
                ->sortable()
                ->formatStateUsing(fn ($record) => view('components.user-avatar', ['user' => $record->user]))
                ->searchable(),

            TextColumn::make('value')
                ->label(__('Hours'))
                ->sortable()
                ->searchable(),
            TextColumn::make('comment')
                ->label(__('Comment'))
                ->limit(50)
                ->sortable()
                ->searchable(),

            TextColumn::make('activity.name')
                ->label(__('Activity'))
                ->sortable(),

            TextColumn::make('ticket.name')
                ->label(__('Ticket'))
                ->sortable(),

            TextColumn::make('created_at')
                ->label(__('Created at'))
                ->dateTime()
                ->sortable()
                ->searchable(),
        ];
    }
}
