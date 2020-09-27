<?php

namespace App\Imports\Banking;

use App\Abstracts\Import;
use App\Http\Requests\Banking\Transaction as Request;
use App\Models\Banking\Transaction as Model;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterImport;
use App\Models\Banking\Account;

class Transactions extends Import implements WithEvents
{
    use RegistersEventListeners;

    public static function afterImport(AfterImport $event)
    {
        /**
         * Since model's 'saved' event isn't fired automatically in batch imports
         * we force calling it in order to complete transfer register
         * */
        $transactions = Model::whereDoesntHave('transfer')
            ->where('type','=','transfer')
            ->where('transfer_account','>','0')
            ->get();

        foreach ($transactions as $item) {
            $item->fireModelEvent('saved', false);
        }
    }

    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['account_id'] = $this->getAccountId($row);
        $row['category_id'] = $this->getCategoryId($row);
        $row['contact_id'] = $this->getContactId($row);
        $row['document_id'] = $this->getDocumentId($row);
        $row['amount'] = abs($row['amount']);

        if ($row['type'] == 'transfer') {
            $row['transfer_account'] = $this->getAccountIdFromName([
                'account_name' => $row['transfer_account'],
            ]);
        }

        if (!isset($row['currency_code'])) {
            $row['currency_code'] = Account::find($row['account_id'])->currency_code;
            $row['currency_rate'] = 1;
        }

        if (!isset($row['payment_method'])) {
            $row['payment_method'] = setting('default.payment_method');
        }

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
