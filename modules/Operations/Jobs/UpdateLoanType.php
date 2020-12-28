<?php

namespace Modules\Operations\Jobs;

use \App\Abstracts\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Modules\Operations\Models\LoanType;

class UpdateLoanType extends Job
{
    protected $loanType;
    protected $id;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->id = $request->id;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \DB::transaction(function () {
            $loanType = LoanType::where('company_id', $this->request->company_id)->where('id', $this->id)->first();
            $loanType = $loanType->update($this->request->all());
            return $loanType;
        });
    }
}
