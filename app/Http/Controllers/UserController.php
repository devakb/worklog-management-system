<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkLog;
use App\Http\Requests\Users\CreateRequest;
use App\Http\Requests\Users\UpdateRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
        ->when($request->filled('search'), function($q) use($request){
            $q->where(function($q) use ($request){
                $q->where("name", 'like' , "%" . $request->get('search') . "%")
                ->orWhere("email", 'like', "%" . $request->get('search') . "%");
            });
        })
        ->when($request->filled('designation'), function($q) use($request){
            $q->where("designation", $request->get('designation'));
        })
        ->when($request->filled('is_active'), function($q) use($request){
            $q->where("is_active", $request->get('is_active'));
        })
        ->latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(CreateRequest $request)
    {
        User::create($request->validated() + ['is_admin' => $request->designation == "Project Manager"]);

        return to_route('users.index');
    }

    public function show(User $user)
    {

        $worklogs = WorkLog::where('user_id', $user->id)
            ->with('project')
            ->orderby('date_of_work', 'desc')
            ->orderby('work_duration_in_minutes', 'desc')
            ->paginate(10);

        $total_hour_logged = WorkLog::where('user_id', $user->id)->sum('work_duration_in_minutes');
        $total_hour_logged_today = WorkLog::where('user_id', $user->id)->whereDate('date_of_work', now())->sum('work_duration_in_minutes');
        $total_hour_logged_this_month = WorkLog::where('user_id', $user->id)
                                        ->whereBetween('date_of_work', [now()->startOfMonth(), now()->endOfMonth()])
                                        ->sum('work_duration_in_minutes');
        $total_hour_logged_this_year = WorkLog::where('user_id', $user->id)
                                        ->whereBetween('date_of_work', [now()->startOfYear(), now()->endOfYear()])
                                        ->sum('work_duration_in_minutes');

        return view('users.show', compact('user', 'worklogs', 'total_hour_logged', 'total_hour_logged_today', 'total_hour_logged_this_month', 'total_hour_logged_this_year'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }


    public function update(UpdateRequest $request, User $user)
    {

        $data = $request->validated();

        if($request->password == null){
            unset($data['password']);
        }

        $user->update($data + ['is_admin' => $request->designation == "Project Manager"]);

        return to_route('users.index');
    }

    public function statusToggle(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        return back();
    }
}
