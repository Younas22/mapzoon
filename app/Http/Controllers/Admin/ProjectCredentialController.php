<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\StoreProjectCredentialRequest;
use App\Http\Requests\Admin\Project\UpdateProjectCredentialRequest;
use App\Models\Project;
use App\Models\ProjectCredential;
use App\Models\ProjectCredentialHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectCredentialController extends Controller
{
    public function store(StoreProjectCredentialRequest $request, Project $project): JsonResponse
    {
        $credential = $project->credentials()->create($request->validated());
        $credential->snapshotHistory('created');

        return response()->json([
            'message' => 'Credential added.',
            'html' => view('admin.projects.partials.credential', compact('project', 'credential'))->render(),
        ], 201);
    }

    public function update(UpdateProjectCredentialRequest $request, Project $project, ProjectCredential $credential): JsonResponse
    {
        abort_unless($credential->project_id === $project->id, 404);

        $data = $request->validated();
        $passwordChanged = filled($data['password'] ?? null);

        if (! $passwordChanged) {
            unset($data['password']);
        }

        $credential->update($data);
        $credential->snapshotHistory($passwordChanged ? 'password_changed' : 'updated');

        return response()->json([
            'message' => 'Credential updated.',
            'html' => view('admin.projects.partials.credential', compact('project', 'credential'))->render(),
        ]);
    }

    public function destroy(Project $project, ProjectCredential $credential): JsonResponse
    {
        $this->authorize('delete', $credential);
        abort_unless($credential->project_id === $project->id, 404);

        $credential->snapshotHistory('deleted');
        $credential->delete();

        return response()->json(['message' => 'Credential removed.']);
    }

    public function reveal(Request $request, Project $project, ProjectCredential $credential): JsonResponse
    {
        $this->authorize('reveal', $credential);
        abort_unless($credential->project_id === $project->id, 404);

        $credential->accessLogs()->create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'action' => 'revealed',
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['password' => $credential->password]);
    }

    public function history(Project $project, ProjectCredential $credential): JsonResponse
    {
        $this->authorize('view', $credential);
        abort_unless($credential->project_id === $project->id, 404);

        $entries = $credential->history()->with('changedBy')->get()->map(fn (ProjectCredentialHistory $entry) => [
            'id' => $entry->id,
            'action' => $entry->action,
            'platform_label' => $entry->platformLabel(),
            'label' => $entry->label,
            'username' => $entry->username,
            'has_password' => filled($entry->password),
            'changed_by' => $entry->changedBy?->name ?? 'Unknown',
            'created_at' => $entry->created_at->format('M d, Y \a\t g:i A'),
        ]);

        return response()->json(['history' => $entries]);
    }

    public function revealHistory(Request $request, Project $project, ProjectCredential $credential, ProjectCredentialHistory $history): JsonResponse
    {
        $this->authorize('reveal', $credential);
        abort_unless($history->credential_id === $credential->id && $credential->project_id === $project->id, 404);

        $credential->accessLogs()->create([
            'project_id' => $project->id,
            'history_id' => $history->id,
            'user_id' => Auth::id(),
            'action' => 'revealed_history',
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['password' => $history->password]);
    }
}
