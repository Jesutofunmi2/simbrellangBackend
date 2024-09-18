<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function assignUserToProject(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $user = User::findOrFail($request->user_id);

        $project->users()->attach($user->id, ['role' => $request->role]);

        return response()->json([
            'message' => 'User assigned to project successfully',
            'data' => $project->users
        ], 201);
    }

    public function removeUserFromProject($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);
        $project->users()->detach($userId);

        return response()->json([
            'message' => 'User removed from project successfully',
        ], 200);
    }
}
