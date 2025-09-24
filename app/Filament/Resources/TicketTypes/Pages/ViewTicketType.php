<?php

namespace App\Filament\Resources\TicketTypes\Pages;

use App\Filament\Resources\TicketTypes\TicketTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTicketType extends ViewRecord
{
    protected static string $resource = TicketTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
