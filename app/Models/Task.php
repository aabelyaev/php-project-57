<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Label;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['status_id', 'name', 'description', 'created_by_id', 'assigned_to_id'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'created_by_id');
    }


    public function executor(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'assigned_to_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo('App\Models\TaskStatus');
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }

    public function getFormattedUpdateTime()
    {
        return $this->updated_at?->format('d.m.Y');
    }

    public function isLabelAttached(Label $label)
    {
        $attachedLabels = $this->labels;
        foreach ($attachedLabels as $attachedLabel) {
            if ($label->id === $attachedLabel->id) {
                return true;
            }
        }
        return false;
    }
}
