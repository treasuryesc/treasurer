<?php

namespace Modules\Operations\Jobs;

use \App\Abstracts\Job;
use Modules\Operations\Models\LoanType;

class UpdateLoanType extends Job
{
    protected $loanType;
    protected $loantype_id;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request, $loantype_id)
    {
        $this->loantype_id = $loantype_id;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            $loanType = LoanType::where(['company_id' => $this->request->company_id, 'id' => $this->loantype_id]);
            $loanType = $loanType->update($this->request->only(['id', 'name', 'attributes_schema']));
            return $loanType;
    }
}
