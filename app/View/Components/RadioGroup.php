<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RadioGroup extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        // public arguments in the constructor become properties of this class
        public string $name,


        public array $options
    )
    {
        //
    }

    public function optionsWithLabels(): array {  // this can be used as a variable inside Blade
        // check is the array is associative (in this case if has label aliases in the model)

        return array_is_list($this->options)
            ? array_combine($this->options, $this->options) // make key => value pairs if list
            : $this->options;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.radio-group');
    }
}
