<?php

namespace App\Filament\Pages;

use App\Helpers\KanbanScrumHelper;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class FullBoard extends Page implements HasForms
{
    use InteractsWithForms;
    use KanbanScrumHelper;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-view-columns';

    protected string $view = 'filament.pages.kanban';

    protected static ?string $slug = 'full-board';

    protected static ?int $navigationSort = 4;

    public function mount()
    {
        $this->form->fill();
    }

    public function getHeading(): string|Htmlable
    {
        return $this->kanbanHeading();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->button()
                ->label(__('Refresh'))
                ->color('gray')
                ->action(function () {
                    $this->getRecords();
                    Filament::notify('success', __('Kanban board updated'));
                }),
        ];
    }

    protected function getFormSchema(): array
    {
        return $this->formSchema();
    }
}
