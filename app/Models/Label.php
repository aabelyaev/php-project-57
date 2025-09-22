<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Task;

class Label extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];


    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }

    public function getFormattedUpdateTime()
    {
        return $this->updated_at?->format('d.m.Y');
    }
}
