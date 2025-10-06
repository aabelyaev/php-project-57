<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $assignedUser;
    private TaskStatus $taskStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->assignedUser = User::factory()->create();
        $this->taskStatus = TaskStatus::factory()->create();
    }

    public function testTasksIsDisplayed(): void
    {
        $response = $this->get(route('tasks.index'));
        $response->assertOk();
    }

    public function testTaskCreateFormIsDisplayed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('tasks.create'));

        $response->assertOk();
    }

    public function testUserCanCreateTask(): void
    {
        $this->assertDatabaseMissing('tasks', [
            'name' => 'Run tests',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->post(route('tasks.store'), [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'name' => 'Run tests',
        ]);
    }

    public function testUserCannotDuplicateTask(): void
    {
        Task::factory()->create([
            'name' => 'Run tests',
            'created_by_id' => $this->user->id,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->post(route('tasks.store'), [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response->assertInvalid(['name']);
    }

    public function testTaskIsDisplayed(): void
    {
        $task = Task::factory()->create([
            'created_by_id' => $this->user->id,
        ]);

        $response = $this->get(route('tasks.show', $task));
        $response->assertOk();
    }

    public function testTaskEditFormIsDisplayed(): void
    {
        $task = Task::factory()->create([
            'created_by_id' => $this->user->id,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->get(route('tasks.edit', $task));

        $response->assertOk();
    }

    public function testUserCanUpdateTask(): void
    {
        $task = Task::factory()->create([
            'name' => 'Run tests',
            'created_by_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'Run tests',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->patch(route('tasks.update', $task), [
                'name' => 'Review tests',
                'description' => 'Some description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', [
            'name' => 'Run tests',
        ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'Review tests',
        ]);
    }

    public function testUserCanDeleteYourTask(): void
    {
        $task = Task::factory()->create([
            'created_by_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function testUserCannotDeleteNotYourTask(): void
    {
        $task = Task::factory()->create([
            'created_by_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);

        $response = $this
            ->actingAs($this->assignedUser)
            ->delete(route('tasks.destroy', $task));

        $response->assertStatus(403);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);
    }
}
