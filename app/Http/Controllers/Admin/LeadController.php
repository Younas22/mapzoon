<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lead\StoreLeadNoteRequest;
use App\Http\Requests\Admin\Lead\StoreLeadRequest;
use App\Http\Requests\Admin\Lead\UpdateLeadAssigneeRequest;
use App\Http\Requests\Admin\Lead\UpdateLeadRequest;
use App\Http\Requests\Admin\Lead\UpdateLeadStatusRequest;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Lead::class);

        return view('admin.leads.index', [
            'leads' => $this->filteredLeads($request),
            'assignees' => User::query()->orderBy('name')->get(),
            'stats' => [
                'total' => Lead::query()->count(),
                'new' => Lead::query()->where('status', 'new')->count(),
                'won' => Lead::query()->where('status', 'won')->count(),
                'lost' => Lead::query()->where('status', 'lost')->count(),
            ],
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Lead::class);

        $leads = $this->filteredLeads($request);

        return response()->json([
            'html' => view('admin.leads.partials.table', compact('leads'))->render(),
        ]);
    }

    public function store(StoreLeadRequest $request): JsonResponse
    {
        Lead::query()->create($request->validated());

        return response()->json([
            'message' => 'Lead created successfully.',
        ], 201);
    }

    public function edit(Lead $lead): JsonResponse
    {
        $this->authorize('update', $lead);

        return response()->json([
            'lead' => [
                ...$lead->only([
                    'id', 'name', 'phone', 'email', 'business_name', 'service',
                    'message', 'status', 'source', 'assigned_to',
                ]),
                'follow_up_date' => $lead->follow_up_date?->format('Y-m-d'),
            ],
        ]);
    }

    public function update(UpdateLeadRequest $request, Lead $lead): JsonResponse
    {
        $lead->update($request->validated());

        return response()->json([
            'message' => 'Lead updated successfully.',
        ]);
    }

    public function updateStatus(UpdateLeadStatusRequest $request, Lead $lead): JsonResponse
    {
        $lead->update($request->validated());

        return response()->json([
            'message' => 'Lead status updated.',
        ]);
    }

    public function updateAssignee(UpdateLeadAssigneeRequest $request, Lead $lead): JsonResponse
    {
        $lead->update($request->validated());

        return response()->json([
            'message' => 'Lead reassigned.',
        ]);
    }

    public function destroy(Lead $lead): JsonResponse
    {
        $this->authorize('delete', $lead);

        $lead->delete();

        return response()->json([
            'message' => 'Lead deleted successfully.',
        ]);
    }

    public function show(Lead $lead): View
    {
        $this->authorize('view', $lead);

        return view('admin.leads.show', [
            'lead' => $lead->load(['assignedUser', 'notes.user']),
            'assignees' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function storeNote(StoreLeadNoteRequest $request, Lead $lead): JsonResponse
    {
        $note = $lead->notes()->create([
            'user_id' => Auth::id(),
            'note' => $request->validated('note'),
        ]);

        $note->load('user');

        return response()->json([
            'message' => 'Note added.',
            'html' => view('admin.leads.partials.note', compact('note'))->render(),
        ], 201);
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('viewAny', Lead::class);

        $leads = $this->filteredLeads($request, paginate: false);

        $filename = 'leads-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($leads) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Name', 'Phone', 'Email', 'Business Name', 'Service', 'Message',
                'Status', 'Source', 'Follow-up Date', 'Assigned To', 'Created At',
            ]);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->name,
                    $lead->phone,
                    $lead->email,
                    $lead->business_name,
                    $lead->service,
                    $lead->message,
                    $lead->statusLabel(),
                    $lead->sourceLabel(),
                    $lead->follow_up_date?->format('Y-m-d'),
                    $lead->assignedUser?->name,
                    $lead->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    protected function filteredLeads(Request $request, bool $paginate = true)
    {
        $sort = in_array($request->query('sort'), ['name', 'created_at', 'follow_up_date']) ? $request->query('sort') : 'created_at';
        $dir = $request->query('dir') === 'asc' ? 'asc' : 'desc';

        $query = Lead::query()
            ->with('assignedUser')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->query('q');
                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%")
                        ->orWhere('business_name', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
            ->when($request->filled('source'), fn ($query) => $query->where('source', $request->query('source')))
            ->when($request->filled('assigned_to'), fn ($query) => $query->where('assigned_to', $request->query('assigned_to')))
            ->orderBy($sort, $dir);

        return $paginate ? $query->paginate(10)->withQueryString() : $query->get();
    }
}
