<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\ProjectAsignee;
use App\Http\Requests\Projects\CreateRequest;
use App\Http\Requests\Projects\UpdateRequest;

class ProjectController extends Controller
{

    public function index(Request $request)
    {
        $projects = Project::withCount('asignees')->when($request->filled('search'), function($q) use($request){
            $q->where('full_name', 'like', "%{$request->search}%")
            ->orWhere('code', 'like', "%{$request->search}%");
        })->latest()->paginate(5);

        return view('projects.index', compact('projects'));
    }


    public function create()
    {
        return view('projects.create');
    }


    public function store(CreateRequest $request)
    {
        Project::create($request->validated());

        return to_route('projects.index');
    }


    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }


    public function update(UpdateRequest $request, Project $project)
    {
        $project->update($request->validated());

        return to_route('projects.index');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return to_route('projects.index');
    }


    public function members(Project $project)
    {
        $project->load(['asignees']);

        $users = User::whereNotIn("id", $project->asignees->pluck('id')->toArray())->get();

        return view('projects.members', compact('project', 'users'));
    }

    public function membersStatusToggle(Project $project, ProjectAsignee $projectAsignee)
    {

        $projectAsignee->update(['is_active' => !$projectAsignee->is_active]);

       return to_route('projects.members.index', $project);
    }


    public function members_store(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => "required|exists:users,id",
        ]);

        $project->asignees()->syncWithoutDetaching([$request->user_id => [
            "added_by_id" => auth()->id(),
        ]]);


       return to_route('projects.members.index', $project);
    }

}
