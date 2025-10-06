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

        // Проверяем ВСЕ важные поля задачи
        $this->assertDatabaseHas('tasks', [
            'name' => 'Run tests',
            'description' => 'Some description',
            'status_id' => $this->taskStatus->id,
            'created_by_id' => $this->user->id,
            'assigned_to_id' => $this->assignedUser->id,
        ]);

        // Дополнительная строгая проверка
        $task = Task::where('name', 'Run tests')
            ->where('created_by_id', $this->user->id)
            ->where('assigned_to_id', $this->assignedUser->id)
            ->first();

        $this->assertNotNull($task);
        $this->assertEquals('Some description', $task->description);
        $this->assertEquals($this->taskStatus->id, $task->status_id);
    }

    public function testUserCannotDuplicateTask(): void
    {
        Task::factory()->create([
            'name' => 'Run tests',
            'created_by_id' => $this->user->id,
            'assigned_to_id' => $this->assignedUser->id,
            'status_id' => $this->taskStatus->id,
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
            'name' => 'Run tests',
            'description' => 'Task description',
            'status_id' => $this->taskStatus->id,
            'created_by_id' => $this->user->id,
            'assigned_to_id' => $this->assignedUser->id,
        ]);

        $response = $this->get(route('tasks.show', $task));
        $response->assertOk();

        // Проверяем, что на странице отображаются данные задачи
        $response->assertSee('Run tests');
        $response->assertSee('Task description');
    }

    public function testTaskEditFormIsDisplayed(): void
    {
        $task = Task::factory()->create([
            'created_by_id' => $this->user->id,
            'assigned_to_id' => $this->assignedUser->id,
            'status_id' => $this->taskStatus->id,
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
            'description' => 'Old description',
            'created_by_id' => $this->user->id,
            'assigned_to_id' => $this->assignedUser->id,
            'status_id' => $this->taskStatus->id,
        ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'Run tests',
            'description' => 'Old description',
            'created_by_id' => $this->user->id,
            'assigned_to_id' => $this->assignedUser->id,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->patch(route('tasks.update', $task), [
                'name' => 'Review tests',
                'description' => 'New description',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response->assertRedirect(route('tasks.index'));

        // Проверяем, что старые данные удалены
        $this->assertDatabaseMissing('tasks', [
            'name' => 'Run tests',
            'description' => 'Old description',
        ]);

        // Проверяем, что новые данные сохранены
        $this->assertDatabaseHas('tasks', [
            'name' => 'Review tests',
            'description' => 'New description',
            'created_by_id' => $this->user->id,
            'assigned_to_id' => $this->assignedUser->id,
            'status_id' => $this->taskStatus->id,
        ]);

        // Дополнительная проверка обновленной задачи
        $updatedTask = Task::find($task->id);
        $this->assertEquals('Review tests', $updatedTask->name);
        $this->assertEquals('New description', $updatedTask->description);
        $this->assertEquals($this->user->id, $updatedTask->created_by_id);
        $this->assertEquals($this->assignedUser->id, $updatedTask->assigned_to_id);
    }

    public function testUserCanDeleteYourTask(): void
    {
        $task = Task::factory()->create([
            'name' => 'Run tests',
            'created_by_id' => $this->user->id,
            'assigned_to_id' => $this->assignedUser->id,
            'status_id' => $this->taskStatus->id,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Run tests',
            'created_by_id' => $this->user->id,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
            'name' => 'Run tests',
        ]);

        // Проверяем, что задача действительно удалена
        $this->assertNull(Task::find($task->id));
    }

    public function testUserCannotDeleteNotYourTask(): void
    {
        $task = Task::factory()->create([
            'name' => 'Run tests',
            'description' => 'Task description',
            'created_by_id' => $this->user->id,
            'assigned_to_id' => $this->assignedUser->id,
            'status_id' => $this->taskStatus->id,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Run tests',
            'created_by_id' => $this->user->id,
        ]);

        $response = $this
            ->actingAs($this->assignedUser)
            ->delete(route('tasks.destroy', $task));

        $response->assertStatus(403);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Run tests',
            'created_by_id' => $this->user->id,
            'assigned_to_id' => $this->assignedUser->id,
        ]);

        // Проверяем, что задача НЕ удалена
        $this->assertNotNull(Task::find($task->id));
    }
}
