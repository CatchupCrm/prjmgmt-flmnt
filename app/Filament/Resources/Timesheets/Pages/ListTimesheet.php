<?php

namespace App\Filament\Resources\Timesheets\Pages;

use App\Filament\Resources\Timesheets\TimesheetResource;
use Filament\Resources\Pages\ListRecords;

class ListTimesheet extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
