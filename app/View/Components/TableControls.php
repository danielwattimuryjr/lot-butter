<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableControls extends Component
{
    /**
     * The search parameter name.
     */
    public string $searchParam;

    /**
     * The search placeholder text.
     */
    public string $searchPlaceholder;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $searchParam = 'name',
        string $searchPlaceholder = 'Search by name'
    ) {
        $this->searchParam = $searchParam;
        $this->searchPlaceholder = $searchPlaceholder;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-controls');
    }
}
