<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\Sprint;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class SprintsRelationManager extends RelationManager
{
    protected static string $relationship = 'sprints';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->type === 'scrum';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(1)
                    ->visible(fn ($record) => ! $record)
                    ->extraAttributes([
                        'class' => 'text-danger-500 text-xs',
                    ])
                    ->schema([
                        Placeholder::make('information')
                            ->disableLabel()
                            ->content(new HtmlString(
                                '<span class="font-medium">' . __('Important:') . '</span>' . ' '
                                . __('The creation of a new Sprint will create a linked Epic into to the Road Map')
                            )),
                    ]),

                Grid::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Sprint name'))
                            ->maxLength(255)
                            ->columnSpan(2)
                            ->required(),

                        DatePicker::make('starts_at')
                            ->label(__('Sprint start date'))
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Set $set) => $set('ends_at', Carbon::parse($state)->addWeek()->subDay()))
                            ->beforeOrEqual(fn (Get $get) => $get('ends_at'))
                            ->required(),

                        DatePicker::make('ends_at')
                            ->label(__('Sprint end date'))
                            ->reactive()
                            ->afterOrEqual(fn (Get $get) => $get('starts_at'))
                            ->required(),

                        RichEditor::make('description')
                            ->label(__('Sprint description'))
                            ->columnSpan(2),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Sprint name'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('starts_at')
                    ->label(__('Sprint start date'))
                    ->date()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('ends_at')
                    ->label(__('Sprint end date'))
                    ->date()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('started_at')
                    ->label(__('Sprint started at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('ended_at')
                    ->label(__('Sprint ended at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('remaining')
                    ->label(__('Remaining'))
                    ->suffix(fn ($record) => $record->remaining ? (' ' . __('days')) : '')
                    ->sortable()
                    ->searchable(),

                TagsColumn::make('tickets.name')
                    ->label(__('Tickets'))
                    ->searchable()
                    ->sortable()
                    ->limit(),
            ])
            ->filters([
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                Action::make('start')
                    ->label(__('Start sprint'))
                    ->visible(fn ($record) => ! $record->started_at && ! $record->ended_at)
                    ->requiresConfirmation()
                    ->color('success')
                    ->button()
                    ->icon('heroicon-o-play')
                    ->action(function ($record) {
                        $now = now();
                        Sprint::where('project_id', $record->project_id)
                            ->where('id', '<>', $record->id)
                            ->whereNotNull('started_at')
                            ->whereNull('ended_at')
                            ->update(['ended_at' => $now]);
                        $record->started_at = $now;
                        $record->save();
                        Notification::make('sprint_started')
                            ->success()
                            ->body(__('Sprint started at') . ' ' . $now)
                            ->actions([
                                Action::make('board')
                                    ->color('gray')
                                    ->button()
                                    ->label(
                                        fn () => ($record->project->type === 'scrum' ? __('Scrum board') : __('Kanban board'))
                                    )
                                    ->url(function () use ($record) {
                                        if ($record->project->type === 'scrum') {
                                            return route('filament.pages.scrum/{project}', ['project' => $record->project->id]);
                                        }

                                        return route('filament.pages.kanban/{project}', ['project' => $record->project->id]);
                                    }),
                            ])
                            ->send();
                    }),

                Action::make('stop')
                    ->label(__('Stop sprint'))
                    ->visible(fn ($record) => $record->started_at && ! $record->ended_at)
                    ->requiresConfirmation()
                    ->color('danger')
                    ->button()
                    ->icon('heroicon-o-pause')
                    ->action(function ($record) {
                        $now              = now();
                        $record->ended_at = $now;
                        $record->save();

                        Notification::make('sprint_started')
                            ->success()
                            ->body(__('Sprint ended at') . ' ' . $now)
                            ->send();
                    }),

                Action::make('tickets')
                    ->label(__('Tickets'))
                    ->color('gray')
                    ->icon('heroicon-o-ticket')
                    ->mountUsing(fn (Schema $schema, Sprint $record) => $schema->fill([
                        'tickets' => $record->tickets->pluck('id')->toArray(),
                    ]))
                    ->modalHeading(fn ($record) => $record->name . ' - ' . __('Associated tickets'))
                    ->schema([
                        Placeholder::make('info')
                            ->disableLabel()
                            ->extraAttributes([
                                'class' => 'text-danger-500 text-xs',
                            ])
                            ->content(
                                __('If a ticket is already associated with an other sprint, it will be migrated to this sprint')
                            ),

                        CheckboxList::make('tickets')
                            ->label(__('Choose tickets to associate to this sprint'))
                            ->required()
                            ->extraAttributes([
                                'class' => 'sprint-checkboxes',
                            ])
                            ->options(
                                function ($record) {
                                    $results = [];
                                    foreach ($record->project->tickets as $ticket) {
                                        $results[$ticket->id] = new HtmlString(
                                            '<div class="w-full flex justify-between items-center">'
                                            . '<span>' . $ticket->name . '</span>'
                                            . ($ticket->sprint ? '<span class="text-xs font-medium '
                                                . ($ticket->sprint_id == $record->id ? 'bg-gray-100 text-gray-600' : 'bg-danger-500 text-white')
                                                . ' px-2 py-1 rounded">' . $ticket->sprint->name . '</span>' : '')
                                            . '</div>'
                                        );
                                    }

                                    return $results;
                                }
                            ),
                    ])
                    ->action(function (Sprint $record, array $data): void {
                        $tickets = $data['tickets'];
                        Ticket::where('sprint_id', $record->id)->update(['sprint_id' => null]);
                        Ticket::whereIn('id', $tickets)->update(['sprint_id' => $record->id]);
                        Filament::notify('success', __('Tickets associated with sprint'));
                    }),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('id');
    }

    protected function canAttach(): bool
    {
        return false;
    }
}
