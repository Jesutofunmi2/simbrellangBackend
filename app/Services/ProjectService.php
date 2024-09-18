<?php

namespace App\Services;

use App\Actions\FileStorage;
use App\Enums\Status;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    /**
     * Create a new Project and upload associated files.
     *
     * @param array $data Array of project data including name, description, and status.
     * 
     * @return Project The newly created project instance.
     * 
     */
    public function createProject(array $data): Project
    {
        $upload = new FileStorage;
        DB::beginTransaction();

        $project = new Project();
        $project->name = $data['name'];
        $project->description = $data['description'];
        $project->status = $data['status'] ?? Status::TODO->value;
        $project->save();

        $fileArray = $data['files'];

        if (!empty($fileArray)) {
            foreach ($fileArray as $file) {
                $upload->upload($file, $project->id);
            }
        }

        DB::commit();

        return $project;
    }

    /**
     * Update an existing Project with new data.
     *
     * @param array $data Array of Project data including name, description, price, and stock.
     * @param Project $Project The Project instance to be updated.
     * 
     * @return Project The updated Project instance.
     * 
     */
    public function updateProject(array $data, Project $project): Project
    {
        DB::beginTransaction();

        $project->update([
            "name" => $data['name'],
            "description" => $data['description'],
            "status" => $data['status'] ?? Status::TODO->value,
        ]);

        $upload = new FileStorage;
        $fileArray = $data['files'] ?? [];

        if (!empty($fileArray)) {
            foreach ($fileArray as $file) {
                $upload->upload($file, $project->id);
            }
        }

        DB::commit();

        return $project;
    }
}
