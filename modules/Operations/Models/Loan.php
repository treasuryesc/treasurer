<?php

namespace Modules\Operations\Models;

use App\Abstracts\Model;
use App\Models\Banking\Account;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Common\Company;
use App\Models\Common\Contact;

class Loan extends Model
{
    use SoftDeletes;

    protected $table = 'loans';
    protected $tenantable = false;
    protected $dates = ['contract_at', 'lent_at', 'last_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['company_id', 'customer_id', 'contract', 'type_id', 'status_id', 'account_id',
        'amortizations', 'interest_rate', 'amount', 'due', 'contract_at', 'lent_at', 'last_at', 'parent_id',
        'reference', 'index', 'notes', 'attributes', ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['id', 'contract'];


    public function Company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function Customer() {
        return $this->belongsTo(Contact::class, 'customer_id');
    }

    public function Type() {
        return $this->belongsTo(LoanType::class, 'type_id');
    }

    public function Status() {
        return $this->belongsTo(LoanStatus::class, 'account_id');
    }

    public function Account() {
        return $this->belongsTo(Account::class, 'status_id');
    }

    public function Parent() {
        return $this->belongsTo(Loan::class, 'parent_id');
    }
}
