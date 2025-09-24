<?php

namespace App\Filament\Resources\TicketPriorities\Pages;

use App\Filament\Resources\TicketPriorities\TicketPriorityResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTicketPriority extends ViewRecord
{
    protected static string $resource = TicketPriorityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
