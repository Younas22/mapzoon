<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\StoresPublicImages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CaseStudy\StoreCaseStudyRequest;
use App\Http\Requests\Admin\CaseStudy\UpdateCaseStudyRequest;
use App\Models\CaseStudy;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;

class CaseStudyController extends Controller
{
    use StoresPublicImages;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', CaseStudy::class);

        return view('admin.case-studies.index', [
            'caseStudies' => $this->filteredCaseStudies($request),
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewAny', CaseStudy::class);

        $caseStudies = $this->filteredCaseStudies($request);

        return response()->json([
            'html' => view('admin.case-studies.partials.table', compact('caseStudies'))->render(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', CaseStudy::class);

        return view('admin.case-studies.create', [
            'caseStudy' => new CaseStudy(['is_active' => true]),
            'clients' => $this->clientOptions(),
        ]);
    }

    public function store(StoreCaseStudyRequest $request): RedirectResponse
    {
        $caseStudy = new CaseStudy($this->caseStudyAttributes($request));

        if ($request->hasFile('image')) {
            $caseStudy->image = $this->storePublicImage($request->file('image'), 'case-studies');
        }

        if ($request->hasFile('owner_photo')) {
            $caseStudy->owner_photo = $this->storePublicImage($request->file('owner_photo'), 'case-studies');
        }

        $caseStudy->screenshots = $this->mergedScreenshots($request);
        $caseStudy->save();

        return redirect()->route('admin.case-studies.edit', $caseStudy)->with('success', 'Case study created successfully.');
    }

    public function edit(CaseStudy $case_study): View
    {
        $this->authorize('update', $case_study);

        return view('admin.case-studies.edit', [
            'caseStudy' => $case_study,
            'clients' => $this->clientOptions(),
        ]);
    }

    public function update(UpdateCaseStudyRequest $request, CaseStudy $case_study): RedirectResponse
    {
        $case_study->fill($this->caseStudyAttributes($request));

        if ($request->hasFile('image')) {
            $this->deletePublicImage($case_study->getOriginal('image'));

            $case_study->image = $this->storePublicImage($request->file('image'), 'case-studies');
        }

        if ($request->hasFile('owner_photo')) {
            $this->deletePublicImage($case_study->getOriginal('owner_photo'));

            $case_study->owner_photo = $this->storePublicImage($request->file('owner_photo'), 'case-studies');
        }

        $retained = $request->validated('existing_screenshots', []) ?? [];
        foreach ($case_study->screenshots ?? [] as $existing) {
            if (! in_array($existing, $retained, true)) {
                $this->deletePublicImage($existing);
            }
        }

        $case_study->screenshots = $this->mergedScreenshots($request);
        $case_study->save();

        return redirect()->route('admin.case-studies.edit', $case_study)->with('success', 'Case study updated successfully.');
    }

    public function destroy(CaseStudy $case_study): JsonResponse
    {
        $this->authorize('delete', $case_study);

        $this->deletePublicImage($case_study->image);
        $this->deletePublicImage($case_study->owner_photo);
        foreach ($case_study->screenshots ?? [] as $screenshot) {
            $this->deletePublicImage($screenshot);
        }

        $case_study->delete();

        return response()->json(['message' => 'Case study deleted successfully.']);
    }

    protected function clientOptions()
    {
        return Client::query()
            ->orderBy('owner_name')
            ->get(['id', 'owner_name', 'company_name', 'photo'])
            ->map(fn (Client $client) => [
                'id' => $client->id,
                'name' => $client->displayName(),
                'owner_name' => $client->owner_name,
                'photo_url' => $client->photoUrl(),
            ])
            ->values();
    }

    protected function caseStudyAttributes(StoreCaseStudyRequest|UpdateCaseStudyRequest $request): array
    {
        return [
            'title' => $request->validated('title'),
            'slug' => $request->validated('slug'),
            'description' => $request->validated('description'),
            'gmb_link' => $request->validated('gmb_link'),
            'website_link' => $request->validated('website_link'),
            'video_url' => $request->validated('video_url'),
            'client_id' => $request->validated('client_id') ?: null,
            'owner_name' => $request->validated('owner_name'),
            'owner_role' => $request->validated('owner_role'),
            'owner_review' => $request->validated('owner_review'),
            'display_order' => $request->validated('display_order') ?: 0,
            'is_active' => $request->boolean('is_active'),
        ];
    }

    protected function mergedScreenshots(StoreCaseStudyRequest|UpdateCaseStudyRequest $request): array
    {
        $retained = $request->validated('existing_screenshots', []) ?? [];

        $uploaded = collect($request->file('new_screenshots', []))
            ->filter()
            ->map(fn (UploadedFile $file) => $this->storePublicImage($file, 'case-studies'))
            ->all();

        return array_values(array_merge($retained, $uploaded));
    }

    protected function filteredCaseStudies(Request $request)
    {
        $sort = in_array($request->query('sort'), ['title', 'display_order', 'created_at']) ? $request->query('sort') : 'display_order';
        $dir = $request->query('dir') === 'desc' ? 'desc' : 'asc';

        return CaseStudy::query()
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->query('q').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->query('status') === 'active'))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();
    }
}
