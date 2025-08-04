<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CreateButton extends Component
{
    public $modalTarget;

    public function __construct($modalTarget)
    {
        $this->modalTarget = $modalTarget;
    }

    public function render()
    {
        return view('components.create-button');
    }
}
