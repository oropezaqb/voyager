@extends ('layouts.app')
@section ('content')
    @include('layouts.nav')
    <div class="col-md-10">
        <div class="card">
            <div class="card-header font-weight-bold">Company: {{ \Auth::user()->current_company->company->name }} (Subsidiary Ledger Details)</div>
            <div class="card-body">
                <div id="wrapper">
                    <div
                        id="page"
                        class="container"
                    >
                        <div id="content">
                            <div id="name">
                                <p>Account Number: {{ $subsidiary_ledger->number }}</p>
                                <p>Account Name: {{ $subsidiary_ledger->name }}</p>
                            </div>
                            <div style="display:inline-block;"><button class="btn btn-primary" onclick="location.href = '/subsidiary_ledgers/{{ $subsidiary_ledger->id }}/edit';">Edit</button></div>
                            <div style="display:inline-block;"><form method="POST" action="/subsidiary_ledgers/{{ $subsidiary_ledger->id }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
