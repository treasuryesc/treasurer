<?php

namespace Modules\Operations\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Abstracts\Http\FormRequest;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Setting\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Operations\Models\Loan;
use Modules\Operations\Models\LoanStatus;
use Modules\Operations\Models\LoanType;

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
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
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
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('operations::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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
