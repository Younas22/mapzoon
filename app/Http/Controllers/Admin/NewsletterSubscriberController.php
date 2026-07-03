<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', NewsletterSubscriber::class);

        $subscribers = NewsletterSubscriber::query()
            ->when($request->filled('q'), fn ($query) => $query->where('email', 'like', '%'.$request->query('q').'%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.newsletter-subscribers.index', [
            'subscribers' => $subscribers,
            'stats' => [
                'total' => NewsletterSubscriber::query()->count(),
                'this_month' => NewsletterSubscriber::query()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            ],
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('viewAny', NewsletterSubscriber::class);

        $subscribers = NewsletterSubscriber::query()
            ->when($request->filled('q'), fn ($query) => $query->where('email', 'like', '%'.$request->query('q').'%'))
            ->latest()
            ->get();

        $filename = 'newsletter-subscribers-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($subscribers) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Email', 'Source', 'Subscribed At']);

            foreach ($subscribers as $subscriber) {
                fputcsv($handle, [
                    $subscriber->email,
                    $subscriber->source,
                    $subscriber->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function destroy(NewsletterSubscriber $newsletterSubscriber): RedirectResponse
    {
        $this->authorize('delete', $newsletterSubscriber);

        $newsletterSubscriber->delete();

        return back()->with('success', 'Subscriber removed.');
    }
}
