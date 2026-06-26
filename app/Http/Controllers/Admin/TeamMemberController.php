<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamMember\StoreTeamMemberRequest;
use App\Http\Requests\Admin\TeamMember\UpdateTeamMemberRequest;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', TeamMember::class);

        return view('admin.team-members.index', [
            'teamMembers' => $this->filteredTeamMembers($request),
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewAny', TeamMember::class);

        $teamMembers = $this->filteredTeamMembers($request);

        return response()->json([
            'html' => view('admin.team-members.partials.table', compact('teamMembers'))->render(),
        ]);
    }

    public function store(StoreTeamMemberRequest $request): JsonResponse
    {
        $teamMember = new TeamMember([
            ...$request->safe()->except(['photo', 'is_visible_on_homepage']),
            'is_visible_on_homepage' => $request->boolean('is_visible_on_homepage'),
            'created_by' => Auth::id(),
        ]);

        if ($request->hasFile('photo')) {
            $teamMember->photo = $request->file('photo')->store('team', 'public');
        }

        $teamMember->save();

        return response()->json(['message' => 'Team member added successfully.'], 201);
    }

    public function edit(TeamMember $team_member): JsonResponse
    {
        $this->authorize('update', $team_member);

        return response()->json([
            'teamMember' => [
                ...$team_member->only([
                    'id', 'name', 'designation', 'bio', 'email', 'linkedin_url',
                    'display_order', 'status', 'is_visible_on_homepage',
                ]),
                'photo_url' => $team_member->photoUrl(),
            ],
        ]);
    }

    public function update(UpdateTeamMemberRequest $request, TeamMember $team_member): JsonResponse
    {
        $team_member->fill([
            ...$request->safe()->except(['photo', 'is_visible_on_homepage']),
            'is_visible_on_homepage' => $request->boolean('is_visible_on_homepage'),
        ]);

        if ($request->hasFile('photo')) {
            if ($team_member->photo) {
                Storage::disk('public')->delete($team_member->photo);
            }

            $team_member->photo = $request->file('photo')->store('team', 'public');
        }

        $team_member->save();

        return response()->json(['message' => 'Team member updated successfully.']);
    }

    public function destroy(TeamMember $team_member): JsonResponse
    {
        $this->authorize('delete', $team_member);

        if ($team_member->photo) {
            Storage::disk('public')->delete($team_member->photo);
        }

        $team_member->delete();

        return response()->json(['message' => 'Team member deleted successfully.']);
    }

    protected function filteredTeamMembers(Request $request)
    {
        $sort = in_array($request->query('sort'), ['name', 'display_order', 'created_at']) ? $request->query('sort') : 'display_order';
        $dir = $request->query('dir') === 'desc' ? 'desc' : 'asc';

        return TeamMember::query()
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->query('q').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
            ->when($request->filled('visible'), fn ($query) => $query->where('is_visible_on_homepage', $request->query('visible') === '1'))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();
    }
}
