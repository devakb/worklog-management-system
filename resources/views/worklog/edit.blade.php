@extends('layouts.app')

@section('content')
    <div class="main py-4">
        <div class="card card-body border-0 shadow">
            <h2 class="mb-4 h5">{{ __('Work Log Update') }}</h2>

            <form action="{{ route('work-logs.update', $work_log) }}" method="POST" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="row mb-4 gy-2 flex-column">

                    <div class="col-md-6 col-lg-5">
                        <label class="required">Project</label>
                        <select name="project_id" class="form-select select2 @error('project_id') is-invalid @enderror" required>
                            <option value="" selected>Select Project</option>
                            @foreach($projects as $project)
                                <option @selected(old('project_id', $work_log->project_id) == $project->id) value="{{ $project->id }}">{{ $project->full_name }} (Code: {{ $project->code }})</option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-lg-5">
                        <label class="required">Date of Work</label>
                        <input type="text" step="0.01" id="date_of_work" name="date_of_work" value="{{ old("date_of_work", $work_log->date_of_work->format("d-M-Y")) }}" class="form-control @error('date_of_work') is-invalid @enderror" required placeholder="Pick a date">
                        @error('date_of_work')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-lg-5">
                        <label class="required">Hours</label>
                        <input type="number" step="0.1" name="work_duration_in_minutes" value="{{ old("work_duration_in_minutes", $work_log->work_duration_in_minutes / 60) }}" class="form-control @error('work_duration_in_minutes') is-invalid @enderror" required placeholder="eg. 0.5 = 30 minutes">
                        @error('work_duration_in_minutes')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-lg-5">
                        <label class="required">Work Description</label>
                        <textarea name="work_description" rows="15" class="form-control @error('work_description') is-invalid @enderror"  placeholder="eg. Describe your work">{{ old("work_description", $work_log->work_description) }}</textarea>
                        @error('work_description')
                            <div class="invalid-feedback"><i class="fa fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="mb-2">
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ request()->get('ref', route('work-logs.index')) }}" class="btn btn-white border">Back to List</a>
                </div>
            </form>

        </div>
    </div>
@endsection
@section('scripts')
    <script>


        $( function() {
            $(".select2").select2();

            $( "#date_of_work" ).datepicker({
                dateFormat: "dd-M-yy",
                maxDate: "today",
            });
        } );
    </script>
@endsection
