<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkLogs\UpdateRequest;
use App\Models\User;
use App\Models\Project;
use App\Models\WorkLog;
use Illuminate\Http\Request;
use App\Models\ProjectAsignee;
use App\Http\Requests\WorkLogs\CreateRequest;
use Carbon\Carbon;

class WorkLogController extends Controller
{

    public function index(Request $request)
    {
        // $worklogs = WorkLog::selectRaw("user_id, date_of_work, GROUP_CONCAT(DISTINCT project_id) as project_ids, SUM(work_duration_in_minutes) as work_duration_in_minutes")
        //     ->groupBy("date_of_work", "user_id")
        //     ->orderby("date_of_work", "DESC")
        //     ->paginate(10);


         $from = Carbon::parse($request->input("from", now()->format("Y-m-d")));
         $to   = Carbon::parse($request->input("to", now()->format("Y-m-d")));


         $worklogs = WorkLog::selectRaw("user_id, GROUP_CONCAT(DISTINCT date_of_work) as work_dates, GROUP_CONCAT(DISTINCT project_id) as project_ids, SUM(work_duration_in_minutes) as work_duration_in_minutes")
            ->groupBy("user_id")
            ->orderby("work_duration_in_minutes", "DESC")
            ->whereBetween('date_of_work', [$from, $to])
            ->when($request->filled('search'), function($q) use ($request){
                $q->whereHas('user', function($q) use ($request){
                    $q->where("email", $request->input("search"))
                        ->orWhere('name', $request->input("search"));
                });
            })
            ->when($request->filled('project_ids'), function($q) use ($request){
                if(is_array($request->project_ids)){
                    $q->whereIn('project_id', $request->project_ids);
                }
            })
            ->when(!auth()->user()->is_admin, function($q) use ($request){
                $q->where('user_id', auth()->id());
            })
            ->paginate(10);

        // Process the paginated results
        $worklogs->getCollection()->transform(function($log) {
            $pids = explode(',', $log->project_ids);
            $workdates = explode(',', $log->work_dates);
            $log->user = User::find($log->user_id);
            $log->projects = Project::whereIn('id', $pids)->get();
            $log->work_duration = (new WorkLog)->calculateWorkDuration($log->work_duration_in_minutes);
           // $log->date_of_work = Carbon::parse($log->date_of_work);
            if(count($workdates) == 1){
                $log->work_dates = Carbon::parse($workdates[0])->format('M d, Y');
                $log->start_work_date = Carbon::parse($workdates[0])->format('Y-m-d H:i:s');
            }else{

                $log->work_dates = Carbon::parse($workdates[0])->format('M d, Y') . " -> " . Carbon::parse($workdates[count($workdates) - 1])->format('M d, Y');
                $log->start_work_date = Carbon::parse($workdates[0])->format('Y-m-d H:i:s');
            }

            return $log;
        });

        if(!auth()->user()->is_admin){
            $projects = auth()->user()->projects;
        }else{
            $projects = Project::all();
        }


        return view('worklog.index', compact('worklogs', 'projects'));
    }


    public function create()
    {

        $projects = Project::whereIn(
            'id',
            ProjectAsignee::where('user_id', auth()->id())->where('is_active', 1)->pluck('project_id')->toArray()
        )->get();


        return view('worklog.create', compact('projects'));
    }


    public function store(CreateRequest $request)
    {

        $work_log = WorkLog::create($request->validated() + [
            'user_id' => auth()->id(),
        ]);

        $work_log->update(["work_duration_in_minutes" => $request->work_duration_in_minutes * 60]);

        return to_route('work-logs.index');
    }


    public function visualize(Request $request, User $user)
    {

        $from = Carbon::parse($request->input("from", now()->format("Y-m-d")));
        $to   = Carbon::parse($request->input("to", now()->format("Y-m-d")));

        $worklogs = WorkLog::where('user_id', $user->id)
                            ->with('project')
                            ->whereBetween('date_of_work', [$from, $to])
                            ->orderby('date_of_work', 'desc')
                            ->orderby('work_duration_in_minutes', 'desc')
                            ->get();

        return view('worklog.visualize', compact('user', 'worklogs'));
    }

    public function edit(WorkLog $work_log)
    {


        if($work_log->user_id != auth()->id()){
            abort(403);
        }

        $projects = Project::all();
        return view('worklog.edit', compact('work_log', 'projects'));
    }


    public function update(UpdateRequest $request, WorkLog $work_log)
    {
        $work_log->update($request->validated());

        $work_log->update(["work_duration_in_minutes" => $request->work_duration_in_minutes * 60]);

        return back();
    }


    public function destroy(WorkLog $work_log)
    {

        $work_log->delete();

        return back();
    }
}
