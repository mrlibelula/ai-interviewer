<?php

namespace App\Livewire;

use App\Tool;
use Livewire\Component;

class Code extends Component
{
  public string $language;
  public string|null $code;

  public function mount(string|null $code, string $language = 'html')
  {
    $this->language = $language;
    // Escape in the Blade view ({{ }}); expand minified LLM solutions for readable codebox.
    $this->code = Tool::normalizeSolutionCode($code ?: '');
  }

  public function render()
  {
    return view('livewire.code');
  }

  public function rendered(): void
  {
    $this->dispatch('highlight-code');
  }
}
