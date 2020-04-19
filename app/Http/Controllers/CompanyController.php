<?php

namespace App\Http\Controllers;

use App\Company;
use App\Ability;
use App\Role;
use App\CurrentCompany;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('web');
    }
    public function index()
    {
        if (empty(request('name'))) {
            $companies = \Auth::user()->companies()->latest()->get();
        } else {
            $companies = \Auth::user()->companies()->where('name', 'like', '%' . request('name') . '%')->get();
        }
        \Request::flash();
        return view('companies.index', ['companies' => $companies]);
    }
    public function show(Company $company)
    {
        return view('companies.show', ['company' => $company]);
    }
    public function create()
    {
        return view('companies.create');
    }
    public function store()
    {
        $this->validateCompany();
        $company = new Company(request(['name']));
        $company->code = substr(md5(microtime()), rand(0, 26), 6);
        $company->save();
        $user = \Auth::user();
        $company->employ($user);
        $approveJobApplication = Ability::firstOrCreate(['name' => 'approve_job_application']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'company_id' => $company->id]);
        $admin->allowTo($approveJobApplication);
        $user->assignRole($admin);
        $recordJournalEntries = Ability::firstOrCreate(['name' => 'record_journal_entries']);
        $staff = Role::firstOrCreate(['name' => 'staff', 'company_id' => $company->id]);
        $staff->allowTo($recordJournalEntries);
        $company->save();
        $approveJobApplication->save();
        $admin->save();
        $current_company = new CurrentCompany(['user_id' => $user->id, 'company_id' => $company->id]);
        $current_company->save();
        return redirect(route('home'))->with('status', 'Welcome to InnoBooks! You may now start adding items through the navigation pane.');
    }
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }
    public function update(Company $company)
    {
        $company->update($this->validateCompany());
        return redirect($company->path());
    }
    public function validateCompany()
    {
        return request()->validate([
            'name' => 'required'
        ]);
    }
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect(route('companies.index'));
    }
}
