<?php

namespace App\Http\Controllers;

use App\SubsidiaryLedger;
use Illuminate\Http\Request;

class SubsidiaryLedgerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
        $this->middleware('web');
    }
    public function index()
    {
        //if (!empty(\Auth::user()->current_company->company))
        //{

        $company = \Auth::user()->current_company->company;
        if (empty(request('name')))
        {
            $subsidiary_ledgers = SubsidiaryLedger::where('company_id', $company->id)->latest()->get();
        }
        else
        {
            $subsidiary_ledgers = SubsidiaryLedger::where('company_id', $company->id)->where('name', 'like', '%' . request('name') . '%')->get();
        }
        if (\Route::currentRouteName() === 'subsidiary_ledgers.index')
        {
            \Request::flash();
        }
        return view('subsidiary_ledgers.index', compact('subsidiary_ledgers'));

        //}
        //else
        //{
        //    return redirect(route('current_company.index'));
        //}
    }
    public function show(SubsidiaryLedger $subsidiary_ledger)
    {
        return view('subsidiary_ledgers.show', compact('subsidiary_ledger'));
    }
    public function create()
    {
        if (\Route::currentRouteName() === 'subsidiary_ledgers.create')
        {
            \Request::flash();
        }
        return view('subsidiary_ledgers.create');
    }
    public function store()
    {
        $this->validateSubsidiaryLedger();
        $company = \Auth::user()->current_company->company;
        $subsidiary_ledger = new SubsidiaryLedger([
            'company_id' => $company->id,
            'number' => request('number'),
            'name' => request('name')
        ]);
        $subsidiary_ledger->save();
        return redirect(route('subsidiary_ledgers.index'));
    }
    public function edit(SubsidiaryLedger $subsidiary_ledger)
    {
        if (\Route::currentRouteName() === 'subsidiary_ledgers.edit')
        {
            \Request::flash();
        }
        return view('subsidiary_ledgers.edit', compact('subsidiary_ledger'));
    }
    public function update(SubsidiaryLedger $subsidiary_ledger)
    {
        $subsidiary_ledger->update($this->validateSubsidiaryLedger());
        return redirect($subsidiary_ledger->path());
    }
    public function validateSubsidiaryLedger()
    {
        return request()->validate([
            'number' => 'required',
            'name' => 'required'
        ]);
    }
    public function destroy(SubsidiaryLedger $subsidiary_ledger)
    {
        $subsidiary_ledger->delete();
        return redirect(route('subsidiary_ledgers.index'));
    }
}
