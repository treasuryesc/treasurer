<?php

namespace Modules\Operations\Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;

class OperationsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        //
        Model::reguard();
    }
}
