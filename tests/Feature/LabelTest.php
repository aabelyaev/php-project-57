<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Тестирование функционала меток (Label)
 *
 * Включает тесты для:
 * - Отображения страниц (index, create, edit)
 * - Операций CRUD (создание, обновление, удаление)
 * - Валидации (запрет на дублирование)
 * - Проверки бизнес-правил (запрет удаления связанной с задачей метки)
 *
 * @covers \App\Models\Label
 */
class LabelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Пользователь для выполнения аутентифицированных запросов
     * @var User
     */
    private User $user;

    /**
     * Настройка тестовой среды
     * Выполняется перед каждым тестом
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Тест: Страница со списком меток отображается корректно
     * @return void
     */
    public function testLabelsIsDisplayed(): void
    {
        $response = $this->get(route('labels.index'));
        $response->assertOk();
    }

    /**
     * Тест: Форма создания метки отображается для аутентифицированного пользователя
     * @return void
     */
    public function testLabelCreateFormIsDisplayed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('labels.create'));

        $response->assertOk();
    }

    /**
     * Тест: Аутентифицированный пользователь может создать новую метку
     * @return void
     */
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

    /**
     * Тест: Невозможно создать две метки с одинаковым именем
     * @return void
     */
    public function testUserCannotDuplicateLabel(): void
    {
        /** @var Label $firstLabel */
        $firstLabel = Label::factory()->create(['name' => 'Testing']);

        $response = $this
            ->actingAs($this->user)
            ->post(route('labels.store'), [
                'name' => 'Testing', // Дублирующееся имя
                'description' => 'Some description',
            ]);

        $response->assertInvalid(['name']);
        $this->assertDatabaseCount('labels', 1); // Убеждаемся, что создана только одна метка
    }

    /**
     * Тест: Форма редактирования метки отображается корректно
     * @return void
     */
    public function testLabelEditFormIsDisplayed(): void
    {
        /** @var Label $label */
        $label = Label::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('labels.edit', $label));

        $response->assertOk();
    }

    /**
     * Тест: Аутентифицированный пользователь может обновить метку
     * @return void
     */
    public function testUserCanUpdateLabel(): void
    {
        /** @var Label $label */
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

    /**
     * Тест: Аутентифицированный пользователь может удалить метку
     * @return void
     */
    public function testUserCanDeleteLabel(): void
    {
        /** @var Label $label */
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

    /**
     * Тест: Невозможно удалить метку, связанную с задачей
     * @return void
     */
    public function testUserCannotDeleteLabelIfItIsAssociatedWithTask(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        /** @var Label $label */
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

        $response->assertRedirect(); // или assertSessionHasErrors()

        $this->assertDatabaseHas('labels', [
            'id' => $label->id,
        ]);
    }
}
