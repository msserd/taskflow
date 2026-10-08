<?php

namespace App\Services;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaskService
{
    public function getPaginated(User $user, Request $request): array
    {
        $cacheKey = 'tasks:list:' . $user->id . ':' . md5(http_build_query($request->query()));
        $tags = $this->getTags($user);

        $tasks = Cache::tags($tags)->remember($cacheKey, 600, function () use ($user, $request) {
            $paginator = Task::with(['user', 'assignedUser'])
                ->forUser($user)
                ->filter($request->only(['status', 'deadline_from', 'deadline_to']))
                ->orderBy($request->input('sort', 'id'), $request->input('direction', 'desc'))
                ->paginate(15, ['*'], 'page', $request->input('page', 1))
                ->withQueryString();

            return [
                'data' => TaskResource::collection($paginator->items())
                    ->response()
                    ->getData(true)['data'],
                'links' => $paginator->linkCollection()->toArray(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ];
        });

        return $tasks;
    }

    public function getStats(User $user): array
    {
        $cacheKey = 'tasks:stats:' . $user->id;
        $tags = $this->getTags($user);

        $stats = Cache::tags($tags)->remember($cacheKey, 600, function () use ($user) {
            $query = Task::query()->forUser($user);

            return [
                'total' => (clone $query)->count(),
                'pending' => (clone $query)->where('status', 'pending')->count(),
                'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
                'completed' => (clone $query)->where('status', 'completed')->count(),
            ];
        });

        return $stats;
    }

    protected function getTags(User $user): array
    {
        return $user->isAdmin()
            ? ['tasks', 'all_tasks']
            : ['tasks', 'user_' . $user->id];
    }
}
