<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
    ) {}

    public function index(Request $request): Response
    {
        $tasks = $this->taskService->getPaginated(Auth::user(), $request);

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'statuses' => Task::STATUSES,
            'filters' => $request->only(['status', 'deadline_from', 'deadline_to']),
            'sort' => $request->input('sort', 'id'),
            'direction' => $request->input('direction', 'desc'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Tasks/Create', [
            'statuses' => Task::STATUSES,
            'users' => User::all(['id', 'name']),
        ]);
    }

    public function store(TaskRequest $request): RedirectResponse
    {

        $task = Task::create([
            ...$request->validated(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('tasks.show', $task->id)->with('success', 'Задача создана');
    }

    public function show(Task $task): Response
    {
        Gate::authorize('view', $task);

        return Inertia::render('Tasks/Show', [
            'task' => $task->load(['user', 'assignedUser']),
        ]);
    }

    public function edit(Task $task): Response
    {
        Gate::authorize('update', $task);

        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'users' => User::all(['id', 'name']),
            'statuses' => Task::STATUSES,
        ]);
    }

    public function update(TaskRequest $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        $task->update($request->validated());

        return redirect()->route('tasks.show', $task->id)->with('success', 'Задача обновлена');
    }

    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Задача удалена');
    }
}
