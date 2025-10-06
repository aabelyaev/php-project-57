<?php

namespace Tests\Feature;

use App\Models\User;
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
        $response = $this->get('/task_statuses');

        $response->assertOk();
    }

    public function testTaskStatusCreateFormIsDisplayed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/task_statuses/create');

        $response->assertOk();
    }

    public function testUserCanCreateTaskStatus(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $response->assertRedirect('/task_statuses');

        $this->assertDatabaseHas('task_statuses', [
            'name' => 'in testing',
        ]);
    }

    public function testUserCannotDuplicateTaskStatus(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $response = $this
            ->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $response->assertRedirectBack();
    }

    public function testTaskStatusEditFormIsDisplayed(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $response = $this->get('/task_statuses/1/edit');

        $response->assertOk();
    }

    public function testUserCanUpdateTaskStatus(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $response = $this
            ->actingAs($this->user)
            ->patch('/task_statuses/1', [
                'name' => 'in reviewing',
            ]);

        $response->assertRedirect('/task_statuses');

        $this->assertDatabaseHas('task_statuses', [
            'name' => 'in reviewing',
        ]);

        $this->assertDatabaseMissing('task_statuses', [
            'name' => 'in testing',
        ]);
    }

    public function testUserCanDeleteTaskStatus(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $this->assertDatabaseHas('task_statuses', [
            'id' => '1',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete('/task_statuses/1');

        $response->assertRedirect('/task_statuses');

        $this->assertDatabaseMissing('task_statuses', [
            'id' => '1',
        ]);
    }

    public function testUserCannotDeleteTaskStatusIfItIsAssociatedWithTask(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => '1',
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->user->id,
            ]);

        $this->assertDatabaseHas('task_statuses', [
            'id' => '1',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete('/task_statuses/1');

        $response->assertRedirectBack();

        $this->assertDatabaseHas('task_statuses', [
            'id' => '1',
        ]);
    }
}
