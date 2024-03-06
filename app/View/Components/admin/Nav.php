<?php

namespace App\View\Components\admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Nav extends Component
{
    public string $currentRoute = '';
    /**
     * Create a new component instance.
     */
    public function __construct(string $currentRoute)
    {
        $this->currentRoute = $currentRoute;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.nav');
    }
}
