<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property User $createdBy
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $status_id
 * @property int $created_by_id
 * @property int|null $assigned_to_id
 *
 * @property-read TaskStatus $status
 * @property-read User $creator
 * @property-read User|null $assignee
 * @property-read \Illuminate\Database\Eloquent\Collection|Label[] $labels
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status_id', 'assigned_to_id'];

    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class)->withTimestamps();
    }
}
