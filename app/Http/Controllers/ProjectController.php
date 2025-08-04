<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project = Project::get();
        return response()->json($project);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $project = new Project();
       $project->name = $request->input('name');
       $project->description = $request->input('description');
       $project->save();

       return response()->json($project, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::find($id);

        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::find($id);
        $project->name=$request->name;
        $project->description=$request->description;
        $project->save();
        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       Project::destroy($id);
       return response()->json(['message' => 'Project deleted successfully'], 200);
    }
}
