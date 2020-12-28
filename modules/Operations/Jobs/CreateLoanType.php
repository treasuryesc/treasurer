<?php

namespace Modules\Operations\Jobs;

use \App\Abstracts\Job;
//use Illuminate\Bus\Queueable;
//use Illuminate\Queue\SerializesModels;
//use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Foundation\Bus\Dispatchable;

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
        \DB::transaction(function () {
            $this->loanType = LoanType::create($this->request->all());
        });

        return $this->loanType;
    }
}
