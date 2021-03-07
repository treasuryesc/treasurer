<?php

namespace Modules\Operations\Jobs;

use \App\Abstracts\Job;
use Debugbar;

use Modules\Operations\Models\LoanType;

class CreateLoanType extends Job
{
    protected $loanType;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \DB::transaction(function () {
                $this->loanType = LoanType::create($this->request->all());
            });
        } catch (\Http\Client\Exception $e) {
            Debugbar::addMessage($e->getMessage());
            return false;
        }
        return $this->loanType;
    }
}
