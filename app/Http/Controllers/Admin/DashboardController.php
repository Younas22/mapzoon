<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Client;
use App\Models\ClientInvoice;
use App\Models\Lead;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        return view('admin.dashboard.index', [
            'cards' => $this->buildCards($user),
            'recentActivities' => $this->recentActivities($user),
            'upcomingDeadlines' => $this->upcomingDeadlines($user),
            'recentLeads' => $user->hasPermission('leads.view')
                ? Lead::query()->latest()->limit(5)->get()
                : collect(),
            'recentTasks' => $user->hasPermission('tasks.view')
                ? Task::query()->with('assignedUser')->latest()->limit(5)->get()
                : collect(),
        ]);
    }

    protected function buildCards(User $user): array
    {
        $cards = [];

        if ($user->hasPermission('clients.view')) {
            $cards[] = [
                'label' => 'Total Clients',
                'value' => Client::query()->count(),
                'url' => route('admin.clients.index'),
            ];
        }

        if ($user->hasPermission('projects.view')) {
            $cards[] = [
                'label' => 'Active Projects',
                'value' => Project::query()->whereNotIn('status', ['completed', 'cancelled'])->count(),
                'url' => route('admin.projects.index'),
            ];
        }

        if ($user->hasPermission('tasks.view')) {
            $cards[] = [
                'label' => 'Pending Tasks',
                'value' => Task::query()->where('status', 'pending')->count(),
                'url' => route('admin.tasks.index', ['status' => 'pending']),
            ];

            $cards[] = [
                'label' => 'Completed Tasks',
                'value' => Task::query()->where('status', 'completed')->count(),
                'url' => route('admin.tasks.index', ['status' => 'completed']),
            ];
        }

        if ($user->hasPermission('leads.view')) {
            $cards[] = [
                'label' => 'New Leads',
                'value' => Lead::query()->where('status', 'new')->count(),
                'url' => route('admin.leads.index', ['status' => 'new']),
            ];
        }

        if ($user->hasPermission('blogs.view')) {
            $cards[] = [
                'label' => 'Published Blogs',
                'value' => BlogPost::query()->published()->count(),
                'url' => route('admin.blog-posts.index', ['status' => 'published']),
            ];
        }

        if ($user->hasPermission('clients.view')) {
            $cards[] = [
                'label' => 'Revenue',
                'value' => '$'.number_format(ClientInvoice::query()->where('status', 'paid')->sum('amount'), 2),
                'url' => null,
            ];
        }

        return $cards;
    }

    /**
     * Merges task and project activity into one feed, each scoped to
     * whichever of those modules the viewer actually has access to —
     * credential access logs are deliberately never surfaced here.
     */
    protected function recentActivities(User $user): Collection
    {
        $activities = collect();

        if ($user->hasPermission('tasks.view')) {
            $activities = $activities->merge(
                TaskActivity::query()->with('task')->latest()->limit(10)->get()->map(fn (TaskActivity $activity) => [
                    'description' => $activity->description,
                    'created_at' => $activity->created_at,
                    'badge' => 'Task',
                    'url' => $activity->task ? route('admin.tasks.show', $activity->task) : null,
                ])
            );
        }

        if ($user->hasPermission('projects.view')) {
            $activities = $activities->merge(
                ProjectActivity::query()->with('project')->latest()->limit(10)->get()->map(fn (ProjectActivity $activity) => [
                    'description' => $activity->description,
                    'created_at' => $activity->created_at,
                    'badge' => 'Project',
                    'url' => $activity->project ? route('admin.projects.show', $activity->project) : null,
                ])
            );
        }

        return $activities->sortByDesc('created_at')->take(8)->values();
    }

    /**
     * Merges task due dates and project end dates into one sorted list.
     * Overdue items are included (sorted first) since they need attention
     * most — the view flags them visually rather than hiding them.
     */
    protected function upcomingDeadlines(User $user): Collection
    {
        $deadlines = collect();

        if ($user->hasPermission('tasks.view')) {
            $deadlines = $deadlines->merge(
                Task::query()
                    ->whereNotNull('due_date')
                    ->whereNotIn('status', ['completed', 'cancelled'])
                    ->orderBy('due_date')
                    ->limit(10)
                    ->get()
                    ->map(fn (Task $task) => [
                        'title' => $task->title,
                        'date' => $task->due_date,
                        'overdue' => $task->isOverdue(),
                        'badge' => 'Task',
                        'url' => route('admin.tasks.show', $task),
                    ])
            );
        }

        if ($user->hasPermission('projects.view')) {
            $deadlines = $deadlines->merge(
                Project::query()
                    ->whereNotNull('end_date')
                    ->whereNotIn('status', ['completed', 'cancelled'])
                    ->orderBy('end_date')
                    ->limit(10)
                    ->get()
                    ->map(fn (Project $project) => [
                        'title' => $project->name,
                        'date' => $project->end_date,
                        'overdue' => $project->isOverdue(),
                        'badge' => 'Project',
                        'url' => route('admin.projects.show', $project),
                    ])
            );
        }

        return $deadlines->sortBy('date')->take(6)->values();
    }
}
