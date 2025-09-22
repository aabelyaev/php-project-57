<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_statuses_is_displayed(): void
    {
        $response = $this->get('/task_statuses');

        $response->assertOk();
    }

    public function test_task_status_create_form_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/task_statuses/create');

        $response->assertOk();
    }

    public function test_user_can_create_task_status(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $response->assertRedirect('/task_statuses');

        $this->assertDatabaseHas('task_statuses', [
            'name' => 'in testing',
        ]);
    }

    public function test_user_cannot_duplicate_task_status(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $response = $this
            ->actingAs($user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $response->assertRedirectBack();
    }

    public function test_task_status_edit_form_is_displayed(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $response = $this->get('/task_statuses/1/edit');

        $response->assertOk();
    }

    public function test_user_can_update_task_status(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $response = $this
            ->actingAs($user)
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

    public function test_user_can_delete_task_status(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $this->assertDatabaseHas('task_statuses', [
            'name' => 'in testing',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/task_statuses/1');

        $response->assertRedirect('/task_statuses');

        $this->assertDatabaseMissing('task_statuses', [
            'name' => 'in testing',
        ]);
    }

    public function test_user_cannot_delete_task_status_if_it_is_associated_with_task(): void
    {
        $user = User::factory()->create();
        $assignedUser = User::factory()->create();

        $this
            ->actingAs($user)
            ->post('/task_statuses', [
                'name' => 'in testing',
            ]);

        $this
            ->actingAs($user)
            ->post('/tasks', [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => '1',
                'created_by_id' => $user->id,
                'assigned_to_id' => $assignedUser->id,
            ]);

        $this->assertDatabaseHas('task_statuses', [
            'name' => 'in testing',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/task_statuses/1');

        $response->assertRedirect('/task_statuses');

        $this->assertDatabaseHas('task_statuses', [
            'name' => 'in testing',
        ]);
    }
}
