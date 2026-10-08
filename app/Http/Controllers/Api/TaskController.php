<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTaskRequest;
use App\Http\Requests\Api\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $tasks = $this->taskService->getPaginated(Auth::user(), $request);

        return response()->json($tasks);
    }

    /**
     * Статистика по задачам текущего пользователя.
     *
     * Возвращает количество задач по статусам. Для админа — по всем задачам,
     * для обычного пользователя — только по своим и назначенным ему.
     */
    public function stats(): JsonResponse
    {
        $stats = $this->taskService->getStats(Auth::user());

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
