@extends('layouts.app')

@section('content')
    <div class="main py-4">
        <div class="card card-body border-0 shadow">
            <h2 class="mb-4 h5">{{ __('Project Edit') }}</h2>

            <form action="{{ route('projects.update', $project) }}" method="POST">
                @method("PUT")
                @csrf
                <div class="row mb-4 gy-2 flex-column">

                    <div class="col-md-6 col-lg-4">
                        <label class="required">Project Code</label>
                        <input type="text" name="code" value="{{ old("code", $project->code) }}" class="form-control @error('code') is-invalid @enderror" required placeholder="eg. ABC-123">
                        @error('code')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <label class="required">Project Name</label>
                        <input type="text" name="full_name" value="{{ old("full_name", $project->full_name) }}" class="form-control @error('full_name') is-invalid @enderror" required placeholder="eg. My New Project">
                        @error('full_name')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <label class="required">Client Name</label>
                        <input type="text" name="client_name" value="{{ old("client_name", $project->client_name) }}" class="form-control @error('client_name') is-invalid @enderror" required placeholder="eg. Jonh Due">
                        @error('client_name')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <label class="required">Client Email</label>
                        <input type="email" name="client_email" value="{{ old("client_email", $project->client_email) }}" class="form-control @error('client_email') is-invalid @enderror" required placeholder="eg. example@project.com">
                        @error('client_email')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <label >Client Phone</label>
                        <input type="text" name="client_phone" value="{{ old("client_phone", $project->client_phone) }}" class="form-control @error('client_phone') is-invalid @enderror"  placeholder="eg. 1234567890">
                        @error('client_phone')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="mb-2">
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('projects.index') }}" class="btn btn-white border">Back to List</a>
                </div>
            </form>

        </div>
    </div>
@endsection
