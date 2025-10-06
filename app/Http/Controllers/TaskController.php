<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                'name',
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id'),
            ])
            ->orderBy('id')
            ->paginate(15);

        $statuses = TaskStatus::all();
        $users = User::all();

        return view('task.index', compact('tasks', 'statuses', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $statuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all();

        return view('task.create', compact('statuses', 'users', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $task = new Task($request->validated());
        $task->createdBy()->associate(Auth::user());
        $task->save();

        $task->labels()->sync($request->get('labels'));

        flash(__('flashes.tasks.create.success'))->success();

        return redirect(route('tasks.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        $statuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all();

        return view('task.edit', compact('task', 'statuses', 'users', 'labels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());
        $task->labels()->sync($request->get('labels'));

        flash(__('flashes.tasks.update.success'))->success();

        return redirect(route('tasks.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->labels()->detach();
        $task->delete();
        flash(__('flashes.tasks.delete.success'))->success();

        return redirect(route('tasks.index'));
    }
}
