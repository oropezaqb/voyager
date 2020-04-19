<?php

namespace App\Http\Controllers;

use App\Company;
use App\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
        $this->middleware('web');
    }
    public function index()
    {
        $company = \Auth::user()->current_company->company;
        if (empty(request('title'))) {
            $accounts = Account::where('company_id', $company->id)->latest()->get();
        } else {
            $accounts = Account::where('company_id', $company->id)->where('title', 'like', '%' . request('title') . '%')->get();
        }
        if (\Route::currentRouteName() === 'accounts.index') {
            \Request::flash();
        }
        return view('accounts.index', compact('accounts'));
    }
    public function show(Account $account)
    {
        return view('accounts.show', compact('account'));
    }
    public function create()
    {
        if (\Route::currentRouteName() === 'accounts.create') {
            \Request::flash();
        }
        return view('accounts.create');
    }
    public function store()
    {
        $this->validateAccount();
        $company = \Auth::user()->current_company->company;
        $subsidiary_ledger = true;
        if (empty(request('subsidiary_ledger'))) {
            $subsidiary_ledger = false;
        }
        $account = new Account([
            'company_id' => $company->id,
            'number' => request('number'),
            'title' => request('title'),
            'type' => request('type'),
            'subsidiary_ledger' => $subsidiary_ledger
        ]);
        $account->save();
        return redirect(route('accounts.index'));
    }
    public function edit(Account $account)
    {
        if (\Route::currentRouteName() === 'accounts.edit') {
            \Request::flash();
        }
        return view('accounts.edit', compact('account'));
    }
    public function update(Account $account)
    {
        $this->validateAccount();
        $subsidiary_ledger = true;
        if (empty(request('subsidiary_ledger'))) {
            $subsidiary_ledger = false;
        }
        $account->update([
            'number' => request('number'),
            'title' => request('title'),
            'type' => request('type'),
            'subsidiary_ledger' => $subsidiary_ledger
        ]);
        return redirect($account->path());
    }
    public function validateAccount()
    {
        return request()->validate([
            'number' => 'required',
            'title' => 'required',
            'type' => 'required'
        ]);
    }
    public function destroy(Account $account)
    {
        $account->delete();
        return redirect(route('accounts.index'));
    }
}
