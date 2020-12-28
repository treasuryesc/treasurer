<?php

namespace Modules\Operations\Listeners;

class AddMenu
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Add new menu item
        $menu = $event->menu->add([
            'url' => 'operations',
            'title' => 'Operações',
            'icon' => 'fas fa-university',
            'order' => 20,
        ]);

        // Add child to existing menu item
        $menu->url('operations/settings', 'Configurações', 1, ['icon' => '']);
        $menu->url('operations/loans', 'Empréstimos', 1, ['icon' => '']);
        $menu->url('operations/receivables', 'Recebíveis', 1, ['icon' => '']);
        $menu->url('operations/drops', 'Baixa', 1, ['icon' => '']);
    }
}
