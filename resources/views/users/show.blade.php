@extends('layouts.app')

@section('content')
    <div class="main py-4">

        <a href="{{ route('users.index') }}" class="btn btn-white border">Back to List</a>

        <div class="row mt-3">
            <div class="col">
                <div class="card card-body" style="border-left: 4px solid #bc1851">
                    <h6 class="text-uppercase">Today Hours Logged</h6>
                    <h2>{{ (new App\Models\Worklog)->calculateWorkDuration($total_hour_logged_today) }}</h2>
                </div>
            </div>
            <div class="col">
                <div class="card card-body" style="border-left: 4px solid #076098">
                    <h6 class="text-uppercase">Current Month Hours Logged</h6>
                    <h2>{{ (new App\Models\Worklog)->calculateWorkDuration($total_hour_logged_this_month) }}</h2>
                </div>
            </div>
            <div class="col">
                <div class="card card-body" style="border-left: 4px solid #985407">
                    <h6 class="text-uppercase">Current Year Hours Logged</h6>
                    <h2>{{ (new App\Models\Worklog)->calculateWorkDuration($total_hour_logged_this_year) }}</h2>
                </div>
            </div>
            <div class="col">
                <div class="card card-body" style="border-left: 4px solid #079841">
                    <h6 class="text-uppercase">Total Hours Logged</h6>
                    <h2>{{ (new App\Models\Worklog)->calculateWorkDuration($total_hour_logged) }}</h2>
                </div>
            </div>
        </div>

        <div class="row my-4">
            <div class="col-md-6">
                <div class="card card-body border-0 shadow h-100">
                    <h2 class="mb-4 h5">{{ __('User Details') }}</h2>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>
                                    Name:
                                </th>
                                <td>
                                    {{ $user->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Email:
                                </th>
                                <td>
                                    {{ $user->email }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Designation:
                                </th>
                                <td>
                                    {{ $user->designation }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    User Status:
                                </th>
                                <td>
                                    <div class="fw-normal">
                                        @if($user->is_active)
                                            <i class="fa fa-check-circle fa-1x me-2 text-success"></i> <span class="text-success">Active</span>
                                        @else
                                            <i class="fa fa-times-circle fa-1x me-2 text-danger"></i> <span class="text-danger">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Date of Joined:
                                </th>
                                <td>
                                    {{ $user->created_at->format("M d, Y") }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-body border-0 shadow h-100">
                    <h2 class="mb-4 h5">{{ __('Projects Assigned') }}</h2>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Project Name and Code</th>
                                    <th>Date of Assigned</th>
                                    <th>Access Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($user->projects as $project)
                                <tr>
                                    <td>{{ $project->full_name }} (Code: {{ $project->code }})</td>
                                    <td>{{ $project->pivot->assigned_at }}</td>
                                    <td>
                                        <div class="fw-normal text-center">
                                            @if($project->pivot->is_active)
                                                <i class="las la-check-double la-2x text-success"></i>
                                            @else
                                                <i class="la la-times-circle la-2x text-danger"></i>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                @empty
                                    <tr>
                                        <td colspan="100%">
                                            <div class="text-center">
                                                <div class="text-muted">
                                                    No Project Assinged Yet.
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-body border-0 shadow mt-2">
            <h2 class="mb-4 h5">{{ __('User Work Logs') }}</h2>

            <div class="table-wrapper table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="border-gray-200">{{ __('Project') }}</th>
                            <th class="border-gray-200">{{ __('Log Hours') }}</th>
                            <th class="border-gray-200">{{ __('Log Date') }}</th>
                            <th class="border-gray-200">{{ __('Description') }}</th>
                            <th class="border-gray-200">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="members_list">
                        @forelse ($worklogs as $worklog)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <a href="{{ route('projects.edit', $worklog->project) }}" class="text-info p-0 mb-1 d-block">{{ $worklog->project->full_name }} <i class="la la-external-link-alt"></i></a>
                                </td>
                                <td>
                                    {{ $worklog->work_duration_formatted }}
                                </td>
                                <td>
                                   {{ $worklog->date_of_work->format("M d, Y") }}
                                </td>
                                <td style="word-break: break-word; white-space: normal;">
                                    {{ $worklog->work_description }}
                                </td>
                                <td>
                                    @if($worklog->user_id == auth()->id())
                                        <a href="{{ route("work-logs.edit", ["work_log" => $worklog, 'ref' => request()->fullUrl()]) }}" class="btn btn-primay">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>

                                        <button class="btn btn-sm text-danger delete-record" data-url="{{ route('work-logs.destroy', $worklog) }}">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="100%">
                                    <span class="text-muted">
                                        {{ __('No Work Logs found.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <br>

            {{ $worklogs->links() }}
        </div>
    </div>
@endsection
