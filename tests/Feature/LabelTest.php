<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use App\Models\Label;
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
        $response = $this->get(route('labels.index'));

        $response->assertOk();
    }

    public function testLabelCreateFormIsDisplayed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('labels.create'));

        $response->assertOk();
    }

    public function testUserCanCreateLabel(): void
    {
        $this->assertDatabaseMissing('labels', [
            'name' => 'Testing',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->post(route('labels.store'), [
                'name' => 'Testing',
                'description' => 'Some description',
            ]);

        $response->assertRedirect(route('labels.index'));

        $this->assertDatabaseHas('labels', [
            'name' => 'Testing',
        ]);
    }

    public function testUserCannotDuplicateLabel(): void
    {
        $this
            ->actingAs($this->user)
            ->post(route('labels.store'), [
                'name' => 'Testing',
                'description' => 'Some description',
            ]);

        $response = $this
            ->actingAs($this->user)
            ->post(route('labels.store'), [
                'name' => 'Testing',
                'description' => 'Some description',
            ]);

        $response->assertInvalid(['name']);
    }

    public function testLabelEditFormIsDisplayed(): void
    {
        $label = Label::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('labels.edit', $label));

        $response->assertOk();
    }

    public function testUserCanUpdateLabel(): void
    {
        $label = Label::factory()->create(['name' => 'Testing']);

        $this->assertDatabaseHas('labels', [
            'name' => 'Testing',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->patch(route('labels.update', $label), [
                'name' => 'Review',
                'description' => 'Some description',
            ]);

        $response->assertRedirect(route('labels.index'));

        $this->assertDatabaseMissing('labels', [
            'name' => 'Testing',
        ]);

        $this->assertDatabaseHas('labels', [
            'name' => 'Review',
        ]);
    }

    public function testUserCanDeleteLabel(): void
    {
        $label = Label::factory()->create();

        $this->assertDatabaseHas('labels', [
            'id' => $label->id,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete(route('labels.destroy', $label));

        $response->assertRedirect(route('labels.index'));

        $this->assertDatabaseMissing('labels', [
            'id' => $label->id,
        ]);
    }

    public function testUserCannotDeleteLabelIfItIsAssociatedWithTask(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        $label = Label::factory()->create();

        $this
            ->actingAs($this->user)
            ->post(route('tasks.store'), [
                'name' => 'Run tests',
                'description' => 'Some description',
                'status_id' => $taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->user->id,
                'labels' => [$label->id],
            ]);

        $this->assertDatabaseHas('labels', [
            'id' => $label->id,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete(route('labels.destroy', $label));

        $response->assertRedirect();

        $this->assertDatabaseHas('labels', [
            'id' => $label->id,
        ]);
    }
}
