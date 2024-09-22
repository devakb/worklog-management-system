@extends('layouts.app')

@section('content')
    <script>
        let chartLabels = [];
        let chartValues = [];
    </script>
    <div class="main py-4">
        <div class="mb-5">
            <h5>View/Manage Work Logs</h5>
            <br>
            <div class="card shadow">
                <div class="card-body">
                   <form action="">
                    <input type="hidden" name="ref" value="{{ request()->get('ref', route('work-logs.index')) }}">
                        <div class="row align-items-end">
                            <div class="col">
                                <label>Total Time Logged</label>
                                <h2>{{ (new \App\Models\WorkLog)->calculateWorkDuration($worklogs->sum('work_duration_in_minutes')) }}</h2>
                            </div>
                            <div class="col">
                                <label>Total Worked Projects</label>
                                <h2>{{ str()->padLeft($worklogs->groupby('project_id')->count(), 2, 0) }}</h2>
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
                            <div class="col-2">
                                <button class="btn btn-primary" type="submit">Apply</button>
                                <a href="{{ request()->get('ref', route('work-logs.index')) }}" class="btn btn-secondary"><- Back</a>
                            </div>
                        </div>
                   </form>
                </div>
            </div>
        </div>
        <div class="mb-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow h-100">
                        <div class="card-header">
                            Project Logged Hours
                        </div>
                        <div class="card-body row">
                            @if($worklogs->count() <= 0)
                                <div class="text-center">
                                    <img src="{{ asset("nologs.jpg") }}" class="img-fluid w-50" />
                                    <br>
                                    <h6 class="text-muted">No Logs Found</h6>
                                </div>
                            @else
                                <div class="col">
                                    <ul class="list-group">
                                        @foreach ($worklogs->groupby('project_id') as $wl)
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>{{ $wl[0]->project->full_name }}</span>
                                                <b>{{ (new \App\Models\WorkLog)->calculateWorkDuration($wl->sum('work_duration_in_minutes')) }}</b>
                                            </li>
                                            <script>
                                                chartLabels.push("{{ $wl[0]->project->full_name }}");
                                                chartValues.push("{{ $wl->sum('work_duration_in_minutes') }}");
                                            </script>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col">
                                    <canvas id="projectHoursChart" width="100" height="100"></canvas>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow h-100">
                        <div class="card-header">
                            Member Details
                        </div>
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
            </div>
        </div>
        <div class="card card-body border-0 shadow">
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
        </div>
    </div>
@endsection
@section('scripts')

    <script>
        $(".delete-record").click(function(){

            const delete_url = $(this).data('url');


            Swal.fire({
                title: "Are You Sure?",
                text: "This action will delete the log permanently. This action cannot be undone.",
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
    </script>
    <script>

        $(function(){

            const data = {
                labels: chartLabels,
                datasets: [{
                    label: 'Logged Minutes',
                    data: chartValues,
                    // backgroundColor: [
                    //     'rgb(255, 99, 132)',
                    //     'rgb(54, 162, 235)',
                    //     'rgb(255, 205, 86)'
                    // ],
                    hoverOffset: 4
                }]
            };


            const config = {
                type: 'doughnut',
                data: data,
            };

            const myChart = new Chart(document.getElementById('projectHoursChart').getContext('2d'), config);

        });

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
