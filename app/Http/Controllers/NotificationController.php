<?php

namespace App\Http\Controllers;

use App\Mail\TaskUpdatedNotification;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    /**
     * Trigger email notifications for a task.
     *
     * @param  Request  $request
     * @param  Task  $task
     * @return JsonResponse
     */
    public function sendTaskNotification(Request $request, Task $task): JsonResponse
    {
        if ($task->assigned_user_id) {
            $user = $task->user;

            Mail::to($user->email)->send(new TaskUpdatedNotification($task));

            return response()->json([
                'message' => 'Notification sent successfully.',
                'data' => $task
            ], 200);
        }

        return response()->json([
            'message' => 'Task does not have an assigned user.'
        ], 400);
    }
}
