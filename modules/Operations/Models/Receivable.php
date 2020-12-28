<?php

namespace Modules\Operations\Models;

use App\Abstracts\Model;
use App\Models\Common\Contact;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Common\Company;

class Receivable extends Model
{
    use SoftDeletes;

    protected $table = 'receivables';
    protected $tenantable = false;
    protected $dates = ['due_at', 'paid_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['company_id', 'loan_id', 'sequence', 'customer_id', 'type_id', 'status_id', 'principal',
        'interest', 'amount', 'due', 'due_at', 'paid_at', 'reference', 'notes', 'attributes', ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['id', 'loan_id', 'status', 'due_at', 'paid_at'];


    public function Company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function Loan() {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function Contact() {
        return $this->belongsTo(Contact::class, 'customer_id');
    }

    public function Type() {
        return $this->belongsTo(ReceivableType::class, 'type_id');
    }

    public function Status() {
        return $this->belongsTo(ReceivableStatus::class, 'status_id');
    }
}
