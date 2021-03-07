<?php

namespace Modules\Operations\Jobs;

use \App\Abstracts\Job;
use Debugbar;

use Modules\Operations\Models\Loan;
use Modules\Operations\Models\LoanType;

class CreateLoan extends Job
{
    protected $loan;

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
                //MOUNT ADDITIONAL ATTRIBUTE (attribute_schema)
                $loan_type = LoanType::findOrFail($this->request->type_id);
                $json_attributes = [];
                if ($loan_type->attributes_schema) {
                    if(count(json_decode($loan_type->attributes_schema, true)) > 0) {
                        foreach (json_decode($loan_type->attributes_schema)->properties as $attribute_schema => $attribute_type) {
                            $json_attributes[$attribute_schema] = $this->request[$attribute_schema];
                        }
                    }
                }
                $this->loan = Loan::create($this->request->all() + ['attributes' => json_encode($json_attributes)]);
            });
        } catch (\Http\Client\Exception $e) {
            Debugbar::addMessage($e->getMessage());
            return false;
        }
        return $this->loan;
    }
}
