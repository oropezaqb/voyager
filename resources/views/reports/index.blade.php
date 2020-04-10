@extends ('layouts.app')
@section ('content')
    @include('layouts.nav')
    <div class="col-md-10">
        <div class="card">
            <div class="card-header font-weight-bold">Company: {{ \Auth::user()->current_company->company->name }} (Reports)</div>
            <div class="card-body">
                <div id="wrapper">
                    <div
                        id="page"
                        class="container"
                    >
                        @forelse ($queries as $query)
                            <div id="query">
                                <div style="display:inline-block;"><form method="POST" action="/reports/{{ $query->id }}/screen">
                                    @csrf
                                    <button class="btn btn-link" type="submit">Screen</button>
                                </form></div>
                                <div style="display:inline-block;"><form method="POST" action="/reports/{{ $query->id }}/pdf">
                                    @csrf
                                    <button class="btn btn-link" type="submit">PDF</button>
                                </form></div>
                                <div style="display:inline-block;"><form method="POST" action="/reports/{{ $query->id }}/csv">
                                    @csrf
                                    <button class="btn btn-link" type="submit">CSV</button>
                                </form></div>
                                <div style="display:inline-block;">&nbsp;&nbsp;{{ $query->title }}: {{ $query->query }}</div>
                            </div>
                        @empty
                            <p>No reports available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
