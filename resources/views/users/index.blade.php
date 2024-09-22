@extends('layouts.app')

@section('content')
    <div class="main py-4">
        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            <h2 class="mb-4 h5">{{ __('Users') }}</h2>


            <div class="mb-2">
                <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>
            </div>

            <form class="my-3">
                <div class="row align-items-end">
                    <div class="col" style="max-width: 310px">
                        <label>Search with name or email</label>
                        <input type="text" class="form-control" name="search" placeholder="Search with name or email" value="{{ request()->search}}">
                    </div>

                    <div class="col" style="max-width: 400px">
                        <label>Filter with Designation</label>
                        <select name="designation" id="designation" class="form-control">
                            <option value="">Any Designation</option>
                            @foreach (App\Models\User::DESIGNATIONS as $desc)
                                <option value="{{ $desc }}" @selected($desc == request()->designation)>{{ $desc }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col" style="max-width: 400px">
                        <label>Filter with account status</label>
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="">All Status</option>
                            <option value="1" @selected(1 == request()->is_active)>Active</option>
                            <option value="0" @selected(request()->filled("is_active") && 0 == request()->is_active)>Inactive</option>
                        </select>
                    </div>

                    <div class="col" style="max-width: 310px">
                        <button class="btn btn-primary">Search</button>
                        <a href="{{ route('users.index') }}" class="btn btn-white border">Reset</a>
                    </div>
                </div>
            </form>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="border-gray-200">{{ __('Name') }}</th>
                        <th class="border-gray-200">{{ __('Email') }}</th>
                        <th class="border-gray-200">{{ __('Designation') }}</th>
                        <th class="border-gray-200">{{ __('Account Type') }}</th>
                        <th class="border-gray-200">{{ __('Account Status') }}</th>
                        <th class="border-gray-200">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td><span class="fw-normal">{{ $user->name }}</span></td>
                            <td><span class="fw-normal">{{ $user->email }}</span></td>
                            <td><span class="fw-normal">{{ $user->designation }}</span></td>
                            <td><span class="fw-normal">{{ $user->is_admin ? "Admin User" : "Regular User" }}</span></td>
                            <td><span class="fw-normal">
                                @if($user->is_active)
                                    <i class="fa fa-check-circle fa-1x me-2 text-success"></i> <span class="text-success">Active</span>
                                @else
                                    <i class="fa fa-times-circle fa-1x me-2 text-danger"></i> <span class="text-danger">Inactive</span>
                                @endif
                            </span></td>
                            <td>

                                <a href="{{ route('users.status-toggle', [$user]) }}" class="btn btn-sm @if($user->is_active) btn-outline-danger @else btn-outline-info @endif border">
                                    @if($user->is_active)
                                        <i class="las la-user-minus"></i> {{ __('Make Inactive') }}
                                    @else
                                        <i class="las la-user-plus"></i> {{ __('Make Active') }}
                                    @endif
                                </a>

                                <a href="{{ route('users.edit', [$user]) }}" class="btn btn-sm btn-primary border">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                <a href="{{ route('users.show', [$user]) }}" class="btn btn-sm btn-white border">
                                    <i class="fa fa-eye"></i> View
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div
                class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                {{ $users->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
@endsection
