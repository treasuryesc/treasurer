<?php

namespace Modules\Operations\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use Modules\Operations\Listeners\AddMenu;
use Modules\Operations\Listeners\FinishInstallation;

class Event extends Provider
{
    /**
     * The event listener mappings for the module.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\Module\Installed::class => [
            FinishInstallation::class,
        ],
        \App\Events\Menu\AdminCreated::class => [
            AddMenu::class,
        ],
    ];
}
