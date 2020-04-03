@extends ('layouts.app')
@section ('content')
    @include('layouts.nav')
    <div class="col-md-10">
        <div class="card">
            <div class="card-header font-weight-bold">Current Company</div>
            <div class="card-body">
                <div id="wrapper">
                    <div
                        id="page"
                        class="container"
                    >
                        <h6 class="font-weight-bold">Add</h6>
                        <p>Want to add a company as current? Click <a href="{{ url('/current_company/create') }}">here</a>!</p>
                        <p></p>
                        <h6 class="font-weight-bold">List</h6>
                        @if (!empty($current_company))
                            <div id="content">
                                <div id="name">
                                    <div style="display:inline-block;"><button class="btn btn-link" onclick="location.href = '{{ $current_company->path() }}';">View</button></div>
                                    <div style="display:inline-block;"><button class="btn btn-link" onclick="location.href = '/current_company/{{ $current_company->id }}/edit';">Edit</button></div>
                                    <div style="display:inline-block;"><form method="POST" action="/current_company/{{ $current_company->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-link" type="submit">Delete</button>
                                    </form></div><div style="display:inline-block;">&nbsp;&nbsp;{!! $current_company->company->name !!}</div>
                                </div>
                            </div>
                        @else
                            <p>No company recorded yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
