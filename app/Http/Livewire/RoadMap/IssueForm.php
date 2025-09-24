<?php

namespace App\Http\Livewire\RoadMap;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\TicketType;
use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Livewire\Component;

class IssueForm extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?Project $project = null;

    public array $epics;

    public array $sprints;

    public function mount()
    {
        $this->initProject($this->project?->id);
        if ($this->project?->status_type === 'custom') {
            $defaultStatus = TicketStatus::where('project_id', $this->project->id)
                ->where('is_default', true)
                ->first()
                ?->id;
        } else {
            $defaultStatus = TicketStatus::whereNull('project_id')
                ->where('is_default', true)
                ->first()
                ?->id;
        }
        $this->form->fill([
            'project_id'  => $this->project?->id ?? null,
            'owner_id'    => auth()->user()->id,
            'status_id'   => $defaultStatus,
            'type_id'     => TicketType::where('is_default', true)->first()?->id,
            'priority_id' => TicketPriority::where('is_default', true)->first()?->id,
        ]);
    }

    public function render()
    {
        return view('livewire.road-map.issue-form');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        Ticket::create($data);
        Filament::notify('success', __('Ticket successfully saved'));
        $this->cancel(true);
    }

    public function cancel($refresh = false): void
    {
        $this->emit('closeTicketDialog', $refresh);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Grid::make(4)
                        ->schema([
                            Select::make('project_id')
                                ->label(__('Project'))
                                ->searchable()
                                ->reactive()
                                ->disabled($this->project != null)
                                ->columnSpan(2)
                                ->options(
                                    fn () => Project::where('owner_id', auth()->user()->id)
                                        ->orWhereHas('users', function ($query) {
                                            return $query->where('users.id', auth()->user()->id);
                                        })->pluck('name', 'id')->toArray()
                                )
                                ->afterStateUpdated(fn (Get $get) => $this->initProject($get('project_id')))
                                ->required(),

                            Select::make('sprint_id')
                                ->label(__('Sprint'))
                                ->searchable()
                                ->reactive()
                                ->visible(fn () => $this->project && $this->project->type === 'scrum')
                                ->columnSpan(2)
                                ->options(fn () => $this->sprints),

                            Select::make('epic_id')
                                ->label(__('Epic'))
                                ->searchable()
                                ->reactive()
                                ->columnSpan(2)
                                ->required()
                                ->visible(fn () => $this->project && $this->project->type !== 'scrum')
                                ->options(fn () => $this->epics),

                            TextInput::make('name')
                                ->label(__('Ticket name'))
                                ->required()
                                ->columnSpan(4)
                                ->maxLength(255),
                        ]),

                    Select::make('owner_id')
                        ->label(__('Ticket owner'))
                        ->searchable()
                        ->options(fn () => User::all()->pluck('name', 'id')->toArray())
                        ->required(),

                    Select::make('responsible_id')
                        ->label(__('Ticket responsible'))
                        ->searchable()
                        ->options(fn () => User::all()->pluck('name', 'id')->toArray()),

                    Grid::make()
                        ->columns(3)
                        ->columnSpan(2)
                        ->schema([
                            Select::make('status_id')
                                ->label(__('Ticket status'))
                                ->searchable()
                                ->options(function ($get) {
                                    if ($this->project?->status_type === 'custom') {
                                        return TicketStatus::where('project_id', $this->project->id)
                                            ->get()
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    }

                                    return TicketStatus::whereNull('project_id')
                                        ->get()
                                        ->pluck('name', 'id')
                                        ->toArray();
                                })
                                ->required(),

                            Select::make('type_id')
                                ->label(__('Ticket type'))
                                ->searchable()
                                ->options(fn () => TicketType::all()->pluck('name', 'id')->toArray())
                                ->required(),

                            Select::make('priority_id')
                                ->label(__('Ticket priority'))
                                ->searchable()
                                ->options(fn () => TicketPriority::all()->pluck('name', 'id')->toArray())
                                ->required(),
                        ]),
                ]),

            RichEditor::make('content')
                ->label(__('Ticket content'))
                ->required()
                ->columnSpan(2),

            Grid::make()
                ->columnSpan(2)
                ->columns(12)
                ->schema([
                    TextInput::make('estimation')
                        ->label(__('Estimation time'))
                        ->numeric()
                        ->columnSpan(4),
                ]),
        ];
    }

    private function initProject($projectId): void
    {
        if ($projectId) {
            $this->project = Project::where('id', $projectId)->first();
        } else {
            $this->project = null;
        }
        $this->epics   = $this->project ? $this->project->epics->pluck('name', 'id')->toArray() : [];
        $this->sprints = $this->project ? $this->project->sprints->pluck('name', 'id')->toArray() : [];
    }
}
