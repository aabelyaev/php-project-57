<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TaskStatus;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testTaskStatusesIsDisplayed(): void
    {
        $response = $this->get(route('task_statuses.index'));

        $response->assertOk();
    }

    public function testTaskStatusCreateFormIsDisplayed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('task_statuses.create'));

        $response->assertOk();
    }

    public function testUserCanCreateTaskStatus(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->post(route('task_statuses.store'), [
                'name' => 'in testing',
            ]);

        $response->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseHas('task_statuses', [
            'name' => 'in testing',
        ]);
    }

    public function testUserCannotDuplicateTaskStatus(): void
    {
        TaskStatus::factory()->create(['name' => 'in testing']);

        $response = $this
            ->actingAs($this->user)
            ->post(route('task_statuses.store'), [
                'name' => 'in testing',
            ]);

        $response->assertInvalid(['name']);
    }

    public function testTaskStatusEditFormIsDisplayed(): void
    {
        $taskStatus = TaskStatus::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('task_statuses.edit', $taskStatus));

        $response->assertOk();
    }

    public function testUserCanUpdateTaskStatus(): void
    {
        $taskStatus = TaskStatus::factory()->create(['name' => 'in testing']);

        $response = $this
            ->actingAs($this->user)
            ->patch(route('task_statuses.update', $taskStatus), [
                'name' => 'in reviewing',
            ]);

        $response->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseHas('task_statuses', [
            'name' => 'in reviewing',
        ]);

        $this->assertDatabaseMissing('task_statuses', [
            'name' => 'in testing',
        ]);
    }

    public function testUserCanDeleteTaskStatus(): void
    {
        $taskStatus = TaskStatus::factory()->create();

        $this->assertDatabaseHas('task_statuses', [
            'id' => $taskStatus->id,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete(route('task_statuses.destroy', $taskStatus));

        $response->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseMissing('task_statuses', [
            'id' => $taskStatus->id,
        ]);
    }

    public function testUserCannotDeleteTaskStatusIfItIsAssociatedWithTask(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        $task = Task::factory()->create([
            'status_id' => $taskStatus->id,
            'created_by_id' => $this->user->id,
            'assigned_to_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('task_statuses', [
            'id' => $taskStatus->id,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete(route('task_statuses.destroy', $taskStatus));

        $response->assertRedirect();

        $this->assertDatabaseHas('task_statuses', [
            'id' => $taskStatus->id,
        ]);
    }
}
