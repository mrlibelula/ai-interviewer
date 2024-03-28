<?php

namespace App\Livewire;

use Livewire\Component;

class Code extends Component
{
    public string $language;
    public string|null $code;

    public function mount(string|null $code, string $language = 'html')
    {
        $this->language = $language;
        $this->code = strtolower($language) === 'html'
            ? htmlspecialchars(!$code ? '' : $code)
            : (!$code ? '' : $code);
    }
    
    public function render()
    {
        return view('livewire.code');
    }
}
