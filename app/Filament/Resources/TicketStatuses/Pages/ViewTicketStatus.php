<?php

namespace App\Filament\Resources\TicketStatuses\Pages;

use App\Filament\Resources\TicketStatuses\TicketStatusResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTicketStatus extends ViewRecord
{
    protected static string $resource = TicketStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
