<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\StoresPublicImages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JobApplication\UpdateJobApplicationStatusRequest;
use App\Models\JobApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobApplicationController extends Controller
{
    use StoresPublicImages;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', JobApplication::class);

        return view('admin.job-applications.index', [
            'applications' => $this->filteredApplications($request),
            'positions' => JobApplication::query()->distinct()->orderBy('position')->pluck('position'),
            'stats' => [
                'total' => JobApplication::query()->count(),
                'new' => JobApplication::query()->where('status', 'new')->count(),
                'shortlisted' => JobApplication::query()->where('status', 'shortlisted')->count(),
                'rejected' => JobApplication::query()->where('status', 'rejected')->count(),
            ],
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewAny', JobApplication::class);

        $applications = $this->filteredApplications($request);

        return response()->json([
            'html' => view('admin.job-applications.partials.table', compact('applications'))->render(),
        ]);
    }

    public function show(JobApplication $jobApplication): View
    {
        $this->authorize('view', $jobApplication);

        return view('admin.job-applications.show', [
            'application' => $jobApplication,
        ]);
    }

    public function updateStatus(UpdateJobApplicationStatusRequest $request, JobApplication $jobApplication): JsonResponse
    {
        $jobApplication->update($request->validated());

        return response()->json([
            'message' => 'Application status updated.',
        ]);
    }

    public function destroy(JobApplication $jobApplication): JsonResponse
    {
        $this->authorize('delete', $jobApplication);

        $this->deletePublicImage($jobApplication->photo_path);
        $this->deletePublicImage($jobApplication->cv_path);

        $jobApplication->delete();

        return response()->json([
            'message' => 'Application deleted successfully.',
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('viewAny', JobApplication::class);

        $applications = $this->filteredApplications($request, paginate: false);

        $filename = 'job-applications-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($applications) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Name', 'Email', 'Phone', 'WhatsApp', 'City', 'Position',
                'Experience Level', 'Education Level', 'Availability', 'Salary Expectation',
                'LinkedIn', 'Portfolio', 'Status', 'Applied At',
            ]);

            foreach ($applications as $application) {
                fputcsv($handle, [
                    $application->fullName(),
                    $application->email,
                    $application->phone,
                    $application->whatsapp,
                    $application->city,
                    $application->position,
                    $application->experience_level,
                    $application->education_level,
                    $application->availability,
                    $application->salary_expectation,
                    $application->linkedin_url,
                    $application->portfolio_url,
                    $application->statusLabel(),
                    $application->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    protected function filteredApplications(Request $request, bool $paginate = true)
    {
        $sort = in_array($request->query('sort'), ['first_name', 'position', 'created_at']) ? $request->query('sort') : 'created_at';
        $dir = $request->query('dir') === 'asc' ? 'asc' : 'desc';

        $query = JobApplication::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->query('q');
                $query->where(function ($query) use ($term) {
                    $query->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%")
                        ->orWhere('position', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
            ->when($request->filled('position'), fn ($query) => $query->where('position', $request->query('position')))
            ->orderBy($sort, $dir);

        return $paginate ? $query->paginate(10)->withQueryString() : $query->get();
    }
}
