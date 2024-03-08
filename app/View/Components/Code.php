<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Code extends Component
{
    public $language;
    public $code;

    /**
     * Create a new component instance.
     */
    public function __construct(string $code, string $language = 'html')
    {
        $this->language = strtolower($language);
        $this->code = strtolower($language) === 'html'
            ? htmlspecialchars($code)
            : $code;
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.code');
    }
}
