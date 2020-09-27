<?php

namespace Modules\Loans\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use Modules\Loans\Listeners\FinishInstallation;
use Modules\Loans\Listeners\AddMenu;

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
