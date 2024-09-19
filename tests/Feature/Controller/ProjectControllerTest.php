<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(); 
        Sanctum::actingAs($this->user);
    }

    public function test_it_can_fetch_all_projects_without_filter()
    {
        Project::factory(3)->create();
        $response = $this->getJson('/api/v1/projects');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data'); 
    }

    public function test_it_can_fetch_projects_filtered_by_status()
    {
        $projectsTodo = Project::factory(2)->create(['status' => 'TODO']);
        Project::factory(3)->create(['status' => 'COMPLETED']);

        $response = $this->getJson('/api/v1/projects?status=TODO');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data'); 
    }


    public function test_it_can_create_a_project()
    {
        $file = UploadedFile::fake()->image('product-image.jpg');
        $projectData = [
            'name' => 'Test Project',
            'description' => 'Project description',
            'status' => 'TODO',
            'files' => [$file] 
        ];

        $response = $this->postJson('/api/v1/projects', $projectData);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'Test Project',
                    'status' => 'TODO'
                ]
            ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'status' => 'TODO'
        ]);
    }

    public function test_it_can_show_a_single_project(): void
    {
        $project = Project::factory()->create();

        $response = $this->getJson('/api/v1/projects/' . $project->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $project->id,
                    'name' => $project->name
                ]
            ]);
    }

    public function test_it_returns_not_found_when_project_does_not_exist(): void
    {
        $response = $this->getJson('/api/v1/projects/invalid-id');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Project not found or invalid content.'
            ]);
    }
    
    public function test_project_validation_fails()
    {
        $response = $this->postJson( '/api/v1/projects/',  []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'description']);
    } 

    public function test_it_can_update_a_project(): void
    {
        $project = Project::factory()->create();

        $updateData = [
            'name' => 'Updated Project Name',
            'description' => 'Updated Project Name',
            'status' => 'PROGRESS'
        ];

        $response = $this->putJson('/api/v1/projects/' . $project->id, $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'Updated Project Name',
                    'description' => 'Updated Project Name',
                    'status' => 'PROGRESS'
                ]
            ]);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Project Name',
            'status' => 'PROGRESS'
        ]);
    }

    public function test_it_can_delete_a_project(): void
    {
        $project = Project::factory()->create();

        $response = $this->deleteJson('/api/v1/projects/' . $project->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
