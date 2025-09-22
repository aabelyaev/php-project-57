<?php

namespace App\Policies;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;

class TaskPolicy
{
    public function view(User $user): bool
    {
        return Auth::user() !== null;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return Auth::user() !== null;
    }

    public function store(User $user): bool
    {
        return Auth::user() !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function edit(User $user): bool
    {
        return Auth::user() !== null;
    }

    public function update(User $user): bool
    {
        return Auth::user() !== null;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return Auth::user() !== null && $user->id === $task->created_by_id;
    }
}
