@extends('layouts.app')

@section('content')
    <div class="main py-4">
        <div class="card card-body border-0 shadow">
            <div class="d-flex justify-content-between">
                <h2 class="mb-4 h5">{{ __('Work Logs') }}</h2>

                <div>
                    <a href="{{ route('work-logs.create') }}" class="btn btn-info"><i class="fa fa-edit"></i> Log Work</a>
                </div>
            </div>

            <form class="my-3">
                <div class="row align-items-end">
                    @if(auth()->user()->is_admin)
                    <div class="col" style="max-width: 310px">
                        <label>Search Member</label>
                        <input type="text" class="form-control" name="search" placeholder="Search with name or email" value="{{ request()->search}}">
                    </div>
                    @endif
                    <div class="col" style="max-width: 400px">
                        <label>Filter Project</label>
                        <select name="project_ids[]" id="project_ids" data-placeholder="Projects ..." multiple class="form-contol">
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @selected(in_array($project->id, request()->project_ids ?? []))>{{ $project->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col" style="max-width: 310px">
                        <label>Filter Date Range</label>
                        <div id="select-filter-daterange" class="form-control">
                            <i class="las la-calendar-alt"></i>
                            <span></span>
                            <input type="hidden" name="to" id="filter-daterange-to">
                            <input type="hidden" name="from" id="filter-daterange-from">
                        </div>
                    </div>
                    <div class="col" style="max-width: 310px">
                        <button class="btn btn-primary">Search</button>
                        <a href="{{ route('work-logs.index') }}" class="btn btn-white border">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-wrapper table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="40%" class="border-gray-200">{{ __('User') }}</th>
                            <th class="border-gray-200">{{ __('Date') }}</th>
                            <th class="border-gray-200">{{ __('Projects') }}</th>
                            <th class="border-gray-200">{{ __('Hours Logged') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($worklogs as $worklog)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $worklog->user->name }}</span> <br>
                                    <span class="fw-normal text-info">{{ $worklog->user->email }}</span>
                                </td>
                                <td><span class="fw-normal">{{ $worklog->work_dates }}</span></td>
                                <td>
                                    <div class="fw-normal">
                                        @foreach ($worklog->projects as $prj)
                                            <a href="{{ route('projects.edit', $prj) }}" class="text-info p-0 mb-1 d-block">{{ $prj->full_name }} <i class="la la-external-link-alt"></i></a>
                                        @endforeach
                                    </div>
                                </td>
                                <td><span class="fw-normal">{{ $worklog->work_duration }}</span></td>
                                <td>
                                    <a href="{{ route("work-logs.visualize", ["user" => $worklog->user, "to" => request()->to, "from" => request()->from, 'ref' => request()->fullUrl()]) }}" class="btn btn-primay">
                                        <i class="fa fa-eye"></i> View Logs
                                    </a>
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

            <div
                class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                {{ $worklogs->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>

        $(function() {
            new Choices(document.getElementById("project_ids"),{
                placeholder: true,
                placeholderValue: "Choose projects",
            });
        })

        $(".delete-record").click(function(){

            const delete_url = $(this).data('url');


            Swal.fire({
                title: "Are You Sure?",
                text: "This action will delete the project permanently. This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                customClass: {
                    confirmButton: "btn-primary",
                    cancelButton: "btn-danger",
                },
            }).then(res => {
                if(res.isConfirmed){
                    let form = $(`<form action="${delete_url}" method="POST">
                        @csrf
                        @method("DELETE")
                    </form>`);

                    $("body").append(form);

                    form.submit();
                    form.remove();
                }
            })

        })

        $(function() {

            var start = moment("{{ Carbon\Carbon::parse(request()->from)->format('Y-m-d') }}");
            var end = moment("{{ Carbon\Carbon::parse(request()->to)->format('Y-m-d') }}");

            $('#select-filter-daterange').daterangepicker({
                timePicker: false,
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last 6 Months': [moment().subtract(6, 'month').startOf('month'), moment()],
                },
                // locale: {
                //     format: 'DD-MM-YYYY',
                //     "separator": " -> "
                // }
            }, (start, end) => {

                $('#select-filter-daterange span').html(start.format('MMM DD, YYYY') + ' -> ' + end.format('MMM DD, YYYY'));
                $("#filter-daterange-from").val(start.format('DD-MM-YYYY'));
                $("#filter-daterange-to").val(end.format('DD-MM-YYYY'));

            });


            $('#select-filter-daterange span').html(start.format('MMM DD, YYYY') + ' -> ' + end.format('MMM DD, YYYY'));
            $("#filter-daterange-from").val(start.format('DD-MM-YYYY'));
            $("#filter-daterange-to").val(end.format('DD-MM-YYYY'));
        });
    </script>
@endsection
