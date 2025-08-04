<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CreateFormV extends Component
{
    public $action;
    public $method;

    public function __construct($action, $method = 'POST')
    {
        $this->action = $action;
        $this->method = $method;
    }

    public function render()
    {
        return view('components.create-form-v');
    }
}
