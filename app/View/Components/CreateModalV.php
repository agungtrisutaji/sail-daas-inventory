<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CreateModalV extends Component
{
    public $id;
    public $title;

    public function __construct($id, $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.create-modal-v');
    }
}
