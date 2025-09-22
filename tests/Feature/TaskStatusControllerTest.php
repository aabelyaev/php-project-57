<?php

namespace Tests;

use App\Models\Task;
use Tests\TestCase;

class TaskStatusControllerTest extends TestCase
{
    private array $validTaskStatusData;
    private array $invalidTaskStatusData;
    private array $patchedTaskStatusData;

    public function setUp(): void
    {
        parent::setUp();
        $this->validTaskStatusData =  ['name' => 'testStatusName'];
        $this->invalidTaskStatusData = ['name' => ''];
        $this->patchedTaskStatusData = ['name' => 'newName'];
    }

    public function testIndex(): void
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertOk();
    }

    public function testCreateWithoutAuthentication(): void
    {
        $response = $this->get(route('task_statuses.create'));
        $response->assertForbidden();
    }

    public function testCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('task_statuses.create'));
        $response->assertOk();
    }


    public function testStoreWithoutAuthentication(): void
    {
        $this->post(route('task_statuses.store'), $this->validTaskStatusData);
        $this->assertDatabaseMissing('task_statuses', $this->validTaskStatusData);
    }

    public function testStoreWithInvalidData(): void
    {
        $response = $this->actingAs($this->user)->post(route('task_statuses.store'), $this->invalidTaskStatusData);
        $response->assertInvalid(['name']);
    }


    public function testStore(): void
    {
        $response = $this->actingAs($this->user)->post(route('task_statuses.store'), $this->validTaskStatusData);
        $response->assertValid();
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', $this->validTaskStatusData);
    }

    public function testStoreNotUniqueStatus(): void
    {
        $response = $this->actingAs($this->user)->post(route('task_statuses.store'), $this->validTaskStatusData);
        $response->assertValid();
        $response = $this->actingAs($this->user)->post(route('task_statuses.store'), $this->validTaskStatusData);
        $response->assertInvalid(['name']);
    }


    public function testEditWithoutAuthentication(): void
    {
        $response = $this->get(route('task_statuses.edit', $this->status->id));
        $response->assertForbidden();
    }

    public function testEdit(): void
    {
        $response = $this->actingAs($this->user)->get(route('task_statuses.edit', $this->status->id));
        $response->assertOk();
    }

    public function testDeleteWithoutAuthentication(): void
    {
        $id = $this->status->id;
        $this->delete(route('task_statuses.destroy', $id));
        $this->assertDatabaseHas('task_statuses', ['id' =>  $id]);
    }

    public function testDeleteUsedStatus(): void
    {
        $id = $this->status->id;
        Task::factory()->create(['status_id' => $id]);
        $this->actingAs($this->user)->delete(route('task_statuses.destroy', $id));
        $this->assertDatabaseHas('task_statuses', ['id' =>  $id]);
    }

    public function testDelete(): void
    {
        $id = $this->status->id;
        $response = $this->actingAs($this->user)->delete(route('task_statuses.destroy', $id));
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseMissing('task_statuses', ['id' =>  $id]);
    }

    public function testUpdateWithoutAuthentication(): void
    {
        $this->patch(route('task_statuses.update', $this->status->id), $this->patchedTaskStatusData);
        $this->assertDatabaseMissing('task_statuses', $this->patchedTaskStatusData);
    }

    public function testUpdateWithInvalidData(): void
    {
        $response = $this->actingAs($this->user)
            ->patch(route('task_statuses.update', $this->status->id), $this->invalidTaskStatusData);
        $response->assertInvalid(['name']);
    }

    public function testUpdate(): void
    {
        $response = $this->actingAs($this->user)
            ->patch(route('task_statuses.update', $this->status->id), $this->patchedTaskStatusData);
        $response->assertValid();
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', $this->patchedTaskStatusData);
    }
}
