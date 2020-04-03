<?php

namespace App\Http\Controllers;

use App\JournalEntry;
use App\Document;
use App\Account;
use App\SubsidiaryLedger;
use App\ReportLineItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('web');
    }
    public function index()
    {
        $company = \Auth::user()->current_company->company;
        if (empty(request('explanation')))
        {
            $journalEntries = JournalEntry::where('company_id', $company->id)->latest()->get();
        }
        else
        {
            $journalEntries = JournalEntry::where('company_id', $company->id)->where('explanation', 'like', '%' . request('explanation') . '%')->get();
        }
        if (\Route::currentRouteName() === 'journal_entries.index')
        {
            \Request::flash();
        }
        return view('journal_entries.index', compact('journalEntries'));
    }
    public function show(JournalEntry $journalEntry)
    {
        return view('journal_entries.show', compact('journalEntry'));
    }
    public function create()
    {
        $company = \Auth::user()->current_company->company;
        $documents = Document::where('company_id', $company->id)->latest()->get();
        $accounts = Account::where('company_id', $company->id)->latest()->get();
        $subsidiaryLedgers = SubsidiaryLedger::where('company_id', $company->id)->latest()->get();
        $reportLineItems = ReportLineItem::where('company_id', $company->id)->latest()->get();
        return view('journal_entries.create', compact('documents', 'accounts', 'subsidiaryLedgers', 'reportLineItems'));
    }
    public function store(Request $request)
    {
        dd (request());
        $messages = [
            'document_type_id.required' => 'The document type field is required.', 
            'document_type_id.exists' => 'The selected document type is invalid. Please choose among the recommended items.',
            'document_number.min' => 'The document number must be a positive number.'
        ];

        $validator = Validator::make($request->all(), [
            'date' => ['required', 'date'],
            'document_type_id' => ['required', 'exists:App\Document,id'],
            'document_number' => ['required', 'numeric', 'min:1'],
            'explanation' => ['required'],
            'postings.account_id' => ['sometimes', 'min:2'],
            'postings.account_id.*' => [
                'sometimes', 
                'required', 
                'exists:App\Account,id'
                //function ($attribute, $value, $fail) {
                //    if ($value === 'foo') {
                //        $fail($attribute.' is invalid.');
                //    }
                //}
            ],
            'postings.debit.*' => ['sometimes', 'numeric', 'min:0.01', 'nullable'],
            'postings.credit.*' => ['sometimes', 'numeric', 'min:0.01', 'nullable'],
            'postings.subsidiary_ledger_id.*' => ['sometimes', 'exists:App\SubsidiaryLedger,id', 'nullable'],
            'postings.report_line_item_id.*' => ['sometimes', 'exists:App\ReportLineItem,id', 'nullable']
        ], $messages);

        $debits = 0;
        $credits = 0;

        for ($x = 0; $x <= count(request()->postings['credit']); $x++){
            $debits = $debits + request(postings['debit'][$x]);
            $credits = $credits + request(postings['credit'][$x]);
            $validator->sometimes('postings.credit.$x', 'required|numeric|min:0.01', function ($input) {
                return $input->postings.debit.$x === null;
            });
        }

        $validator->after(function ($validator) {
            if ($debits === $credits) {
                $validator->errors()->add('postings', 'Total debits must equal total credits');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $company = \Auth::user()->current_company->company;
        $journalEntry = new JournalEntry([
            'company_id' => $company->id,
            'date' => request('date'),
            'document_type_id' => request('document_type_id'),
            'document_number' => request('document_number'),
            'explanation' => request('explanation')
        ]);
        $journalEntry->save();
        for ($row = 0; $row < count($posting['account_id']); $row++)
        {
            $posting = new Posting([
                'account_id' => $request['account_id'][$row],
                'debit' => $request['debit'][$row],
                'subsidiary_ledger_id' => $request['subsidiary_ledger_id'][$row],
                'report_line_item_id' => $request['report_line_item_id'][$row]
            ]);
            $posting->save();
            $journalEntry->post($posting);
        }
        return redirect(route('journal_entries.index'));
    }
}
