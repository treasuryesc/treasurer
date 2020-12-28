<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use App\Models\Setting\Category;
use App\Models\Setting\Tax;
use Illuminate\Database\Seeder;
use Modules\Operations\Models\LoanStatus;
use Modules\Operations\Models\ReceivableStatus;

class SampleData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();

        config(['mail.default' => 'array']);

        $count = (int) $this->command->option('count');
        $small_count = ($count <= 10) ? $count : 10;

        $this->command->info('Creating sample data...');

        $bar = $this->command->getOutput()->createProgressBar(7);
        $bar->setFormat('verbose');

        $bar->start();

        factory(Contact::class, $count)->create();
        $bar->advance();

        factory(Category::class, $count)->create();
        $bar->advance();

        factory(Tax::class, $small_count)->states('enabled')->create();
        $bar->advance();

        factory(Item::class, $count)->create();
        $bar->advance();

        factory(Account::class, $small_count)->create();
        $bar->advance();

        factory(Bill::class, $count)->create();
        $bar->advance();

        factory(Invoice::class, $count)->create();
        $bar->advance();

        $bar->finish();

        $this->command->info('');
        $this->command->info('Sample data created.');

        //ADICIONAR LOANSTATUS e RECEIVABLESTATUS
        LoanStatus::create([
            'company_id' => 1,
            'id' => 1,
            'name' => 'Aberto',
            'created_at' => now(),
        ]);
        LoanStatus::create([
            'company_id' => 1,
            'id' => 2,
            'name' => 'Liberado',
            'created_at' => now(),
        ]);
        LoanStatus::create([
            'company_id' => 1,
            'id' => 3,
            'name' => 'Encerrado',
            'created_at' => now(),
        ]);

        ReceivableStatus::create([
            'company_id' => 1,
            'id' => 1,
            'name' => 'Aberto',
            'created_at' => now(),
        ]);
        ReceivableStatus::create([
            'company_id' => 1,
            'id' => 2,
            'name' => 'Atrasado',
            'created_at' => now(),
        ]);
        ReceivableStatus::create([
            'company_id' => 1,
            'id' => 3,
            'name' => 'Pago',
            'created_at' => now(),
        ]);

        Model::unguard();
    }
}
