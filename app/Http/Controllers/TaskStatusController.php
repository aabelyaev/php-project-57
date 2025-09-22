<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreTaskStatusRequest;

class TaskStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = TaskStatus::paginate();
        return view('statuses.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $taskStatus = new TaskStatus();
        Gate::authorize('create', $taskStatus);
        return view('statuses.create', compact('taskStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskStatusRequest $request)
    {
        $status = new TaskStatus();
        Gate::authorize('store', $status);
        $data = $request->validated();
        $status->fill($data);
        $status->save();
        flash('Статус успешно создан')->success();
        return redirect()->route('task_statuses.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskStatus $taskStatus)
    {
        Gate::authorize('edit', $taskStatus);
        return view('statuses.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskStatus $taskStatus)
    {
        Gate::authorize('update', $taskStatus);
        $newStatus = TaskStatus::findOrFail($taskStatus->id);
        $data = $request->validate([

            'name' => "required"
        ]);
        $newStatus->fill($data);
        $newStatus->save();
        flash('Статус успешно изменён')->success();
        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskStatus $taskStatus, Request $request)
    {
        Gate::authorize('delete', $taskStatus);
        if (($taskStatus->tasks->count()) > 0) {
            flash('Не удалось удалить статус')->error();
        } else {
            $taskStatus->delete();
            flash('Статус успешно удалён')->success();
        }
        return redirect()->route('task_statuses.index');
    }
}
