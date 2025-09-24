<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use App\Models\TicketType as Model;
use Closure;
use Illuminate\View\Component;

class TicketType extends Component
{
    public Model $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Model $type)
    {
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.ticket-type');
    }
}
