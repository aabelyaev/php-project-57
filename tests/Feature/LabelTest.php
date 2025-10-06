<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testLabelsIsDisplayed(): void
    {
        $response = $this->get('/labels');

        $response->assertOk();
    }

    public function testLabelCreateFormIsDisplayed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/labels/create');

        $response->assertOk();
    }

    public function testUserCanCreateLabel(): void
    {
        $this->assertDatabaseMissing('labels', [
            'name' => 'Testing',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->post('/labels', [
                'name' => 'Testing',
                'description' => 'Some description',
            ]);

        $response->assertRedirect('/labels');

        $this->assertDatabaseHas('labels', [
            'name' => 'Testing',
        ]);
    }

    public function testUserCannotDuplicateLabel(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/labels', [
                'name' => 'Testing',
                'description' => 'Some description',
            ]);

        $response = $this
            ->actingAs($this->user)
            ->post('/labels', [
                'name' => 'Testing',
                'description' => 'Some description',
            ]);

        $response->assertRedirectBack();
    }

    public function testLabelEditFormIsDisplayed(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/labels', [
                'name' => 'Testing',
                'description' => 'Some description',
            ]);

        $response = $this
            ->actingAs($this->user)
            ->get('/labels/1/edit');

        $response->assertOk();
    }

    public function testUserCanUpdateLabel(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/labels', [
                'name' => 'Testing',
                'description' => 'Some description',
            ]);

        $this->assertDatabaseHas('labels', [
            'name' => 'Testing',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->patch('/labels/1', [
                'name' => 'Review',
                'description' => 'Some description',
            ]);

        $response->assertRedirect('/labels');

        $this->assertDatabaseMissing('labels', [
            'name' => 'Testing',
        ]);

        $this->assertDatabaseHas('labels', [
            'name' => 'Review',
        ]);
    }

    public function testUserCanDeleteLabel(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/labels', [
                'name' => 'Testing',
                'description' => 'Some description',
            ]);

        $this->assertDatabaseHas('labels', [
            'id' => '1',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete('/labels/1');

        $response->assertRedirect('/labels');

        $this->assertDatabaseMissing('labels', [
            'id' => '1',
        ]);
    }

    public function testUserCannotDeleteLabelIfItIsAssociatedWithTask(): void
    {
        $taskStatus = TaskStatus::factory()->create();

        $this
            ->actingAs($this->user)
            ->post('/labels', [
                'name' => 'Testing',
                'description' => 'Some description',
            ]);

        $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => $taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->user->id,
                'labels' => [1],
            ]);

        $this->assertDatabaseHas('labels', [
            'id' => '1',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete('/labels/1');

        $response->assertRedirectBack();

        $this->assertDatabaseHas('labels', [
            'id' => '1',
        ]);
    }
}
