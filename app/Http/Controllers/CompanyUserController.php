<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Company;
use App\Application;
use App\CompanyUser;
use Illuminate\Http\Request;

class CompanyUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
        $this->middleware('web');
    }
    public function index()
    {
        if(empty(request('name')))
        {
            $company_users = \Auth::user()->current_company->company->users;
        }
        else
        {
            $company_users = \Auth::user()->current_company->company->users()->where('name', 'like', '%' . request('name') . '%')->get();
        }
        \Request::flash();
        return view('company_users.index', ['company_users' => $company_users]);
    }
    public function show($id)
    {
        $user = User::find($id);
        $company = \Auth::user()->current_company->company()->firstOrFail();
        $roles = $user->roles()->where('company_id', $company->id)->get();
        return view('company_users.show', compact('user', 'roles'));
    }
    public function add()
    {
        $company = \Auth::user()->current_company->company;
        $applications = $company->applications->where('company_id', $company->id);
        return view('company_users.add', ['applications' => $applications]);
    }
    public function store()
    {
        $this->validateCompanyUser();
        $application = Application::where('id', request('application_id'))->firstOrFail();
        $company = $application->company;
        $user = $application->user;
        $company->employ($user);
        $application->delete();
        return redirect(route('company_users.index'));
    }
    public function edit($id)
    {
        $user = User::find($id);
        $company = \Auth::user()->current_company->company()->firstOrFail();
        $roles = Role::where('company_id', $company->id)->get();
        $checked_roles = $user->roles()->where('company_id', $company->id)->get();
        return view('company_users.edit', compact('user', 'roles', 'checked_roles'));
    }
    public function update(Request $request)
    {
        $user = User::find(request('id'));
        $roles = $request->input('role');
        $user->roles()->detach();
        if (isset($roles))
        {
            foreach ($roles as $role)
            {
                $user->assignRole(Role::find($role));
            }
        }
        return redirect(route('company_users.show', request('id')));
    }
}
