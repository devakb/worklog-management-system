
@extends('layouts.app')

@section('content')
    <script>
        let chartLabels = [];
        let chartValues = [];
    </script>
    <div class="main">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="col-12 px-0">
                    <div class="card border-0 shadow">
                        <div class="card-body">
                            <h2 class="fs-5 fw-bold mb-1">{{ __('Hello! ') }} {{ $user->name }}</h2>
                            <p>{{ __('Welcome to dashboard') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    <h2 class="mb-4 h5">{{ __('Summary of your today work logs') }}</h2>
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
    </div>
@endsection
@section('scripts')
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
