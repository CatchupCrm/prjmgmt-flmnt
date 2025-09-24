<?php

namespace App\Filament\Resources\TicketTypes\Pages;

use App\Filament\Resources\TicketTypes\TicketTypeResource;
use App\Models\TicketType;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTicketType extends EditRecord
{
    protected static string $resource = TicketTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->is_default) {
            TicketType::where('id', '<>', $this->record->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
    }
}
