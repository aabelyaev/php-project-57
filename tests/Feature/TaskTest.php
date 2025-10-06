<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
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
        $response = $this->get('/tasks');
        $response->assertOk();
    }

    public function testTaskCreateFormIsDisplayed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/tasks/create');

        $response->assertOk();
    }

    public function testUserCanCreateTask(): void
    {
        $this->assertDatabaseMissing('tasks', [
            'name' => 'Run tests',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', [
            'name' => 'Run tests',
        ]);
    }

    public function testUserCannotDuplicateTask(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response = $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response->assertRedirectBack();
    }

    public function testTaskIsDisplayed(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response = $this->get('/tasks/1');
        $response->assertOk();
    }

    public function testTaskEditFormIsDisplayed(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response = $this
            ->actingAs($this->user)
            ->get('/tasks/1/edit');

        $response->assertOk();
    }

    public function testUserCanUpdateTask(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'Run tests',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->patch('/tasks/1', [
                'name' => 'Review tests',
                'description' => 'Some description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response->assertRedirect('/tasks');

        $this->assertDatabaseMissing('tasks', [
            'name' => 'Run tests',
        ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'Review tests',
        ]);
    }

    public function testUserCanDeleteYourTask(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => '1',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete('/tasks/1');

        $response->assertRedirect('/tasks');

        $this->assertDatabaseMissing('tasks', [
            'id' => '1',
        ]);
    }

    public function testUserCannotDeleteNotYourTask(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => '1',
        ]);

        $response = $this
            ->actingAs($this->assignedUser)
            ->delete('/tasks/1');

        $response->assertStatus(403);

        $this->assertDatabaseHas('tasks', [
            'id' => '1',
        ]);
    }
}
