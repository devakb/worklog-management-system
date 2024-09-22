@extends('layouts.app')

@section('content')
    <div class="main py-4">
        <div class="card card-body border-0 shadow">
            <h2 class="mb-4 h5">{{ __('User Edit') }}</h2>

            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method("PUT")
                <div class="row mb-4 gy-2 flex-column">


                    <div class="col-md-6 col-lg-4">
                        <label class="required">Name</label>
                        <input type="text" name="name" value="{{ old("name", $user->name) }}" class="form-control @error('name') is-invalid @enderror" required placeholder="eg. Jonh Due">
                        @error('name')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <label class="required">Email</label>
                        <input type="email" name="email" value="{{ old("email", $user->email) }}" class="form-control @error('email') is-invalid @enderror" required placeholder="eg. example@project.com">
                        @error('email')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <label class="required">Designation</label>
                        <select  name="designation" class="form-control @error('designation') is-invalid @enderror" required>
                            <option value="" disabled selected>Choose Designation</option>
                            @foreach (\App\Models\User::DESIGNATIONS as $designation)
                                <option value="{{ $designation }}" @selected(old("designation", $user->designation) == $designation)>{{ $designation }}</option>
                            @endforeach
                        </select>
                        @error('designation')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <label class="required">Password</label>
                        <input type="password" name="password" value="{{ old("password") }}" class="form-control @error('password') is-invalid @enderror"  placeholder="xxxxxxxx">
                        @error('password')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>


                </div>


                <div class="mb-2">
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('users.index') }}" class="btn btn-white border">Back to List</a>
                </div>
            </form>

        </div>
    </div>
@endsection
