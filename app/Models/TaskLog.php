<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['task_id', 'user_id', 'field', 'old_value', 'new_value'])]
class TaskLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'field',
        'old_value',
        'new_value',
    ];

    protected function casts()
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
