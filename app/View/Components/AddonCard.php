<?php

namespace App\View\Components;

use App\Models\Addon;
use Closure;
use Illuminate\Contracts\View\View;

class AddonCard extends Component
{
    /**
     * View path for the component.
     */
    public string $view = 'components.addon-card';

    /**
     * The version of the addon to show.
     */
    public ?string $version;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public Addon $addon,
        public bool $showPublisher = true,
        public ?string $link = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $this->version = $this->addon->versions->last()->version ?? '1.0.0';

        if (! str_starts_with($this->version, 'v')) {
            $this->version = 'v' . $this->version;
        }

        return view($this->view);
    }
}
