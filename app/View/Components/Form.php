<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;

class Form extends Component
{
    /**
     * View path for the component.
     */
    public string $view = 'components.form';

    /**
     * The actual form method passed to the `method` attribute.
     */
    public string $formMethod;

    /**
     * The form identifier.
     */
    public string $identifier;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $id = null,
        public string $method = 'GET',
        public string $action = '.',
        public ?string $route = null,
        public array $routeParams = [],
        public string $enctype = 'multipart/form-data',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $this->id ??= uniqid('form-');
        $this->method = strtoupper($this->method);
        $this->formMethod = $this->method === 'GET' ? 'GET' : 'POST';
        $this->identifier = base64_encode(sprintf('%s:%s', $this->action, $this->method));

        if ($this->route) {
            $this->action = route($this->route, $this->routeParams);
        }

        return view($this->view);
    }
}
