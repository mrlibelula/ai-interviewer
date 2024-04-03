<?php

namespace App\Livewire;

use Livewire\Component;

class Chatbot extends Component
{
    public $chat_welcome;
    public $chat_message;

    public function render()
    {
        return view('livewire.chatbot');
    }
}
