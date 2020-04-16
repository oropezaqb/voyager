<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('company');

Route::resources([
    'companies' => 'CompanyController',
    'applications' => 'ApplicationController',
    'current_company' => 'CurrentCompanyController',
    'accounts' => 'AccountController',
    'documents' => 'DocumentController',
    'subsidiary_ledgers' => 'SubsidiaryLedgerController',
    'report_line_items' => 'ReportLineItemController',
    'journal_entries' => 'JournalEntryController',
    'postings' => 'PostingController',
    'roles' => 'RoleController',
    'abilities' => 'AbilityController',
    'queries' => 'QueryController',
]);

Route::get('/company_users', 'CompanyUserController@index')->name('company_users.index');
Route::post('/company_users', 'CompanyUserController@store')->name('company_users.store');
Route::get('/company_users/add', 'CompanyUserController@add')->name('company_users.add');
Route::get('/company_users/{company_user}', 'CompanyUserController@show')->name('company_users.show');
Route::get('/company_users/{company_user}/edit', 'CompanyUserController@edit')->name('company_users.edit');
Route::put('/company_users/{company_user}', 'CompanyUserController@update')->name('company_users.update');

Route::post('queries/{query}/run', 'QueryController@run')->name('queries.run');
Route::post('reports/{query}/screen', 'ReportController@screen')->name('reports.screen');
Route::post('reports/{query}/pdf', 'ReportController@pdf')->name('reports.pdf');
Route::post('reports/{query}/csv', 'ReportController@csv')->name('reports.csv');
Route::post('reports/{query}/run', 'ReportController@run')->name('reports.run');
Route::post('reports/trial_balance', 'ReportController@trial_balance')->name('reports.trial_balance');
Route::get('/reports', 'ReportController@index')->name('reports.index');
