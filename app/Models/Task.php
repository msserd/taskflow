<?php

namespace App\Models;

use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Observers\TaskObserver;
use App\Policies\TaskPolicy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

#[ObservedBy([TaskObserver::class])]
#[UsePolicy(TaskPolicy::class)]

#[Fillable(['title', 'description', 'status', 'deadline', 'user_id', 'assigned_to'])]

class Task extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = [
        'pending' => 'В ожидании',
        'in_progress' => 'В работе',
        'completed' => 'Завершена',
    ];

    protected $dispatchesEvents = [
        'created' => TaskCreated::class,
        'updated' => TaskUpdated::class,
    ];

    protected $appends = ['status_label'];

    protected function casts()
    {
        return [
            'status' => 'string',
            'deadline' => 'datetime:Y-m-d H:i:s',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TaskLog::class);
    }

    public function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => self::STATUSES[$this->status] ?? $this->status,
        );
    }

    public function isOverdue(): bool
    {
        return $this->deadline
            && $this->deadline < now()
            && $this->status !== 'completed';
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->when(!$user->isAdmin(),
            fn (Builder $query) => $query->where(function (Builder $query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('assigned_to', $user->id);
            })
        );
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(!empty($filters['status']),
                fn (Builder $query) => $query->where('status', $filters['status']))
            ->when(!empty($filters['deadline_from']),
                fn (Builder $query) => $query->whereDate('deadline', '>=', $filters['deadline_from']))
            ->when(!empty($filters['deadline_to']),
                fn (Builder $query) => $query->whereDate('deadline', '<=', $filters['deadline_to']));
    }

    public static function clearCache(array $userIds): void
    {
        $prefix = config('database.redis.options.prefix') . (config('cache.prefix') ?? '');
        $redis = Redis::connection('cache');

        foreach ($userIds as $userId) {
            $patterns = [
                '*tasks:web:' . $userId . ':*',
                '*tasks:api:' . $userId . ':*',
                '*tasks:stats:' . $userId,
            ];

            foreach ($patterns as $pattern) {
                $keys = $redis->keys($pattern);
                foreach ($keys as $key) {
                    Cache::forget(str_replace($prefix, '', $key));
                }
            }
        }
    }
}
