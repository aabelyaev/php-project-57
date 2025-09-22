<?php

namespace Tests;

use Tests\TestCase;

class LabelControllerTest extends TestCase
{
    private array $validLabelData;
    private array $invalidLabelData;
    private array $patchedLabelData;

    public function setUp(): void
    {
        parent::setUp();
        $this->validLabelData = ['name' => 'testName'];
        $this->invalidLabelData = ['name' => ''];
        $this->patchedLabelData = ['name' => 'newName'];
    }

    public function testIndex(): void
    {
        $response = $this->get(route('labels.index'));
        $response->assertOk();
    }

    public function testCreateWithoutAuthentication(): void
    {
        $response = $this->get(route('labels.create'));
        $response->assertForbidden();
    }

    public function testCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('labels.create'));
        $response->assertOk();
    }


    public function testStoreWithoutAuthentication(): void
    {
        $this->post(route('labels.store', $this->validLabelData));
        $this->assertDatabaseMissing('labels', $this->validLabelData);
    }

    public function testStoreWithInvalidName(): void
    {
        $response = $this->actingAs($this->user)->post(route('labels.store', $this->invalidLabelData));
        $response->assertInvalid(['name']);
    }


    public function testStoreWithNotUniqueName(): void
    {
        $response = $this->actingAs($this->user)->post(route('labels.store', $this->validLabelData));
        $response->assertValid();
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', $this->validLabelData);
        $response = $this->actingAs($this->user)->post(route('labels.store', $this->validLabelData));
        $response->assertInvalid(['name']);
    }

    public function testEditWithoutAuthentication(): void
    {
        $response = $this->get(route('labels.edit', $this->label->id));
        $response->assertForbidden();
    }

    public function testEdit(): void
    {
        $response = $this->actingAs($this->user)->get(route('labels.edit', $this->label->id));
        $response->assertOk();
    }

    public function testDeleteWithoutAuthentication(): void
    {
        $this->delete(route('labels.destroy', $this->label->id));
        $this->assertDatabaseHas('labels', ['id' => $this->label->id]);
    }

    public function testDeleteLabelWithAttachedTasks(): void
    {
        $id = $this->label->id;
        $this->label->tasks()->attach([$this->task]);
        $this->actingAs($this->user)->delete(route('labels.destroy', $id));
        $this->assertDatabaseHas('labels', ['id' => $id]);
    }

    public function testDelete(): void
    {
        $id = $this->label->id;
        $response = $this->actingAs($this->user)->delete(route('labels.destroy', $id));
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseMissing('labels', ['id' => $id]);
    }

    public function testUpdateWithoutAuthentication(): void
    {
        $this->patch(route('labels.update', $this->label->id), $this->invalidLabelData);
        $this->assertDatabaseMissing('labels', $this->invalidLabelData);
    }

    public function testUpdateWithInvalidData(): void
    {
        $response = $this->actingAs($this->user)
            ->patch(route('labels.update', $this->label->id), $this->invalidLabelData);
        $response->assertInvalid(['name']);
    }

    public function testUpdate(): void
    {
        $response = $this->actingAs($this->user)
            ->patch(route('labels.update', $this->label->id), $this->patchedLabelData);
        $response->assertValid();
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', $this->patchedLabelData);
    }
}
