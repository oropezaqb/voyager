@extends('layouts.app')
@section('content')
    @include('layouts.nav')
    <div class="col-md-10">
        <div class="card">
            <div class="card-header font-weight-bold">Update Company Details</div>
            <div class="card-body">
                <div id="wrapper">
                    <div id="page" class="container">
                        <form method="POST" action="/companies/{{ $company->id }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Company Name: </label>
                                <input 
                                    class="form-control @error('name') is-danger @enderror" 
                                    type="text" 
                                    name="name" 
                                    id="name" required
                                    value="{{ $company->name }}">
                                @error('name')
                                    <p class="help is-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
