<?php

namespace Modules\Operations\Models;

use App\Abstracts\Model;
use App\Models\Common\Company;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanStatus extends Model
{
    use SoftDeletes;

    protected $table = 'loan_status';
    protected $tenantable = false;
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['company_id', 'id', 'name'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['id', 'name'];

    public function Company() {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
