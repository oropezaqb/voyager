@extends ('layouts.app')
@section ('content')
    @include('layouts.nav')
    <div class="col-md-10">
        <div class="card">
            <div class="card-header font-weight-bold">Company: {{ \Auth::user()->current_company->company->name }} (Run Report)</div>
            <div class="card-body">
                <div id="wrapper">
                    <div id="page" class="container">
                        <div class="content">
                            <p>Click below to access the report:</p>
                            <p><a href='{{ $url }}'>{{ $url }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
