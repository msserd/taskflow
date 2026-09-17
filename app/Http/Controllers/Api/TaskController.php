<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTaskRequest;
use App\Http\Requests\Api\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = Auth::user();
        $page = $request->get('page', 1);
        $cacheKey = 'tasks:api:' . $user->id . ':' . md5(http_build_query($request->query()));

        $tasks = Cache::remember($cacheKey, 600, function () use ($user, $request, $page) {
            return Task::with(['user', 'assignedUser'])
                ->forUser($user)
                ->filter($request->query())
                ->orderBy($request->get('sort', 'id'), $request->get('direction', 'desc'))
                ->paginate(15, ['*'], 'page', $page);
        });

        return TaskResource::collection($tasks);
    }

    /**
     * Статистика по задачам текущего пользователя.
     *
     * Возвращает количество задач по статусам. Для админа — по всем задачам,
     * для обычного пользователя — только по своим и назначенным ему.
     */
    public function stats(): JsonResponse
    {
        $user = Auth::user();
        $cacheKey = 'tasks:stats:' . $user->id;

        $stats = Cache::remember($cacheKey, 600, function () use ($user) {
            $query = Task::query();

            if ($user->role !== 'admin') {
                $query->where('user_id', $user->id)->orWhere('assigned_to', $user->id);
            }

            return [
                'total' => $query->count(),
                'pending' => (clone $query)->where('status', 'pending')->count(),
                'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
                'completed' => (clone $query)->where('status', 'completed')->count(),
            ];
        });

        return response()->json($stats);
    }

    public function store(StoreTaskRequest $request): TaskResource
    {
        $task = Task::create([
            ...$request->validated(),
            'user_id' => Auth::id(),
        ]);

        return new TaskResource($task->load(['user', 'assignedUser']));
    }

    public function show(Task $task): TaskResource
    {
        Gate::authorize('view', $task);

        return new TaskResource($task->load(['user', 'assignedUser', 'logs.user']));
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        Gate::authorize('update', $task);

        $task->update($request->validated());

        return new TaskResource($task->load(['user', 'assignedUser']));
    }

    public function destroy(Task $task): JsonResponse
    {

        Gate::authorize('delete', $task);

        $task->delete();

        return response()->json(null, 204);
    }
}
