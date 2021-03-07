<?php

namespace Modules\Operations\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Abstracts\Http\FormRequest;

use Modules\Operations\Requests\LoanTypeRequest;
use Modules\Operations\Requests\ReceivableTypeRequest;

use Debugbar;
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
     * @param LoanTypeRequest $request
     * @return Response
     */
    public function loanTypesStore(LoanTypeRequest $request)
    {
        $response = $this->ajaxDispatch(new CreateLoanType($request));
        $response['redirect'] = route('operations.settings.loan-types.create');

        if ($response['success']) {
            $message = trans('messages.success.added', ['type' => trans('operations::general.loan-type')]);

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
     * Show the form for editing the specified resource.
     *
     * @param  Int  $loantype_id
     *
     * @return Response
     */
    public function loanTypesEdit($loantype_id)
    {
        $loantype = LoanType::where('company_id', session('company_id'))->where('id', $loantype_id)->firstOrFail();
        return view('operations::settings.loan-types.edit', compact('loantype'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  LoanTypeRequest $request
     * @param  Int $id
     *
     * @return Response
     */
    public function loanTypesUpdate(LoanTypeRequest $request, $loantype_id)
    {
        $response = $this->ajaxDispatch(new UpdateLoanType($request, $loantype_id));
        $response['redirect'] = route('operations.settings.loan-types.edit', ['loantype_id' => $loantype_id]);

        if ($response['success']) {
            $response['redirect'] = route('operations.settings.loan-types.edit', ['loantype_id' => $request->id]);
            $message = trans('messages.success.updated', ['type' => trans('operations::general.loan-type')]);

            flash($message)->success();
            return redirect($response['redirect'])->with('Sucesso', $message) ;
        } else {
            $message = $response['message'];

            flash($message)->error();
            return redirect($response['redirect'])->withInput($request->all())->with('Erro', $message);
        }

        $message = 'Erro ao atualizar registro';
        flash($message)->error();
        return redirect($response['redirect'])->withInput($request->all())->with('Erro', $message);
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
     * @param ReceivableTypeRequest $request
     * @return Response
     */
    public function receivableTypesStore(ReceivableTypeRequest $request)
    {
        $response = $this->ajaxDispatch(new CreateReceivableType($request));
        $response['redirect'] = route('operations.settings.receivable-types.create');

        if ($response['success']) {
            $message = trans('messages.success.added', ['type' => trans('operations::general.receivable-type')]);

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
     * Show the form for editing the specified resource.
     *
     * @param  Int  $receivabletype_id
     *
     * @return Response
     */
    public function receivableTypesEdit($receivabletype_id)
    {
        $receivabletype = ReceivableType::where('company_id', session('company_id'))->where('id', $receivabletype_id)->first();
        return view('operations::settings.receivable-types.edit', compact('receivabletype'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ReceivableTypeRequest $request
     * @param  Int $id
     *
     * @return Response
     */
    public function receivableTypesUpdate(ReceivableTypeRequest $request, $receivabletype_id)
    {
        $response = $this->ajaxDispatch(new UpdateReceivableType($request, $receivabletype_id));
        $response['redirect'] = route('operations.settings.receivable-types.edit', ['receivabletype_id' => $receivabletype_id]);

        if ($response['success']) {
            $response['redirect'] = route('operations.settings.receivable-types.edit', ['receivabletype_id' => $request->id]);
            $message = trans('messages.success.updated', ['type' => trans('operations::general.receivable-type')]);

            flash($message)->success();
            return redirect($response['redirect'])->with('Sucesso', $message) ;
        } else {
            $message = $response['message'];

            flash($message)->error();
            return redirect($response['redirect'])->withInput($request->all())->with('Erro', $message);
        }

        $message = 'Erro ao atualizar registro';
        flash($message)->error();
        return redirect($response['redirect'])->withInput($request->all())->with('Erro', $message);
    }
}
