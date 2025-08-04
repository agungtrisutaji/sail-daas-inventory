<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputCurrency extends Component
{
    public $name;
    public $value;
    public $placeholder;

    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param string|numeric $value
     * @param string $placeholder
     */
    public function __construct($name, $value = '', $placeholder = 'Rp 0,00')
    {
        $this->name = $name;
        // Format nilai jika ada
        $this->value = $value ? $this->formatCurrency($value) : '';
        $this->placeholder = $placeholder;
    }

    /**
     * Format nilai numerik ke format rupiah untuk ditampilkan
     *
     * @param mixed $value
     * @return string
     */
    protected function formatCurrency($value)
    {
        // Pastikan nilai numerik
        $value = (float) $value;
        // Format dengan 2 desimal
        return number_format($value, 2, ',', '.');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input-currency');
    }
}
