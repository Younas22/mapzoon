<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\StoreSubtaskRequest;
use App\Http\Requests\Admin\Task\StoreTaskAttachmentRequest;
use App\Http\Requests\Admin\Task\StoreTaskCommentRequest;
use App\Http\Requests\Admin\Task\StoreTaskNoteRequest;
use App\Http\Requests\Admin\Task\StoreTaskRequest;
use App\Http\Requests\Admin\Task\UpdateTaskAssigneeRequest;
use App\Http\Requests\Admin\Task\UpdateTaskProgressRequest;
use App\Http\Requests\Admin\Task\UpdateTaskRequest;
use App\Http\Requests\Admin\Task\UpdateTaskStatusRequest;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Task::class);

        return view('admin.tasks.index', [
            'tasks' => $this->filteredTasks($request),
            'assignees' => User::query()->orderBy('name')->get(),
            'stats' => [
                'total' => Task::query()->count(),
                'in_progress' => Task::query()->where('status', 'in_progress')->count(),
                'completed' => Task::query()->where('status', 'completed')->count(),
                'overdue' => Task::query()->overdue()->count(),
            ],
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $tasks = $this->filteredTasks($request);

        return response()->json([
            'html' => view('admin.tasks.partials.table', compact('tasks'))->render(),
        ]);
    }

    public function myTasks(Request $request): View
    {
        $tasks = Task::query()
            ->with('creator')
            ->assignedTo(Auth::id())
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
            ->orderBy('due_date')
            ->paginate(10)
            ->withQueryString();

        return view('admin.tasks.my-tasks', compact('tasks'));
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::query()->create([
            ...$request->validated(),
            'created_by' => Auth::id(),
        ]);

        $task->logActivity('created', Auth::user()->name.' created the task.');

        if ($task->assigned_to) {
            $task->logActivity('assigned', 'Assigned to '.$task->assignedUser->name.'.');
        }

        return response()->json(['message' => 'Task created successfully.'], 201);
    }

    public function edit(Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        return response()->json([
            'task' => [
                ...$task->only(['id', 'title', 'description', 'priority', 'status', 'assigned_to']),
                'start_date' => $task->start_date?->format('Y-m-d'),
                'due_date' => $task->due_date?->format('Y-m-d'),
            ],
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $original = $task->only(['status', 'priority', 'assigned_to']);

        $task->fill($request->validated());

        if ($task->isDirty('status')) {
            $task->completed_at = $task->status === 'completed' ? now() : null;
        }

        $task->save();

        $this->logFieldChanges($task, $original);

        return response()->json(['message' => 'Task updated successfully.']);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        foreach ($task->attachments as $attachment) {
            Storage::disk('local')->delete($attachment->path);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully.']);
    }

    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        return view('admin.tasks.show', [
            'task' => $task->load(['assignedUser', 'creator', 'notes.user', 'comments.user', 'attachments.user', 'subtasks', 'activities.user']),
            'assignees' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): JsonResponse
    {
        $oldLabel = $task->statusLabel();
        $task->status = $request->validated('status');
        $task->completed_at = $task->status === 'completed' ? now() : null;
        $task->save();

        $task->logActivity('status_changed', Auth::user()->name." changed status from {$oldLabel} to {$task->statusLabel()}.");

        return response()->json(['message' => 'Task status updated.']);
    }

    public function updateProgress(UpdateTaskProgressRequest $request, Task $task): JsonResponse
    {
        $task->progress = $request->validated('progress');
        $task->save();

        $task->logActivity('progress_updated', Auth::user()->name." updated progress to {$task->progress}%.");

        return response()->json(['message' => 'Progress updated.']);
    }

    public function updateAssignee(UpdateTaskAssigneeRequest $request, Task $task): JsonResponse
    {
        $task->assigned_to = $request->validated('assigned_to');
        $task->save();

        $task->logActivity('assigned', Auth::user()->name.' '.($task->assigned_to ? 'assigned the task to '.$task->assignedUser->name : 'unassigned the task').'.');

        return response()->json(['message' => 'Assignee updated.']);
    }

    public function storeNote(StoreTaskNoteRequest $request, Task $task): JsonResponse
    {
        $note = $task->notes()->create([
            'user_id' => Auth::id(),
            'note' => $request->validated('note'),
        ]);
        $note->load('user');

        $task->logActivity('note_added', Auth::user()->name.' added a note.');

        return response()->json([
            'message' => 'Note added.',
            'html' => view('admin.tasks.partials.note', compact('note'))->render(),
        ], 201);
    }

    public function storeComment(StoreTaskCommentRequest $request, Task $task): JsonResponse
    {
        $comment = $task->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->validated('comment'),
        ]);
        $comment->load('user');

        $task->logActivity('comment_added', Auth::user()->name.' added a comment.');

        return response()->json([
            'message' => 'Comment added.',
            'html' => view('admin.tasks.partials.comment', compact('comment'))->render(),
        ], 201);
    }

    public function storeSubtask(StoreSubtaskRequest $request, Task $task): JsonResponse
    {
        $subtask = $task->subtasks()->create([
            'title' => $request->validated('title'),
            'sort_order' => $task->subtasks()->count(),
        ]);

        $task->logActivity('subtask_added', Auth::user()->name." added a subtask: \"{$subtask->title}\".");

        return response()->json([
            'message' => 'Subtask added.',
            'html' => view('admin.tasks.partials.subtask', compact('subtask'))->render(),
        ], 201);
    }

    public function toggleSubtask(Task $task, Subtask $subtask): JsonResponse
    {
        $this->authorize('view', $task);
        abort_unless($subtask->task_id === $task->id, 404);

        $subtask->is_completed = ! $subtask->is_completed;
        $subtask->save();

        $task->logActivity(
            $subtask->is_completed ? 'subtask_completed' : 'subtask_reopened',
            Auth::user()->name.' '.($subtask->is_completed ? 'completed' : 'reopened')." subtask: \"{$subtask->title}\"."
        );

        return response()->json(['message' => 'Subtask updated.', 'is_completed' => $subtask->is_completed]);
    }

    public function destroySubtask(Task $task, Subtask $subtask): JsonResponse
    {
        $this->authorize('view', $task);
        abort_unless($subtask->task_id === $task->id, 404);

        $title = $subtask->title;
        $subtask->delete();

        $task->logActivity('subtask_removed', Auth::user()->name." removed subtask: \"{$title}\".");

        return response()->json(['message' => 'Subtask removed.']);
    }

    public function storeAttachment(StoreTaskAttachmentRequest $request, Task $task): JsonResponse
    {
        $file = $request->file('file');
        $path = $file->store('task-attachments', 'local');

        $attachment = $task->attachments()->create([
            'user_id' => Auth::id(),
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ]);
        $attachment->load('user');

        $task->logActivity('attachment_added', Auth::user()->name." attached a file: \"{$attachment->original_name}\".");

        return response()->json([
            'message' => 'File attached.',
            'html' => view('admin.tasks.partials.attachment', compact('task', 'attachment'))->render(),
        ], 201);
    }

    public function downloadAttachment(Task $task, TaskAttachment $attachment): StreamedResponse
    {
        $this->authorize('view', $task);
        abort_unless($attachment->task_id === $task->id, 404);

        return Storage::disk('local')->download($attachment->path, $attachment->original_name);
    }

    public function destroyAttachment(Task $task, TaskAttachment $attachment): JsonResponse
    {
        $this->authorize('view', $task);
        abort_unless($attachment->task_id === $task->id, 404);

        Storage::disk('local')->delete($attachment->path);
        $name = $attachment->original_name;
        $attachment->delete();

        $task->logActivity('attachment_removed', Auth::user()->name." removed an attachment: \"{$name}\".");

        return response()->json(['message' => 'Attachment removed.']);
    }

    protected function logFieldChanges(Task $task, array $original): void
    {
        $labels = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'];

        if ($original['status'] !== $task->status) {
            $oldLabel = Task::STATUSES[$original['status']] ?? $original['status'];
            $task->logActivity('status_changed', Auth::user()->name." changed status from {$oldLabel} to {$task->statusLabel()}.");
        }

        if ($original['priority'] !== $task->priority) {
            $task->logActivity('priority_changed', Auth::user()->name.' changed priority from '.($labels[$original['priority']] ?? $original['priority']).' to '.$task->priorityLabel().'.');
        }

        if ($original['assigned_to'] !== $task->assigned_to) {
            $task->logActivity('assigned', Auth::user()->name.' '.($task->assigned_to ? 'assigned the task to '.$task->assignedUser->name : 'unassigned the task').'.');
        }

        $task->logActivity('updated', Auth::user()->name.' updated the task details.');
    }

    protected function filteredTasks(Request $request)
    {
        $sort = in_array($request->query('sort'), ['title', 'created_at', 'due_date', 'priority']) ? $request->query('sort') : 'created_at';
        $dir = $request->query('dir') === 'asc' ? 'asc' : 'desc';

        return Task::query()
            ->with(['assignedUser', 'creator'])
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->query('q').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->query('priority')))
            ->when($request->filled('assigned_to'), fn ($query) => $query->where('assigned_to', $request->query('assigned_to')))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();
    }
}
