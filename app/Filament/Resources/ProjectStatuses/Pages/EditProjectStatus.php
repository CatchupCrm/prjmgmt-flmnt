<?php

namespace App\Filament\Resources\ProjectStatuses\Pages;

use App\Filament\Resources\ProjectStatuses\ProjectStatusResource;
use App\Models\ProjectStatus;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectStatus extends EditRecord
{
    protected static string $resource = ProjectStatusResource::class;

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
            ProjectStatus::where('id', '<>', $this->record->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
    }
}
