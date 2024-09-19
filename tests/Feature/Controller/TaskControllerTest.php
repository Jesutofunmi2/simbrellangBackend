<?php

namespace Tests\Feature;

use App\Mail\TaskUpdatedNotification;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $taskService;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(); 
        $this->taskService = $this->createMock(TaskService::class);
        Sanctum::actingAs($this->user);
    }

    public function test_it_can_fetch_all_tasks_without_filter()
    {
        $project = Project::factory()->create();

        Task::factory(3)->create(['project_id' => $project->id]);

        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data'); 
    }

    public function test_it_can_fetch_tasks_filtered_by_status()
    {
            $project = Project::factory()->create();

            Task::factory(2)->create(['status' => 'TODO', 'project_id' => $project->id]);
            Task::factory(3)->create(['status' => 'COMPLETED', 'project_id' => $project->id]);

            $response = $this->getJson('/api/v1/tasks?status=TODO');

            $response->assertStatus(200)
                ->assertJsonCount(2, 'data'); 
    }

    public function test_it_can_create_a_task()
    {
        Mail::fake();

        $project = Project::factory()->create();

        $taskServiceMock = Mockery::mock(TaskService::class);
        $this->app->instance(TaskService::class, $taskServiceMock);

        $taskServiceMock->shouldReceive('createTask')
        ->once()
        ->andReturn(new Task([
            'title' => 'Test Task',
            'status' => 'TODO',
            'priority' => 'HIGH',
            'project_id' => $project->id,
            'assigned_user_id' => $this->user->id,
        ]));
        
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Task description',
            'status' => 'TODO',
            'priority' => 'HIGH',
            'assigned_user_id' => $this->user->id,
            'project_id' => $project->id,
        ];

        $response = $this->postJson('/api/v1/tasks', $taskData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'Test Task',
                    'status' => 'TODO'
                ]
            ]);

        Mail::assertQueued(TaskUpdatedNotification::class, function ($mail) {
            return $mail->hasTo($this->user->email);
        });
    }

    public function test_it_can_show_a_single_task()
    {
        $project = Project::factory()->create(); 
        $task = Task::factory()->create(['project_id' => $project->id]);

        $response = $this->getJson('/api/v1/tasks/' . $task->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title
                ]
            ]);
    }

    public function test_it_returns_not_found_when_task_does_not_exist()
    {
        $response = $this->getJson('/api/v1/tasks/invalid-id');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Task not found or invalid content.'
            ]);
    }

    public function test_task_validation_fails()
    {
        $response = $this->postJson('/api/v1/tasks', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'description']);
    }
    
    public function test_it_can_delete_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson('/api/v1/tasks/' . $task->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

}
