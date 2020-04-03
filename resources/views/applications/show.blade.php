@extends ('layouts.app')
@section ('content')
    @include('layouts.nav')
    <div class="col-md-10">
        <div class="card">
            <div class="card-header font-weight-bold">Application Details</div>
            <div class="card-body">
                <div id="wrapper">
                    <div
                        id="page"
                        class="container"
                    >
                        <div id="content">
                            <div id="name">
                                <p>Company Name: {!! \App\Company::findOrFail($application->company_id)->name; !!}</p>
                                <p>Company Code: {!! \App\Company::findOrFail($application->company_id)->code; !!}</p>
                            </div>
                            <div style="display:inline-block;"><button class="btn btn-primary" onclick="location.href = '/applications/{{ $application->id }}/edit';">Edit</button></div>
                            <div style="display:inline-block;"><form method="POST" action="/applications/{{ $application->id }}">
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
