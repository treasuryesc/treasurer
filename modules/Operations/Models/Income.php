<?php

namespace Modules\Operations\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Common\Company;
use App\Models\Banking\Transaction;

class Income extends Model
{
    use SoftDeletes;

    protected $table = 'receivables';
    protected $tenantable = false;
    protected $dates = ['paid_at', 'credit_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['company_id', 'receivable_id', 'account_id', 'transaction_id', 'paid', 'paid_at',
        'credit_at', 'reference', 'notes', ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['id', 'company_id', 'receivable_id', 'paid_at', 'credit_at'];


    public function Company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function Receivable() {
        return $this->belongsTo(Receivable::class, 'receivable_id');
    }

    public function Transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
