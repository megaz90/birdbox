<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('projects.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
        ]);

        Auth::user()->projects()->create($attributes);

        return redirect('/projects');
    }

    public function show(Project $project)
    {
        if (auth()->user()->id != $project->owner->id) {
            abort(403);
        }
        return view('projects.show', compact('project'));
    }
}
