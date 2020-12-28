<?php

namespace Modules\Operations\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Abstracts\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Operations\Models\LoanType;
use Modules\Operations\Models\ReceivableType;

use Modules\Operations\Jobs\CreateLoanType;
use Modules\Operations\Jobs\UpdateLoanType;
use Modules\Operations\Jobs\CreateReceivableType;
use Modules\Operations\Jobs\UpdateReceivableType;

class Settings extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('operations::settings.index');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function loanTypes()
    {
        $loantypes = LoanType::where('company_id', session('company_id'))->collect();
        return view('operations::settings.loan-types.index', compact('loantypes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function loanTypesCreate()
    {
        return view('operations::settings.loan-types.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function loanTypesStore(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateLoanType($request));

        if ($response['success']) {
            $response['redirect'] = route('operations.settings.loan-types.index');
            $message = trans('messages.success.added', ['type' => trans_choice('general.operations.settings.loan-type', 1)]);
            flash($message)->success();
        } else {
            $response['redirect'] = route('operations.settings.loan-types.index');
            $message = $response['message'];
            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Int  $id
     *
     * @return Response
     */
    public function loanTypesEdit($id)
    {
        $loantype = LoanType::where('company_id', session('company_id'))->where('id', $id)->first();
        return view('operations::settings.loan-types.edit', compact('loantype'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function loanTypesUpdate(Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateLoanType($request));

        if ($response['success']) {
            $response['redirect'] = route('operations.settings.loan-types.index');
            $message = trans('messages.success.updated', ['type' => $request->name]);
            flash($message)->success();
        } else {
            $response['redirect'] = route('operations.settings.loan-types.edit', $request->id);
            $message = $response['message'];
            flash($message)->error();
        }

        return response()->json($response);
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function receivableTypes()
    {
        $receivabletypes = ReceivableType::where('company_id', session('company_id'))->collect();
        return view('operations::settings.receivable-types.index', compact('receivabletypes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function receivableTypesCreate()
    {
        return view('operations::settings.receivable-types.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function receivableTypesStore(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateReceivableType($request));

        if ($response['success']) {
            $response['redirect'] = route('operations.settings.receivable-types.index');
            $message = trans('messages.success.added', ['type' => trans_choice('general.operations.settings.receivable-type', 1)]);
            flash($message)->success();
        } else {
            $response['redirect'] = route('operations.settings.receivable-types.index');
            $message = $response['message'];
            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Int  $id
     *
     * @return Response
     */
    public function receivableTypesEdit($id)
    {
        $receivabletype = ReceivableType::where('company_id', session('company_id'))->where('id', $id)->first();
        return view('operations::settings.receivable-types.edit', compact('receivabletype'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function receivableTypesUpdate(Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateReceivableType($request));

        if ($response['success']) {
            $response['redirect'] = route('operations.settings.receivable-types.index');
            $message = trans('messages.success.updated', ['type' => $request->name]);
            flash($message)->success();
        } else {
            $response['redirect'] = route('operations.settings.receivable-types.edit', $request->id);
            $message = $response['message'];
            flash($message)->error();
        }

        return response()->json($response);
    }
}
