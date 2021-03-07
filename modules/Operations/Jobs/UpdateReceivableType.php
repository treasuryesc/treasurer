<?php

namespace Modules\Operations\Jobs;

use \App\Abstracts\Job;
use Modules\Operations\Models\ReceivableType;

class UpdateReceivableType extends Job
{
    protected $receivableType;
    protected $receivabletype_id;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request, $receivabletype_id)
    {
        $this->receivabletype_id = $receivabletype_id;
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
            $receivableType = ReceivableType::where('company_id', $this->request->company_id)->where('id', $this->receivabletype_id)->firstOrFail();
            $receivableType = $receivableType->update($this->request->all());
            return $receivableType;
        });
    }
}
