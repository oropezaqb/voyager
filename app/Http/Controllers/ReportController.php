<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use App\Ability;
use Illuminate\Support\Facades\Validator;
use App\EPMADD\DbAccess;
use App\EPMADD\Report;
use App\Query;
use Illuminate\Support\Facades\Storage;
use DateTime;
use Dompdf\Dompdf;

class ReportController extends Controller
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
        $queries = Query::where('company_id', $company->id)->latest()->get();
        return view('reports.index', compact('queries'));
    }
    public function pdf(Query $query)
    {
        if (stripos($query->query, 'file ') === 0) {
            return redirect(route('queries.index'))->with('status', 'Cannot run file reports here.');
        }
        else
        {
            $db = new DbAccess();
            $stmt = $db->query($query->query);
            $r = new Report();
            $url = $r->pdf($query->title, $stmt);
            return view('reports.pdf', compact('url'));
        }
    }
    public function csv(Query $query)
    {
        if (stripos($query->query, 'file ') === 0) {
            return redirect(route('queries.index'))->with('status', 'Cannot run file reports here.');
        }
        else
        {
            $db = new DbAccess();
            $stmt = $db->query($query->query);
            $r = new Report();
            $url = $r->csv($stmt);
            return view('reports.csv', compact('url'));
        }
    }
    public function screen(Query $query)
    {
        if (stripos($query->query, 'file ') === 0) {
            return redirect(route('reports.index'))->with('status', 'Cannot run file reports here.');
        }
        else
        {
            $db = new DbAccess();
            $stmt = $db->query($query->query);
            $ncols = $stmt->columnCount();
            $headings = array();
            for ($i = 0; $i < $ncols; $i++) {
                $meta = $stmt->getColumnMeta($i);
                $headings[] = $meta['name'];
            }
            return view('reports.screen', compact('query', 'stmt', 'headings'));
        }
    }
    public function trial_balance () {
        return view('reports.trial_balance');
    }
    public function run (Request $request) {
        $query = new Query();
        $query->title = "Trial Balance";
        $date = DateTime::createFromFormat('Y-m-d', "$request->date");
        $query->date = 'As of ' . date_format($date,'M d, Y');
        $db = new DbAccess();
        $stmt = $db->query("SELECT accounts.number, accounts.title, SUM(debit) debit FROM journal_entries RIGHT JOIN journal_entry_posting ON journal_entries.id = journal_entry_posting.journal_entry_id RIGHT JOIN postings ON journal_entry_posting.posting_id = postings.id RIGHT JOIN accounts ON postings.account_id = accounts.id WHERE accounts.company_id=" . auth()->user()->current_company->company->id . " AND journal_entries.date<=". "'" . request('date'). "'" ." GROUP BY accounts.number ORDER BY accounts.number;");
        $ncols = $stmt->columnCount();
        $headings = array('Account Title', 'Debit', 'Credit');
        return view('reports.trial_balance.screen', compact('query', 'stmt', 'headings'));
    }
}
