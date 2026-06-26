<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\StoreProjectDiscussionRequest;
use App\Http\Requests\Admin\Project\StoreProjectFileRequest;
use App\Http\Requests\Admin\Project\StoreProjectMilestoneRequest;
use App\Http\Requests\Admin\Project\StoreProjectNoteRequest;
use App\Http\Requests\Admin\Project\StoreProjectRequest;
use App\Http\Requests\Admin\Project\UpdateProjectProgressRequest;
use App\Http\Requests\Admin\Project\UpdateProjectRequest;
use App\Http\Requests\Admin\Project\UpdateProjectTeamRequest;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\ProjectMilestone;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Project::class);

        return view('admin.projects.index', [
            'projects' => $this->filteredProjects($request),
            'clients' => Client::query()->orderBy('owner_name')->get(),
            'stats' => [
                'total' => Project::query()->count(),
                'in_progress' => Project::query()->where('status', 'in_progress')->count(),
                'completed' => Project::query()->where('status', 'completed')->count(),
                'overdue' => Project::query()->whereNotIn('status', ['completed', 'cancelled'])->whereDate('end_date', '<', now())->count(),
            ],
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Project::class);

        $projects = $this->filteredProjects($request);

        return response()->json([
            'html' => view('admin.projects.partials.table', compact('projects'))->render(),
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = Project::query()->create([
            ...$request->validated(),
            'created_by' => Auth::id(),
        ]);

        $project->logActivity('created', Auth::user()->name.' created the project.');

        return response()->json(['message' => 'Project created successfully.'], 201);
    }

    public function edit(Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        return response()->json([
            'project' => [
                ...$project->only(['id', 'name', 'project_type', 'description', 'client_id', 'budget', 'status', 'priority', 'services_included']),
                'start_date' => $project->start_date?->format('Y-m-d'),
                'end_date' => $project->end_date?->format('Y-m-d'),
            ],
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $original = $project->only(['status', 'priority', 'budget']);

        $project->update($request->validated());

        $this->logFieldChanges($project, $original);

        return response()->json(['message' => 'Project updated successfully.']);
    }

    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        foreach ($project->files as $file) {
            Storage::disk('local')->delete($file->path);
        }

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully.']);
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        return view('admin.projects.show', [
            'project' => $project->load([
                'client', 'teamMembers', 'milestones', 'files.user',
                'credentials', 'notes.user', 'discussions.user', 'activities.user', 'tasks',
            ]),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function updateProgress(UpdateProjectProgressRequest $request, Project $project): JsonResponse
    {
        $project->progress = $request->validated('progress');
        $project->save();

        $project->logActivity('progress_updated', Auth::user()->name." updated progress to {$project->progress}%.");

        return response()->json(['message' => 'Progress updated.']);
    }

    public function updateTeam(UpdateProjectTeamRequest $request, Project $project): JsonResponse
    {
        $project->teamMembers()->sync($request->validated('user_ids', []));

        $project->logActivity('team_updated', Auth::user()->name.' updated the assigned team.');

        return response()->json(['message' => 'Team updated.']);
    }

    public function storeNote(StoreProjectNoteRequest $request, Project $project): JsonResponse
    {
        $note = $project->notes()->create([
            'user_id' => Auth::id(),
            'note' => $request->validated('note'),
        ]);
        $note->load('user');

        $project->logActivity('note_added', Auth::user()->name.' added a note.');

        return response()->json([
            'message' => 'Note added.',
            'html' => view('admin.projects.partials.note', compact('note'))->render(),
        ], 201);
    }

    public function storeDiscussion(StoreProjectDiscussionRequest $request, Project $project): JsonResponse
    {
        $discussion = $project->discussions()->create([
            'user_id' => Auth::id(),
            'message' => $request->validated('message'),
        ]);
        $discussion->load('user');

        $project->logActivity('discussion_added', Auth::user()->name.' posted a message.');

        return response()->json([
            'message' => 'Message posted.',
            'html' => view('admin.projects.partials.discussion', compact('discussion'))->render(),
        ], 201);
    }

    public function storeFile(StoreProjectFileRequest $request, Project $project): JsonResponse
    {
        $file = $request->file('file');
        $path = $file->store('project-files', 'local');

        $projectFile = $project->files()->create([
            'user_id' => Auth::id(),
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ]);
        $projectFile->load('user');

        $project->logActivity('file_added', Auth::user()->name." uploaded a file: \"{$projectFile->original_name}\".");

        return response()->json([
            'message' => 'File uploaded.',
            'html' => view('admin.projects.partials.file', compact('project', 'projectFile'))->render(),
        ], 201);
    }

    public function downloadFile(Project $project, ProjectFile $file): StreamedResponse
    {
        $this->authorize('view', $project);
        abort_unless($file->project_id === $project->id, 404);

        return Storage::disk('local')->download($file->path, $file->original_name);
    }

    public function destroyFile(Project $project, ProjectFile $file): JsonResponse
    {
        $this->authorize('update', $project);
        abort_unless($file->project_id === $project->id, 404);

        Storage::disk('local')->delete($file->path);
        $name = $file->original_name;
        $file->delete();

        $project->logActivity('file_removed', Auth::user()->name." removed a file: \"{$name}\".");

        return response()->json(['message' => 'File removed.']);
    }

    public function storeMilestone(StoreProjectMilestoneRequest $request, Project $project): JsonResponse
    {
        $milestone = $project->milestones()->create([
            ...$request->validated(),
            'sort_order' => $project->milestones()->count(),
        ]);

        $project->logActivity('milestone_added', Auth::user()->name." added a milestone: \"{$milestone->title}\".");

        return response()->json([
            'message' => 'Milestone added.',
            'html' => view('admin.projects.partials.milestone', compact('milestone'))->render(),
        ], 201);
    }

    public function toggleMilestone(Project $project, ProjectMilestone $milestone): JsonResponse
    {
        $this->authorize('update', $project);
        abort_unless($milestone->project_id === $project->id, 404);

        $milestone->is_completed = ! $milestone->is_completed;
        $milestone->save();

        $project->logActivity(
            $milestone->is_completed ? 'milestone_completed' : 'milestone_reopened',
            Auth::user()->name.' '.($milestone->is_completed ? 'completed' : 'reopened')." milestone: \"{$milestone->title}\"."
        );

        return response()->json(['message' => 'Milestone updated.', 'is_completed' => $milestone->is_completed]);
    }

    public function destroyMilestone(Project $project, ProjectMilestone $milestone): JsonResponse
    {
        $this->authorize('update', $project);
        abort_unless($milestone->project_id === $project->id, 404);

        $title = $milestone->title;
        $milestone->delete();

        $project->logActivity('milestone_removed', Auth::user()->name." removed milestone: \"{$title}\".");

        return response()->json(['message' => 'Milestone removed.']);
    }

    protected function logFieldChanges(Project $project, array $original): void
    {
        if ($original['status'] !== $project->status) {
            $oldLabel = Project::STATUSES[$original['status']] ?? $original['status'];
            $project->logActivity('status_changed', Auth::user()->name." changed status from {$oldLabel} to {$project->statusLabel()}.");
        }

        if ($original['priority'] !== $project->priority) {
            $oldLabel = Project::PRIORITIES[$original['priority']] ?? $original['priority'];
            $project->logActivity('priority_changed', Auth::user()->name." changed priority from {$oldLabel} to {$project->priorityLabel()}.");
        }

        if ((string) $original['budget'] !== (string) $project->budget) {
            $project->logActivity('budget_changed', Auth::user()->name.' updated the budget.');
        }

        $project->logActivity('updated', Auth::user()->name.' updated the project details.');
    }

    protected function filteredProjects(Request $request)
    {
        $sort = in_array($request->query('sort'), ['name', 'created_at', 'end_date', 'priority']) ? $request->query('sort') : 'created_at';
        $dir = $request->query('dir') === 'asc' ? 'asc' : 'desc';

        return Project::query()
            ->with(['client'])
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->query('q').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
            ->when($request->filled('client_id'), fn ($query) => $query->where('client_id', $request->query('client_id')))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();
    }
}
