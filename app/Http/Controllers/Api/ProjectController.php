<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param ProjectService $projectService
     */
    public function __construct(protected ProjectService $projectService) {}
    /**
     * Display a listing of the resource also filter by status.
     */
    public function index(Request $request): JsonResource
    {
        $projects = Project::with('tasks')
            ->when($request->has('status'), function ($query) use ($request) {
                $query->whereIn('status', [$request->input('status')]);
            })
            ->get();

        return ProjectResource::collection($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request): JsonResource
    {
        $project = $this->projectService->createProject($request->validated());
        return ProjectResource::make($project);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): mixed
    {
        try {
            $project = Project::with('tasks')->findOrFail($id);
            return ProjectResource::make($project);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Project not found or invalid content.',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, string $id): mixed
    {
        try {
            $project = Project::findOrFail($id);

            $productUpdated = $this->projectService->updateProject($request->validated(), $project);

            return ProjectResource::make($productUpdated);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Project not found or invalid content.',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        Project::destroy($id);
        return response()->noContent();
    }
}
