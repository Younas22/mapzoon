<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Client\StoreClientContactRequest;
use App\Http\Requests\Admin\Client\StoreClientContractRequest;
use App\Http\Requests\Admin\Client\StoreClientFileRequest;
use App\Http\Requests\Admin\Client\StoreClientInvoiceRequest;
use App\Http\Requests\Admin\Client\StoreClientRequest;
use App\Http\Requests\Admin\Client\UpdateClientRequest;
use App\Http\Requests\Admin\Client\UpdateClientTeamRequest;
use App\Models\Client;
use App\Models\ClientContact;
use App\Models\ClientContract;
use App\Models\ClientFile;
use App\Models\ClientInvoice;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Client::class);

        return view('admin.clients.index', [
            'clients' => $this->filteredClients($request),
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Client::class);

        $clients = $this->filteredClients($request);

        return response()->json([
            'html' => view('admin.clients.partials.table', compact('clients'))->render(),
        ]);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        Client::query()->create([
            ...$request->validated(),
            'created_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Client created successfully.'], 201);
    }

    public function edit(Client $client): JsonResponse
    {
        $this->authorize('update', $client);

        return response()->json([
            'client' => $client->only([
                'id', 'company_name', 'owner_name', 'phone', 'email', 'website',
                'address', 'industry', 'notes', 'status', 'client_type',
            ]),
        ]);
    }

    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $client->update($request->validated());

        return response()->json(['message' => 'Client updated successfully.']);
    }

    public function destroy(Client $client): JsonResponse
    {
        $this->authorize('delete', $client);

        foreach ($client->files as $file) {
            Storage::disk('local')->delete($file->path);
        }

        foreach ($client->contracts as $contract) {
            if ($contract->file_path) {
                Storage::disk('local')->delete($contract->file_path);
            }
        }

        foreach ($client->invoices as $invoice) {
            if ($invoice->file_path) {
                Storage::disk('local')->delete($invoice->file_path);
            }
        }

        $client->delete();

        return response()->json(['message' => 'Client deleted successfully.']);
    }

    public function show(Client $client): View
    {
        $this->authorize('view', $client);

        return view('admin.clients.show', [
            'client' => $client->load(['contacts', 'files.user', 'contracts', 'invoices', 'teamMembers', 'projects']),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function storeContact(StoreClientContactRequest $request, Client $client): JsonResponse
    {
        $contact = $client->contacts()->create($request->validated());

        return response()->json([
            'message' => 'Contact added.',
            'html' => view('admin.clients.partials.contact', compact('client', 'contact'))->render(),
        ], 201);
    }

    public function updateContact(StoreClientContactRequest $request, Client $client, ClientContact $contact): JsonResponse
    {
        abort_unless($contact->client_id === $client->id, 404);

        $contact->update($request->validated());

        return response()->json([
            'message' => 'Contact updated.',
            'html' => view('admin.clients.partials.contact', compact('client', 'contact'))->render(),
        ]);
    }

    public function destroyContact(Client $client, ClientContact $contact): JsonResponse
    {
        $this->authorize('update', $client);
        abort_unless($contact->client_id === $client->id, 404);

        $contact->delete();

        return response()->json(['message' => 'Contact removed.']);
    }

    public function storeFile(StoreClientFileRequest $request, Client $client): JsonResponse
    {
        $file = $request->file('file');
        $path = $file->store('client-files', 'local');

        $clientFile = $client->files()->create([
            'user_id' => Auth::id(),
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ]);
        $clientFile->load('user');

        return response()->json([
            'message' => 'File uploaded.',
            'html' => view('admin.clients.partials.file', compact('client', 'clientFile'))->render(),
        ], 201);
    }

    public function downloadFile(Client $client, ClientFile $file): StreamedResponse
    {
        $this->authorize('view', $client);
        abort_unless($file->client_id === $client->id, 404);

        return Storage::disk('local')->download($file->path, $file->original_name);
    }

    public function destroyFile(Client $client, ClientFile $file): JsonResponse
    {
        $this->authorize('update', $client);
        abort_unless($file->client_id === $client->id, 404);

        Storage::disk('local')->delete($file->path);
        $file->delete();

        return response()->json(['message' => 'File removed.']);
    }

    public function storeContract(StoreClientContractRequest $request, Client $client): JsonResponse
    {
        $contract = $client->contracts()->create([
            ...$request->safe()->except('file'),
            ...$this->storedFileAttributes($request, 'client-contracts'),
        ]);

        return response()->json([
            'message' => 'Contract added.',
            'html' => view('admin.clients.partials.contract', compact('client', 'contract'))->render(),
        ], 201);
    }

    public function updateContract(StoreClientContractRequest $request, Client $client, ClientContract $contract): JsonResponse
    {
        abort_unless($contract->client_id === $client->id, 404);

        $contract->fill($request->safe()->except('file'));

        if ($request->hasFile('file')) {
            if ($contract->file_path) {
                Storage::disk('local')->delete($contract->file_path);
            }

            $contract->fill($this->storedFileAttributes($request, 'client-contracts'));
        }

        $contract->save();

        return response()->json([
            'message' => 'Contract updated.',
            'html' => view('admin.clients.partials.contract', compact('client', 'contract'))->render(),
        ]);
    }

    public function destroyContract(Client $client, ClientContract $contract): JsonResponse
    {
        $this->authorize('update', $client);
        abort_unless($contract->client_id === $client->id, 404);

        if ($contract->file_path) {
            Storage::disk('local')->delete($contract->file_path);
        }

        $contract->delete();

        return response()->json(['message' => 'Contract removed.']);
    }

    public function downloadContractFile(Client $client, ClientContract $contract): StreamedResponse
    {
        $this->authorize('view', $client);
        abort_unless($contract->client_id === $client->id && $contract->file_path, 404);

        return Storage::disk('local')->download($contract->file_path, $contract->file_original_name);
    }

    public function storeInvoice(StoreClientInvoiceRequest $request, Client $client): JsonResponse
    {
        $invoice = $client->invoices()->create([
            ...$request->safe()->except('file'),
            ...$this->storedFileAttributes($request, 'client-invoices'),
        ]);

        return response()->json([
            'message' => 'Invoice added.',
            'html' => view('admin.clients.partials.invoice', compact('client', 'invoice'))->render(),
        ], 201);
    }

    public function updateInvoice(StoreClientInvoiceRequest $request, Client $client, ClientInvoice $invoice): JsonResponse
    {
        abort_unless($invoice->client_id === $client->id, 404);

        $invoice->fill($request->safe()->except('file'));

        if ($request->hasFile('file')) {
            if ($invoice->file_path) {
                Storage::disk('local')->delete($invoice->file_path);
            }

            $invoice->fill($this->storedFileAttributes($request, 'client-invoices'));
        }

        $invoice->save();

        return response()->json([
            'message' => 'Invoice updated.',
            'html' => view('admin.clients.partials.invoice', compact('client', 'invoice'))->render(),
        ]);
    }

    public function destroyInvoice(Client $client, ClientInvoice $invoice): JsonResponse
    {
        $this->authorize('update', $client);
        abort_unless($invoice->client_id === $client->id, 404);

        if ($invoice->file_path) {
            Storage::disk('local')->delete($invoice->file_path);
        }

        $invoice->delete();

        return response()->json(['message' => 'Invoice removed.']);
    }

    public function downloadInvoiceFile(Client $client, ClientInvoice $invoice): StreamedResponse
    {
        $this->authorize('view', $client);
        abort_unless($invoice->client_id === $client->id && $invoice->file_path, 404);

        return Storage::disk('local')->download($invoice->file_path, $invoice->file_original_name);
    }

    public function updateTeam(UpdateClientTeamRequest $request, Client $client): JsonResponse
    {
        $client->teamMembers()->sync($request->validated('user_ids', []));

        return response()->json([
            'message' => 'Team updated.',
        ]);
    }

    protected function storedFileAttributes(Request $request, string $directory): array
    {
        if (! $request->hasFile('file')) {
            return [];
        }

        $file = $request->file('file');

        return [
            'file_path' => $file->store($directory, 'local'),
            'file_original_name' => $file->getClientOriginalName(),
        ];
    }

    protected function filteredClients(Request $request)
    {
        $sort = in_array($request->query('sort'), ['owner_name', 'company_name', 'created_at']) ? $request->query('sort') : 'created_at';
        $dir = $request->query('dir') === 'asc' ? 'asc' : 'desc';

        return Client::query()
            ->withCount('teamMembers')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->query('q');
                $query->where(function ($query) use ($term) {
                    $query->where('company_name', 'like', "%{$term}%")
                        ->orWhere('owner_name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
            ->when($request->filled('client_type'), fn ($query) => $query->where('client_type', $request->query('client_type')))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();
    }
}
