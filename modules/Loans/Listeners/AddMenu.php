<?php

namespace Modules\Loans\Listeners;

use App\Events\Menu\AdminCreated as Event;

class AddMenu
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        // Add new menu item
        $menu = $event->menu->add([
            'url' => 'loans',
            'title' => 'Operações',
            'icon' => 'fas fa-university',
            'order' => 20,
        ]);

        // Add child to existing menu item
        $menu->url('loans/loans', 'Empréstimos', 1, ['icon' => '']);
        $menu->url('loans/receivables', 'Recebíveis', 1, ['icon' => '']);

    }
}