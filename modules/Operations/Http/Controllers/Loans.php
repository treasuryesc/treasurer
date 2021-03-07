<?php

namespace Modules\Operations\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Abstracts\Http\FormRequest;

use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Setting\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Operations\Jobs\CreateLoan;
use Modules\Operations\Jobs\UpdateLoan;
use Modules\Operations\Models\Loan;
use Modules\Operations\Models\LoanStatus;
use Modules\Operations\Models\LoanType;
use Illuminate\Support\Facades\Validator;

use App\Models\Setting\Category;
use App\Models\Setting\Tax;

class Loans extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $loans = Loan::where('company_id', session('company_id'))->collect();
        return view('operations::loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $customers = Contact::where('company_id', session('company_id'))->where('type', 'customer')->orderBy('name')->pluck('name', 'id');
        $types = LoanType::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'id');
        $status = LoanStatus::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'id');
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', true)->orderBy('name')->pluck('name', 'id');
        return view('operations::loans.create', compact('customers', 'types', 'status','accounts'));
    }

    /**
     * Show the form for creating a new resource (step2).
     * @return Response
     */
    public function createStep2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|integer|exists:loan_types,id',
            'customer_id' => 'required|integer|exists:contacts,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->all());
        }

        try {
            $customer = Contact::find($request->customer_id);
            $type = LoanType::find($request->type_id);
            //Set additional_fields
            $additional_fields = [];
            if ($type->attributes_schema) {
                if(count(json_decode($type->attributes_schema, true)) > 0) {
                    $required = json_decode($type->attributes_schema)->required;
                    foreach (json_decode($type->attributes_schema)->properties as $attribute_schema => $attribute_type) {
                        in_array($attribute_schema, $required) ? $attribute_required = 'required' : $attribute_required = '';
                        $additional_fields[] = [
                            "attribute" => $attribute_schema,
                            "type" => $attribute_type->type,
                            "required" => $attribute_required,
                        ];
                    }
                }
            }
            $status = LoanStatus::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'id');
            $accounts = Account::where('company_id', session('company_id'))->where('enabled', true)->orderBy('name')->pluck('name', 'id');


            $categories = Category::item()->enabled()->orderBy('name')->pluck('name', 'id');
            $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');
            $currency = Currency::where('code', setting('default.currency', 'USD'))->first();

            return view('operations::loans.create-step2', compact('customer', 'type', 'additional_fields', 'status','accounts', 'categories', 'taxes', 'currency'));
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->back();
        }

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator_rules = [];
        $validator_rules['type_id'] = 'required|integer|exists:loan_types,id';
        $validator_rules['customer_id'] = 'required|integer|exists:contacts,id';
        $validator_rules['contract'] = 'required|integer';
        $validator_rules['status_id'] = 'required|integer|exists:loan_status,id';
        $validator_rules['account_id'] = 'required|integer|exists:accounts,id';
        $validator_rules['amortizations'] = 'required|integer';
        $validator_rules['interest_rate'] = 'required|numeric';
        $validator_rules['amount'] = 'required|numeric';
        $validator_rules['due'] = 'required|numeric';
        $validator_rules['contract_at'] = 'required|date';
        $validator_rules['lent_at'] = 'nullable|date';
        $validator_rules['last_at'] = 'nullable|date';
        $validator_rules['reference'] = 'required';
        $validator_rules['index'] = 'required';
        //CHECK ADDITIONAL ATTRIBUTES (FROM SCHEMA)
        $loan_type = LoanType::findOrFail($request->type_id);
        if ($loan_type->attributes_schema) {
            if(count(json_decode($loan_type->attributes_schema, true)) > 0) {
                $required = json_decode($loan_type->attributes_schema)->required;
                foreach (json_decode($loan_type->attributes_schema)->properties as $attribute_schema => $attribute_type) {
                    //REQUIRED?
                    in_array($attribute_schema, $required) ? $attribute_required = 'required' : $attribute_required = 'nullable';
                    //STRING/NUMERIC?
                    switch ($attribute_type->type) {
                        case 'string':
                            $attribute_required .= '|' . $attribute_type->type;
                            break;
                        case 'number':
                            $attribute_required .= '|numeric';
                            break;
                    }
                    $validator_rules[$attribute_schema] = $attribute_required;
                }
            }
        }
        $validator = Validator::make($request->all(), $validator_rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $response = $this->ajaxDispatch(new CreateLoan($request));

        $response['redirect'] = route('operations.loans.create');

        if ($response['success']) {
            $message = trans('messages.success.added', ['type' => trans('operations::general.loan')]);

            flash($message)->success();
            return redirect($response['redirect'])->with('Sucesso', $message) ;
        } else {
            $message = $response['message'];

            flash($message)->error();
            return redirect($response['redirect'])->withInput($request->all())->with('Erro', $message);
        }

        $message = 'Erro ao inserir registro';
        flash($message)->error();
        return redirect($response['redirect'])->withInput($request->all())->with('Erro', $message);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('operations::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $loan_id
     * @return Response
     */
    public function edit($loan_id)
    {
        try {
            $loan = Loan::with('type')->with('customer')->findOrFail($loan_id);
            $attributes = json_decode($loan->attributes, true);
            //Set additional_fields
            $additional_fields = [];
            if ($loan->type->attributes_schema) {
                if(count(json_decode($loan->type->attributes_schema, true)) > 0) {
                    $required = json_decode($loan->type->attributes_schema)->required;
                    foreach (json_decode($loan->type->attributes_schema)->properties as $attribute_schema => $attribute_type) {
                        in_array($attribute_schema, $required) ? $attribute_required = 'required' : $attribute_required = '';
                        $additional_fields[] = [
                            "attribute" => $attribute_schema,
                            "type" => $attribute_type->type,
                            "required" => $attribute_required,
                            "value" => $attributes[$attribute_schema],
                        ];
                    }
                }
            }

            $status = LoanStatus::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'id');
            $accounts = Account::where('company_id', session('company_id'))->where('enabled', true)->orderBy('name')->pluck('name', 'id');
            $categories = Category::item()->enabled()->orderBy('name')->pluck('name', 'id');
            $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');
            $currency = Currency::where('code', setting('default.currency', 'USD'))->first();

            return view('operations::loans.edit', compact('loan', 'additional_fields', 'status','accounts', 'categories', 'taxes', 'currency'));
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $loan_id
     * @return Response
     */
    public function update(Request $request, $loan_id)
    {
        $validator_rules = [];
        $validator_rules['type_id'] = 'required|integer|exists:loan_types,id';
        $validator_rules['customer_id'] = 'required|integer|exists:contacts,id';
        $validator_rules['contract'] = 'required|integer';
        $validator_rules['status_id'] = 'required|integer|exists:loan_status,id';
        $validator_rules['account_id'] = 'required|integer|exists:accounts,id';
        $validator_rules['amortizations'] = 'required|integer';
        $validator_rules['interest_rate'] = 'required|numeric';
        $validator_rules['amount'] = 'required|numeric';
        $validator_rules['due'] = 'required|numeric';
        $validator_rules['contract_at'] = 'required|date';
        $validator_rules['lent_at'] = 'nullable|date';
        $validator_rules['last_at'] = 'nullable|date';
        $validator_rules['reference'] = 'required';
        $validator_rules['index'] = 'required';
        //CHECK ADDITIONAL ATTRIBUTES (FROM SCHEMA)
        $loan_type = LoanType::findOrFail($request->type_id);
        if ($loan_type->attributes_schema) {
            if(count(json_decode($loan_type->attributes_schema, true)) > 0) {
                $required = json_decode($loan_type->attributes_schema)->required;
                foreach (json_decode($loan_type->attributes_schema)->properties as $attribute_schema => $attribute_type) {
                    //REQUIRED?
                    in_array($attribute_schema, $required) ? $attribute_required = 'required' : $attribute_required = 'nullable';
                    //STRING/NUMERIC?
                    switch ($attribute_type->type) {
                        case 'string':
                            $attribute_required .= '|' . $attribute_type->type;
                            break;
                        case 'number':
                            $attribute_required .= '|numeric';
                            break;
                    }
                    $validator_rules[$attribute_schema] = $attribute_required;
                }
            }
        }
        $validator = Validator::make($request->all(), $validator_rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $response = $this->ajaxDispatch(new UpdateLoan($request, $loan_id));

        $response['redirect'] = route('operations.loans.edit', ['loan_id' => $loan_id]);

        if ($response['success']) {
            $message = trans('messages.success.updated', ['type' => trans('operations::general.loan')]);

            flash($message)->success();
            return redirect($response['redirect'])->with('Sucesso', $message) ;
        } else {
            $message = $response['message'];

            flash($message)->error();
            return redirect($response['redirect'])->withInput($request->all())->with('Erro', $message);
        }

        $message = 'Erro ao inserir registro';
        flash($message)->error();
        return redirect($response['redirect'])->withInput($request->all())->with('Erro', $message);

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
