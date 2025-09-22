<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskStatusRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\TaskStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskStatusController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TaskStatus::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $taskStatuses = TaskStatus::paginate(3);

        return view('task_status.index', compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('task_status.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskStatusRequest $request): RedirectResponse
    {
        TaskStatus::create($request->all());
        flash('Статус успешно создан')->success();

        return redirect(route('task_statuses.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskStatus $taskStatus): View
    {
        return view('task_status.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskStatusRequest $request, TaskStatus $taskStatus): RedirectResponse
    {
        $taskStatus->update($request->all());
        flash('Статус успешно изменён')->success();

        return redirect(route('task_statuses.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskStatus $taskStatus): RedirectResponse
    {
        if ($taskStatus->tasks->isNotEmpty()) {
            flash('Не удалось удалить статус')->error();

            return redirect(route('task_statuses.index'));
        }

        $taskStatus->delete();
        flash('Статус успешно удалён')->success();

        return redirect(route('task_statuses.index'));
    }
}
