@extends('layouts.app')

@section('content')
    <div class="main py-4">
        <div class="card card-body border-0 shadow">
            <h2 class="mb-4 h5">{{ __('Projects') }}</h2>

            <div class="mb-2">
                <a href="{{ route('projects.create') }}" class="btn btn-primary">Add New Project</a>
            </div>

            <form class="my-3">
                <div class="row align-items-end">
                    <div class="col" style="max-width: 310px">
                        <label>Search Project</label>
                        <input type="text" class="form-control" name="search" placeholder="Search with name or code" value="{{ request()->search}}">
                    </div>

                    <div class="col" style="max-width: 310px">
                        <button class="btn btn-primary">Search</button>
                        <a href="{{ route('projects.index') }}" class="btn btn-white border">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-wrapper table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="40%" class="border-gray-200">{{ __('Project Name') }}</th>
                            <th class="border-gray-200">{{ __('Project Code') }}</th>
                            <th class="border-gray-200">{{ __('Client') }}</th>
                            <th class="border-gray-200 text-center">{{ __('Team Members') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr>
                                <td><span class="fw-normal">{{ $project->full_name }}</span></td>
                                <td><span class="fw-normal">{{ $project->code }}</span></td>
                                <td>
                                    <span class="fw-bold">{{ $project->client_name }}</span> <br>
                                    <span class="fw-normal text-info">{{ $project->client_email }}</span>
                                </td>
                                <td class="text-center"><span class="fw-bold">{{ $project->asignees_count }}</span></td>
                                <td>
                                    <a href="{{ route('projects.members.index', $project) }}" class="btn btn-sm btn-white border">Manage Members</a>
                                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-white border">Edit</a>
                                    {{-- <button class="btn btn-sm btn-secondary delete-record" data-url="{{ route('projects.destroy', $project) }}">Delete</button> --}}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="100%">
                                    <span class="text-muted">
                                        {{ __('No projects found.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
@endsection
@section('scripts')

@endsection
