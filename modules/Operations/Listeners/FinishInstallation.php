<?php

namespace Modules\Operations\Listeners;

use App\Events\Module\Installed as Event;
use App\Traits\Permissions;

class FinishInstallation
{
    use Permissions;

    public $alias = 'operations';

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->alias != $this->alias) {
            return;
        }

        $this->updatePermissions();
    }

    protected function updatePermissions()
    {
        // c=create, r=read, u=update, d=delete
        $this->attachPermissionsToAdminRoles([
            $this->alias . '-settings' => 'r',
            $this->alias . '-settings-loan-types' => 'c,r,u,d',
            $this->alias . '-settings-receivables' => 'c,r,u,d',
            $this->alias . '-loans' => 'c,r,u,d',
            $this->alias . '-receivables' => 'c,r,u,d',
            $this->alias . '-drops' => 'c,r,u,d',
        ]);
    }
}
