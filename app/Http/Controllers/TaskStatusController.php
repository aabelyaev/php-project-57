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
        $taskStatuses = TaskStatus::paginate(5);

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
        TaskStatus::create($request->validated());
        flash(__('flashes.statuses.create.success'))->success();

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
        $taskStatus->update($request->validated());
        flash(__('flashes.statuses.update.success'))->success();

        return redirect(route('task_statuses.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskStatus $taskStatus): RedirectResponse
    {
        if ($taskStatus->tasks()->exists()) {
            flash(__('flashes.statuses.delete.error'))->error();

            return back();
        }

        $taskStatus->delete();
        flash(__('flashes.statuses.delete.success'))->success();

        return redirect(route('task_statuses.index'));
    }
}
