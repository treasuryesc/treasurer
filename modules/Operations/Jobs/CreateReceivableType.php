<?php

namespace Modules\Operations\Jobs;

use \App\Abstracts\Job;
//use Illuminate\Bus\Queueable;
//use Illuminate\Queue\SerializesModels;
//use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Foundation\Bus\Dispatchable;

use Modules\Operations\Models\ReceivableType;

class CreateReceivableType extends Job
{
//    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
