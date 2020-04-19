<?php

namespace App\Http\Controllers;

use App\Company;
use App\CurrentCompany;
use Illuminate\Http\Request;

class CurrentCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('web');
    }
    public function index()
    {
        $current_company = \Auth::user()->current_company;
        return view('current_company.index', ['current_company' => $current_company]);
    }
    public function show(CurrentCompany $current_company)
    {
        return view('current_company.show', ['current_company' => $current_company]);
    }
    public function create()
    {
        $companies = \Auth::user()->companies;
        return view('current_company.create', ['companies' => $companies]);
    }
    public function store()
    {
        $this->validateCurrentCompany();
        $company = Company::where('id', request('company_id'))->firstOrFail();
        $user = \Auth::user();
        if (empty($user->current_company)) {
            $current_company = new CurrentCompany(['user_id' => $user->id, 'company_id' => $company->id]);
            $current_company->save();
            return redirect(route('home'))->with('status', 'Welcome! You may now start adding items through the navigation pane.');
        } else {
            $companies = \Auth::user()->companies;
            \Request::flash();
            $message = "Cannot add another company as current. You may update your current company instead.";
            return view('current_company.create', ['message' => $message, 'companies' => $companies]);
        }
    }
    public function edit(CurrentCompany $current_company)
    {
        $companies = \Auth::user()->companies()->latest()->get();
        return view('current_company.edit', ['current_company' => $current_company, 'companies' => $companies]);
    }
    public function update(CurrentCompany $current_company)
    {
        $current_company->update($this->validateCurrentCompany());
        return redirect($current_company->path());
    }
    public function validateCurrentCompany()
    {
        return request()->validate([
            'company_id' => 'required'
        ]);
    }
    public function destroy(CurrentCompany $current_company)
    {
        $current_company->delete();
        return redirect(route('current_company.index'));
    }
}
