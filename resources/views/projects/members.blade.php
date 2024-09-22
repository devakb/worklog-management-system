@extends('layouts.app')

@section('content')
    <div class="main py-4">
        <div class="card card-body border-0 shadow">
            <h2 class="mb-4 h5 d-flex justify-content-between">
                <span>{{ __('Project Members') }} </span>
                <span class="text-dark">{{ $project->full_name }} (Code: {{ $project->code }})</span>
            </h2>

            <div class="mb-2">
                <button class="btn btn-primary border" data-bs-target="#AddNewMemberModal" data-bs-toggle="modal">Add New Member</button>
                <a href="{{ route('projects.index') }}" class="btn btn-white border">Back to List</a>

                <div class="d-flex justify-content-start my-2">
                    <input type="text" class="form-control" placeholder="Search with member name or email" id="search" style="max-width: 400px;">
                </div>
            </div>

            <div class="table-wrapper table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th width="40%" class="border-gray-200">{{ __('Member Details') }}</th>
                            <th class="border-gray-200">{{ __('Assigned On') }}</th>
                            <th class="border-gray-200">{{ __('Assigned By') }}</th>
                            <th class="border-gray-200 text-center">{{ __('Project Access') }}</th>
                            <th class="border-gray-200">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="members_list">
                        @forelse ($project->asignees as $asingee)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $asingee->name }}</span> <br>
                                    <span class="fw-normal text-info">{{ $asingee->email }}</span>
                                </td>
                                <td>
                                    <i class="las la-calendar-alt"></i> <span class="fw-normal">{{ Carbon\Carbon::parse($asingee->pivot->assigned_at)->format("d M Y") }}</span> <br>
                                    <i class="las la-clock"></i> <span class="fw-normal">{{ Carbon\Carbon::parse($asingee->pivot->assigned_at)->format("h:i A") }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $asingee->pivot->addedBy->name }}</span> <br>
                                    <span class="fw-normal text-info">{{ $asingee->pivot->addedBy->email }}</span>
                                </td>
                                <td>
                                    <div class="fw-normal text-center">
                                        @if($asingee->pivot->is_active)
                                            <i class="las la-check-double la-2x text-success"></i>
                                        @else
                                            <i class="la la-times-circle la-2x text-danger"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('projects.members.status-toggle', [$project, $asingee->pivot->id]) }}" class="btn btn-sm @if($asingee->pivot->is_active) btn-outline-danger @else btn-outline-info @endif border">
                                        @if($asingee->pivot->is_active)
                                            <i class="las la-user-minus"></i> {{ __('Revoke Access') }}
                                        @else
                                            <i class="las la-user-plus"></i> {{ __('Give Access') }}
                                        @endif
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="100%">
                                    <span class="text-muted">
                                        {{ __('No members found.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AddNewMemberModal" aria-labelledby="AddNewMemberModalLabel" aria-hidden="false">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5 d-block text-center w-100" id="AddNewMemberModalLabel">
                    Add New Member
              </h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('projects.members.store', $project) }}" method="POST">
                    @csrf
                    <label class="required">Select User</label>
                    <div class="select-box">
                        <select name="user_id" class="users-select2 form-control form-select" required>
                            <option value="">-- Choose User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} {{ $user->email }}</option>
                            @endforeach
                        </select>
                    </div>

                    <br>

                    <button type="submit" class="btn btn-primary">Add Member</utton>
                </form>
            </div>
          </div>
        </div>
      </div>
@endsection
@section('scripts')
    <script>

        $(".users-select2").select2({
            dropdownParent: $('#AddNewMemberModal')
        });

        $("#search").on('keyup paste blur', function(){

            let _text = $(this).val();

            $("#members_list").find("tr").removeClass("d-none");

            if(_text == ""){
                return;
            }

            $("#members_list").find("tr").each(function(){
                let _name = $(this).find("td:eq(1) span.fw-bold").text();
                let _email = $(this).find("td:eq(1) span.fw-normal").text();

                if(!_name.toLowerCase().includes(_text.toLowerCase()) &&!_email.toLowerCase().includes(_text.toLowerCase())){
                    $(this).addClass("d-none");
                }
            });


        })

    </script>
@endsection
