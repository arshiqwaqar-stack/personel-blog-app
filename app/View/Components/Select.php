<?php
namespace App\View\Components;

use Illuminate\View\Component;

class Select extends Component
{
    public $name;
    public $label;
    public $options;
    public $selected;
    public $id;
    public $customattributes;

    public function __construct($name, $label = null, $options = [], $selected = null,$id = null,$customattributes = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        $this->selected = $selected;
        $this->id = $id;
        $this->customattributes = $customattributes
            ? str_replace(",", " ", $customattributes)
            : null;
    }

    public function render()
    {
        return view('components.select');
    }
}
