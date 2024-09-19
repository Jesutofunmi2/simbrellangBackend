<?php

namespace App\Services;

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskService
{
  
    /**
     * Create a new task.
     *
     * @param array $data Array of task data including title, description, status, priorities.
     * 
     * @return task The newly created task instance.
     * 
     */
    public function createTask(array $data): Task
    {
        DB::beginTransaction();

        $task = new Task();
        $task->project_id = $data['project_id'];
        $task->assigned_user_id = $data['assigned_user_id'] ?? null;
        $task->title = $data['title'];
        $task->description = $data['description'];
        $task->status = $data['status'] ?? Status::TODO->value;
        $task->priority = $data['priority'] ?? Priority::LOW->value;
        $task->due_date = $data['due_date'] ?? null;
        $task->save();

        DB::commit();

        return $task;
    }

    /**
     * Update an existing Task with new data.
     *
     * @param array $data Array of Task data including product_id, assigned_user_id, title, description, status, priority, due_date etc
     * @param Task $Task The Task instance to be updated.
     * 
     * @return Task The updated Task instance.
     * 
     */
    public function updateTask(array $data, Task $task): Task
    {
        DB::beginTransaction();

        $task->update([
            "project_id" => $data['project_id'],
            "assigned_user_id" => $data['assigned_user_id']?? null,
            "title" => $data['title'],
            "description" => $data['description'],
            "status" => $data['status'] ?? Status::TODO->value,
            "priority" => $data['priority'] ?? Priority::LOW->value,
            "due_date" => $data['due_date'] ?? null,
        ]);

        DB::commit();

        return $task;
    }
}