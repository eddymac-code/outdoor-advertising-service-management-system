<?php

namespace App\Livewire;

use Livewire\Component;

class CustomHeader extends Component
{
    public $title = '';
    public $breadcrumbs = [];
    
    public function render()
    {
        return view('livewire.custom-header');
    }
}
