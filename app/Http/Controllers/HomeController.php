<?php

namespace App\Http\Controllers;

use App\Models\WorkLog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $user = auth()->user();


        $worklogs = WorkLog::where('user_id', $user->id)
                            ->with('project')
                            ->whereDate('date_of_work', now())
                            ->orderby('date_of_work', 'desc')
                            ->orderby('work_duration_in_minutes', 'desc')
                            ->get();

        $total_hour_logged = WorkLog::where('user_id', $user->id)->sum('work_duration_in_minutes');
        $total_hour_logged_today = WorkLog::where('user_id', $user->id)->whereDate('date_of_work', now())->sum('work_duration_in_minutes');
        $total_hour_logged_this_month = WorkLog::where('user_id', $user->id)
                                        ->whereBetween('date_of_work', [now()->startOfMonth(), now()->endOfMonth()])
                                        ->sum('work_duration_in_minutes');
        $total_hour_logged_this_year = WorkLog::where('user_id', $user->id)
                                        ->whereBetween('date_of_work', [now()->startOfYear(), now()->endOfYear()])
                                        ->sum('work_duration_in_minutes');

        return view('home', compact("worklogs", "user", "total_hour_logged", "total_hour_logged_today", "total_hour_logged_this_month", "total_hour_logged_this_year"));
    }
}
