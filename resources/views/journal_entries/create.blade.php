@extends('layouts.app')
@section('content')
    @include('layouts.nav')
    <div class="col-md-10">
        <div class="card">
            <div class="card-header font-weight-bold">Company: {{ \Auth::user()->current_company->company->name }} (Add a New Journal Entry)</div>
            <div class="card-body">
                <div id="wrapper">
                    <div id="page" class="container">
                        <div class="content">
                            <form method="POST" action="/journal_entries">
                                @csrf
                                <datalist id="account_ids">
                                    @foreach ($accounts as $account)
                                        <option data-value={{ $account->id }}>{{ $account->title }} ({{ $account->number }})</option>
                                    @endforeach
                                </datalist>
                                <datalist id="subsidiary_ledger_ids">
                                    @foreach ($subsidiaryLedgers as $subsidiaryLedger)
                                        <option data-value={{ $subsidiaryLedger->id }}>{{ $subsidiaryLedger->name }} ({{ $subsidiaryLedger->number }})</option>
                                    @endforeach
                                </datalist>
                                <datalist id="report_line_item_ids">
                                    @foreach ($reportLineItems as $reportLineItem)
                                        <option data-value={{ $reportLineItem->id }}>{{ $reportLineItem->report }}, {{ !empty($reportLineItem->section) ? $reportLineItem->section : '' }}, {{ $reportLineItem->line_item }}</option>
                                    @endforeach
                                </datalist>
                                <div class="form-group custom-control-inline">
                                    <label for="date">Date:&nbsp;</label>&nbsp;
                                    <input type="date" class="form-control @error('date') is-danger @enderror" id="date" name="date" required value="{!! old('date') !!}">
                                </div>
                                @error('date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <br>
                                <div class="form-group custom-control-inline">
                                    <label for="document_type_id">Document&nbsp;Type:&nbsp;</label>&nbsp;
                                    <input list="document_type_ids" id="document_type_id" onchange="setValue(this)" data-id=0 class="custom-select @error('document_type_id') is-danger @enderror" required value="{!! old('document_type_name') !!}">
                                    <datalist id="document_type_ids">
                                        @foreach ($documents as $document)
                                            <option data-value="{{ $document->id }}">{{ $document->name }}</option>
                                        @endforeach
                                    </datalist>
                                    <input type="hidden" name="document_type_id" id="document_type_id-hidden0" value="{!! old('document_type_id') !!}">
                                    <input type="hidden" name="document_type_name" id="name-document_type_id-hidden0" value="{!! old('document_type_name') !!}">
                                </div>
                                <div class="form-group custom-control-inline">
                                    <label for="document_number">Document&nbsp;Number:&nbsp;</label>&nbsp;
                                    <input type="number" class="form-control" id="document_number" name="document_number" style="text-align: right;" required value="{!! old('document_number') !!}">
                                </div>
                                @error('document_type_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                @error('document_number')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="form-group">
                                    <label for="explanation">Explanation: </label>
                                    <textarea id="explanation" name="explanation" class="form-control" rows="4" cols="50" required>{!! old('explanation') !!}</textarea>
                                </div>
                                @error('explanation')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="form-group">
                                    <table id="lines" style="width:100%">
                                        <tr style="text-align: center;">
                                            <th>
                                                <input type="checkbox" id="myCheck" onclick="myFunction()">
                                            </th>
                                            <th>
                                                <label for="postings['account_id'][]">Account Title</label>
                                            </th>
                                            <th>
                                                <label for="postings['debit'][]">Debit</label>
                                            </th>
                                            <th>
                                                <label for="postings['credit'][]">Credit</label>
                                            </th>
                                            <th>
                                                <label for="postings['subsidiary_ledger_id'][]">Subsidiary Ledger</label>
                                            </th>
                                            <th>
                                                <label for="postings['report_line_item_id'][]">Report Line Item</label>
                                            </th>
                                        <tr>
                                    </table>
                                </div>
                                <button class="btn btn-primary" type="submit">Save</button>
                            </form>
                            <br>
                            <button id="addLines"  onclick="addLines()" class="btn btn-secondary">Add Lines</button>&nbsp;&nbsp;
                            <button id="deleteLines" onclick="deleteLines()" class="btn btn-secondary">Delete Lines</button>
                            <script>
                                var line = 0;
                                function setValue (id) 
                                {
                                    var input = id,
                                        list = input.getAttribute('list'),
                                        options = document.querySelectorAll('#' + list + ' option'),
                                        hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden' + input.getAttribute('data-id')),
                                        hiddenInputName = document.getElementById('name-' + input.getAttribute('id') + '-hidden' + input.getAttribute('data-id')),
                                        label = input.value;

                                    hiddenInputName.value = label;
                                    hiddenInput.value = label;

                                    for(var i = 0; i < options.length; i++) {
                                        var option = options[i];

                                        if(option.innerText === label) {
                                            hiddenInput.value = option.getAttribute('data-value');
                                            break;
                                        }
                                    }
                                }
                                function addLines() {
                                    var tr = document.createElement("tr");
                                    var table = document.getElementById("lines");
                                    table.appendChild(tr);

                                    var td0 = document.createElement("td");
                                    tr.appendChild(td0);

                                    var check = document.createElement("input");
                                    check.setAttribute("type", "checkbox");
                                    check.setAttribute("class", "deleteBox");
                                    td0.appendChild(check);

                                    var td1 = document.createElement("td");
                                    tr.appendChild(td1);

                                    var accountInput = document.createElement("input");
                                    accountInput.setAttribute("list", "account_ids");
                                    accountInput.setAttribute("id", "postings['account_id'][]");
                                    accountInput.setAttribute("onchange", "setValue(this)");
                                    accountInput.setAttribute("data-id", line);
                                    accountInput.setAttribute("class", "custom-select");
                                    accountInput.setAttribute("required", "required");
                                    td1.appendChild(accountInput);

                                    var accountHidden = document.createElement("input");
                                    accountHidden.setAttribute("type", "hidden");
                                    accountHidden.setAttribute("name", "postings['account_id'][]");
                                    accountHidden.setAttribute("id", "postings['account_id'][]-hidden" + line);
                                    td1.appendChild(accountHidden);

                                    var accountHidden2 = document.createElement("input");
                                    accountHidden2.setAttribute("type", "hidden");
                                    accountHidden2.setAttribute("name", "postings['account_name'][]");
                                    accountHidden2.setAttribute("id", "name-postings['account_id'][]-hidden" + line);
                                    td1.appendChild(accountHidden2);

                                    var td2 = document.createElement("td");
                                    tr.appendChild(td2);

                                    var debitInput = document.createElement("input");
                                    debitInput.setAttribute("type", "number");
                                    debitInput.setAttribute("class", "form-control");
                                    debitInput.setAttribute("id", "postings['debit'][]" + line);
                                    debitInput.setAttribute("name", "postings['debit'][]");
                                    debitInput.setAttribute("step", "0.01");
                                    debitInput.setAttribute("style", "text-align: right;");
                                    td2.appendChild(debitInput);

                                    var td3 = document.createElement("td");
                                    tr.appendChild(td3);

                                    var creditInput = document.createElement("input");
                                    creditInput.setAttribute("type", "number");
                                    creditInput.setAttribute("class", "form-control");
                                    creditInput.setAttribute("id", "postings['credit'][]" + line);
                                    creditInput.setAttribute("name", "postings['crebit'][]");
                                    creditInput.setAttribute("step", "0.01");
                                    creditInput.setAttribute("style", "text-align: right;");
                                    td3.appendChild(creditInput);

                                    var td4 = document.createElement("td");
                                    tr.appendChild(td4);

                                    var subsidiaryInput = document.createElement("input");
                                    subsidiaryInput.setAttribute("list", "subsidiary_ledger_ids");
                                    subsidiaryInput.setAttribute("id", "postings['subsidiary_ledger_id'][]");
                                    subsidiaryInput.setAttribute("onchange", "setValue(this)");
                                    subsidiaryInput.setAttribute("data-id", line);
                                    subsidiaryInput.setAttribute("class", "custom-select");
                                    td4.appendChild(subsidiaryInput);

                                    var subsidiaryHidden = document.createElement("input");
                                    subsidiaryHidden.setAttribute("type", "hidden");
                                    subsidiaryHidden.setAttribute("name", "postings['subsidiary_ledger_id'][]");
                                    subsidiaryHidden.setAttribute("id", "postings['subsidiary_ledger_id'][]-hidden" + line);
                                    td4.appendChild(subsidiaryHidden);

                                    var subsidiaryHidden2 = document.createElement("input");
                                    subsidiaryHidden2.setAttribute("type", "hidden");
                                    subsidiaryHidden2.setAttribute("name", "postings['subsidiary_ledger_name'][]");
                                    subsidiaryHidden2.setAttribute("id", "name-postings['subsidiary_ledger_id'][]-hidden" + line);
                                    td4.appendChild(subsidiaryHidden2);

                                    var td5 = document.createElement("td");
                                    tr.appendChild(td5);

                                    var reportInput = document.createElement("input");
                                    reportInput.setAttribute("list", "report_line_item_ids");
                                    reportInput.setAttribute("id", "postings['report_line_item_id'][]");
                                    reportInput.setAttribute("onchange", "setValue(this)");
                                    reportInput.setAttribute("data-id", line);
                                    reportInput.setAttribute("class", "custom-select");
                                    td5.appendChild(reportInput);

                                    var reportHidden = document.createElement("input");
                                    reportHidden.setAttribute("type", "hidden");
                                    reportHidden.setAttribute("name", "postings['report_line_item_id'][]");
                                    reportHidden.setAttribute("id", "postings['report_line_item_id'][]-hidden" + line);
                                    td5.appendChild(reportHidden);

                                    var reportHidden2 = document.createElement("input");
                                    reportHidden2.setAttribute("type", "hidden");
                                    reportHidden2.setAttribute("name", "postings['report_line_item_name'][]");
                                    reportHidden2.setAttribute("id", "name-postings['report_line_item_id'][]-hidden" + line);
                                    td5.appendChild(reportHidden2);

                                    line++;
                                }
                                function addLines2(a, b, c, d, e, f, g, h) {
                                    var tr = document.createElement("tr");
                                    var table = document.getElementById("lines");
                                    table.appendChild(tr);

                                    var td0 = document.createElement("td");
                                    tr.appendChild(td0);

                                    var check = document.createElement("input");
                                    check.setAttribute("type", "checkbox");
                                    check.setAttribute("class", "deleteBox");
                                    td0.appendChild(check);

                                    var td1 = document.createElement("td");
                                    tr.appendChild(td1);

                                    var accountInput = document.createElement("input");
                                    accountInput.setAttribute("list", "account_ids");
                                    accountInput.setAttribute("id", "postings['account_id'][]");
                                    accountInput.setAttribute("onchange", "setValue(this)");
                                    accountInput.setAttribute("data-id", line);
                                    accountInput.setAttribute("class", "custom-select");
                                    accountInput.setAttribute("required", "required");
                                    accountInput.setAttribute("value", f);
                                    td1.appendChild(accountInput);

                                    var accountHidden = document.createElement("input");
                                    accountHidden.setAttribute("type", "hidden");
                                    accountHidden.setAttribute("name", "postings['account_id'][]");
                                    accountHidden.setAttribute("id", "postings['account_id'][]-hidden" + line);
                                    accountHidden.setAttribute("value", a);
                                    td1.appendChild(accountHidden);

                                    var accountHidden2 = document.createElement("input");
                                    accountHidden2.setAttribute("type", "hidden");
                                    accountHidden2.setAttribute("name", "postings['account_name'][]");
                                    accountHidden2.setAttribute("id", "name-postings['account_id'][]-hidden" + line);
                                    accountHidden2.setAttribute("value", f);
                                    td1.appendChild(accountHidden2);

                                    var td2 = document.createElement("td");
                                    tr.appendChild(td2);

                                    var debitInput = document.createElement("input");
                                    debitInput.setAttribute("type", "number");
                                    debitInput.setAttribute("class", "form-control");
                                    debitInput.setAttribute("id", "postings['debit'][]" + line);
                                    debitInput.setAttribute("name", "postings['debit'][]");
                                    debitInput.setAttribute("step", "0.01");
                                    debitInput.setAttribute("style", "text-align: right;");
                                    debitInput.setAttribute("value", b);
                                    td2.appendChild(debitInput);

                                    var td3 = document.createElement("td");
                                    tr.appendChild(td3);

                                    var creditInput = document.createElement("input");
                                    creditInput.setAttribute("type", "number");
                                    creditInput.setAttribute("class", "form-control");
                                    creditInput.setAttribute("id", "postings['credit'][]" + line);
                                    creditInput.setAttribute("name", "postings['crebit'][]");
                                    creditInput.setAttribute("step", "0.01");
                                    creditInput.setAttribute("style", "text-align: right;");
                                    creditInput.setAttribute("value", c);
                                    td3.appendChild(creditInput);

                                    var td4 = document.createElement("td");
                                    tr.appendChild(td4);

                                    var subsidiaryInput = document.createElement("input");
                                    subsidiaryInput.setAttribute("list", "subsidiary_ledger_ids");
                                    subsidiaryInput.setAttribute("id", "postings['subsidiary_ledger_id'][]");
                                    subsidiaryInput.setAttribute("onchange", "setValue(this)");
                                    subsidiaryInput.setAttribute("data-id", line);
                                    subsidiaryInput.setAttribute("class", "custom-select");
                                    subsidiaryInput.setAttribute("value", g);
                                    td4.appendChild(subsidiaryInput);

                                    var subsidiaryHidden = document.createElement("input");
                                    subsidiaryHidden.setAttribute("type", "hidden");
                                    subsidiaryHidden.setAttribute("name", "postings['subsidiary_ledger_id'][]");
                                    subsidiaryHidden.setAttribute("id", "postings['subsidiary_ledger_id'][]-hidden" + line);
                                    subsidiaryHidden.setAttribute("value", d);
                                    td4.appendChild(subsidiaryHidden);

                                    var subsidiaryHidden2 = document.createElement("input");
                                    subsidiaryHidden2.setAttribute("type", "hidden");
                                    subsidiaryHidden2.setAttribute("name", "postings['subsidiary_ledger_name'][]");
                                    subsidiaryHidden2.setAttribute("id", "name-postings['subsidiary_ledger_id'][]-hidden" + line);
                                    subsidiaryHidden2.setAttribute("value", g);
                                    td4.appendChild(subsidiaryHidden2);

                                    var td5 = document.createElement("td");
                                    tr.appendChild(td5);

                                    var reportInput = document.createElement("input");
                                    reportInput.setAttribute("list", "report_line_item_ids");
                                    reportInput.setAttribute("id", "postings['report_line_item_id'][]");
                                    reportInput.setAttribute("onchange", "setValue(this)");
                                    reportInput.setAttribute("data-id", line);
                                    reportInput.setAttribute("class", "custom-select");
                                    reportInput.setAttribute("value", h);
                                    td5.appendChild(reportInput);

                                    var reportHidden = document.createElement("input");
                                    reportHidden.setAttribute("type", "hidden");
                                    reportHidden.setAttribute("name", "postings['report_line_item_id'][]");
                                    reportHidden.setAttribute("id", "postings['report_line_item_id'][]-hidden" + line);
                                    reportHidden.setAttribute("value", e);
                                    td5.appendChild(reportHidden);

                                    var reportHidden2 = document.createElement("input");
                                    reportHidden2.setAttribute("type", "hidden");
                                    reportHidden2.setAttribute("name", "postings['report_line_item_name'][]");
                                    reportHidden2.setAttribute("id", "name-postings['report_line_item_id'][]-hidden" + line);
                                    reportHidden2.setAttribute("value", h);
                                    td5.appendChild(reportHidden2);

                                    line++;
                                }
                                function myFunction() {
                                    var checkBox = document.getElementById("myCheck");
                                    if (checkBox.checked == true)
                                    {
                                        var x = document.getElementsByClassName("deleteBox");
                                        var i;
                                        for (i = 0; i < x.length; i++) {
                                            x[i].checked = true;
                                        }
                                    }
                                    else
                                    {
                                        var x = document.getElementsByClassName("deleteBox");
                                        var i;
                                        for (i = 0; i < x.length; i++) {
                                            x[i].checked = false;
                                        }
                                    }
                                }
                                function deleteLines () {
                                    var x = document.getElementsByClassName("deleteBox");
                                    var i;
                                    for (i = 0; i < x.length; i++) {
                                        if (x[i].checked) {
                                            x[i].parentNode.parentNode.remove();
                                        }
                                    }
                                }
                                @if (!empty(old('postings')))
                                    @for ($i = 0; $i < count(old("postings.'account_id'")); $i++)
                                        addLines();
                                        document.getElementById("postings['account_id'][]-hidden" + $i).value = {{ old("postings.'account_id'.$i") }};
                                        document.getElementById("postings['debit'][]" + $i).value = {{ old("postings.'debit'.$i") }};
                                        document.getElementById("postings['credit'][]" + $i).value = {{ old("postings.'credit'.$i") }}; 
                                        document.getElementById("postings['subsidiary_ledger_id'][]-hidden" + $i).value = {{ old("postings.'subsidiary_ledger_id'.$i") }};
                                        document.getElementById("postings['report_line_item_id'][]-hidden" + $i).value = {{ old("postings.'report_line_item_id'.$i") }};
                                        document.getElementById("name-postings['account_id'][]-hidden" + $i).value = {{ old("postings.'account_name'.$i") }};
                                        document.getElementById("name-postings['subsidiary_ledger_id'][]-hidden" + $i).value = {{ old("postings.'subsidiary_ledger_name'.$i") }};
                                        document.getElementById("name-postings['report_line_item_id'][]-hidden" + $i).value = {{ old("postings.'report_line_item_name'.$i") }};
                                    @endfor
                                @endif
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
