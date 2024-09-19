<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{

     /**
     * Create a new controller instance.
     *
     * @param TaskService $taskService
     */
    public function __construct(protected TaskService $taskService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResource
    {
        $tasks = Task::with('project', 'user')
                        ->when($request->has('status'), function ($query) use ($request) {
                            $query->whereIn('status', [$request->input('status')]);
                        })
                        ->when($request->has('priority'), function ($query) use ($request) {
                            $query->whereIn('priority', [$request->input('priority')]);
                        })
                        ->get();
        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): JsonResource
    {
        $task = $this->taskService->createTask($request->validated());
        return TaskResource::make($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): mixed
    {
        try {
            $task = Task::with('project', 'user')->findOrFail($id);
            return TaskResource::make($task);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task not found or invalid content.',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $id): mixed
    {
        try {
            $task = Task::findOrFail($id);

            $taskUpdated = $this->taskService->updateTask($request->validated(), $task);

            return TaskResource::make($taskUpdated);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task not found or invalid content.',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        Task::destroy($id);
        return response()->json(null, 204);
    }
}
