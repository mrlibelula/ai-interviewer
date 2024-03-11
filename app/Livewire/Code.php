<?php

namespace App\Livewire;

use Livewire\Component;

class Code extends Component
{
    public $language;
    public $code;

    public function mount(string $code, string $language = 'html')
    {
        $this->language = $language;
        $this->code = strtolower($language) === 'html'
            ? htmlspecialchars($code)
            : $code;
    }
    
    public function render()
    {
        return view('livewire.code');
    }
}
