<?php

namespace Tests;

use App\Models\User;
use App\Models\TaskStatus;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    private array $validTaskData;
    private array $invalidTaskData;
    private array $patchedTaskData;

    public function setUp(): void
    {
        parent::setUp();
        $this->validTaskData = ['name' => 'taskName', 'status_id' => $this->status->id];
        $this->invalidTaskData = ['name' => '', 'status_id' => ''];
        $newStatus = TaskStatus::factory()->create();
        $this->patchedTaskData = ['name' => 'newName', 'status_id' => $newStatus->id];
    }

    public function testIndex(): void
    {
        $response = $this->get(route('tasks.index'));
        $response->assertOk();
    }

    public function testCreateWithoutAuthentication(): void
    {
        $response = $this->get(route('tasks.create'));
        $response->assertForbidden();
    }
    public function testCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('tasks.create'));
        $response->assertOk();
    }


    public function testStoreWithoutAuthentication(): void
    {
        $response = $this->post(route('tasks.store'), $this->validTaskData);
        $this->assertDatabaseMissing('tasks', $this->validTaskData);
    }

    public function testStoreWithInvalidData(): void
    {
        $response = $this->actingAs($this->user)->post(route('tasks.store'), $this->invalidTaskData);
        $response->assertInvalid(['name', 'status_id']);
    }

    public function testStore(): void
    {
        $response = $this->actingAs($this->user)->post(route('tasks.store'), $this->validTaskData);
        $response->assertValid();
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', $this->validTaskData);
    }

    public function testEditWithoutAuthentication(): void
    {
        $response = $this->get(route('tasks.edit', $this->task->id));
        $response->assertForbidden();
    }

    public function testEdit(): void
    {
        $response = $this->actingAs($this->user)->get(route('tasks.edit', $this->task->id));
        $response->assertOk();
    }


    public function testDeleteWithoutAuthentication(): void
    {
        $response = $this->delete(route('tasks.destroy', $this->task->id));
        $response->assertForbidden();
    }

    public function testDeleteWithNotCreatorAuthentication(): void
    {
        $otherUser = User::factory()->create();
        $response = $this->actingAs($otherUser)->delete(route('tasks.destroy', $this->task->id));
        $response->assertForbidden();
    }

    public function testDeleteWithCreatorAuthentication(): void
    {
        $id = $this->task->id;
        $response = $this->actingAs($this->task->creator)->delete(route('tasks.destroy', $id));
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $id]);
    }


    public function testUpdateWithoutAuthentication(): void
    {
        $this->patch(route('tasks.update', $this->task->id), $this->patchedTaskData);
        $this->assertDatabaseMissing('tasks', $this->patchedTaskData);
    }

    public function testUpdateWithInvalidData(): void
    {
        $response = $this->actingAs($this->user)->patch(route('tasks.update', $this->task->id), $this->invalidTaskData);
        $response->assertInvalid(['name', 'status_id']);
    }

    public function testUpdate(): void
    {
        $response = $this->actingAs($this->user)->patch(route('tasks.update', $this->task->id), $this->patchedTaskData);
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', $this->patchedTaskData);
    }
}
