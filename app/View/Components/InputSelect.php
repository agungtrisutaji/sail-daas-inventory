<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputSelect extends Component
{
    public $name;
    public $model;
    public $options;
    public $selected;
    public $placeholder;

    public function __construct($name,  $model = null, $options = [], $selected = null, $placeholder = '')
    {
        $this->name = $name;
        $this->model = $model;
        $this->options = $options;
        $this->selected = $selected;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.input-select');
    }
}
