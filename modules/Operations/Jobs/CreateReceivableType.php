<?php

namespace Modules\Operations\Jobs;

use \App\Abstracts\Job;
use Modules\Operations\Models\ReceivableType;

class CreateReceivableType extends Job
{
    protected $receivableType;

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
            $this->receivableType = ReceivableType::create($this->request->all());
        });

        return $this->receivableType;
    }
}
