<?php
namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $href;
    public $type;
    public $variant;

    public function __construct($href = null, $type = 'button', $variant = 'primary')
    {
        $this->href = $href;
        $this->type = $type;
        $this->variant = $variant;
    }

    public function render()
    {
        return view('components.button');
    }
}
