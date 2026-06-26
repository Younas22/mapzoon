<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VideoReview\StoreVideoReviewRequest;
use App\Http\Requests\Admin\VideoReview\UpdateVideoReviewRequest;
use App\Models\VideoReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VideoReviewController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', VideoReview::class);

        return view('admin.video-reviews.index', [
            'videoReviews' => $this->filteredVideoReviews($request),
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewAny', VideoReview::class);

        $videoReviews = $this->filteredVideoReviews($request);

        return response()->json([
            'html' => view('admin.video-reviews.partials.table', compact('videoReviews'))->render(),
        ]);
    }

    public function store(StoreVideoReviewRequest $request): JsonResponse
    {
        $videoReview = new VideoReview([
            ...$request->safe()->except(['thumbnail', 'is_visible_on_homepage']),
            'is_visible_on_homepage' => $request->boolean('is_visible_on_homepage'),
            'created_by' => Auth::id(),
        ]);

        if ($request->hasFile('thumbnail')) {
            $videoReview->thumbnail = $request->file('thumbnail')->store('reviews', 'public');
        }

        $videoReview->save();

        return response()->json(['message' => 'Video review added successfully.'], 201);
    }

    public function edit(VideoReview $video_review): JsonResponse
    {
        $this->authorize('update', $video_review);

        return response()->json([
            'videoReview' => [
                ...$video_review->only([
                    'id', 'client_name', 'tagline', 'company_name', 'review_text',
                    'youtube_url', 'display_order', 'status', 'is_visible_on_homepage',
                ]),
                'thumbnail_url' => $video_review->thumbnailUrl(),
            ],
        ]);
    }

    public function update(UpdateVideoReviewRequest $request, VideoReview $video_review): JsonResponse
    {
        $video_review->fill([
            ...$request->safe()->except(['thumbnail', 'is_visible_on_homepage']),
            'is_visible_on_homepage' => $request->boolean('is_visible_on_homepage'),
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($video_review->thumbnail) {
                Storage::disk('public')->delete($video_review->thumbnail);
            }

            $video_review->thumbnail = $request->file('thumbnail')->store('reviews', 'public');
        }

        $video_review->save();

        return response()->json(['message' => 'Video review updated successfully.']);
    }

    public function destroy(VideoReview $video_review): JsonResponse
    {
        $this->authorize('delete', $video_review);

        if ($video_review->thumbnail) {
            Storage::disk('public')->delete($video_review->thumbnail);
        }

        $video_review->delete();

        return response()->json(['message' => 'Video review deleted successfully.']);
    }

    protected function filteredVideoReviews(Request $request)
    {
        $sort = in_array($request->query('sort'), ['client_name', 'display_order', 'created_at']) ? $request->query('sort') : 'display_order';
        $dir = $request->query('dir') === 'desc' ? 'desc' : 'asc';

        return VideoReview::query()
            ->when($request->filled('q'), fn ($query) => $query->where('client_name', 'like', '%'.$request->query('q').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
            ->when($request->filled('visible'), fn ($query) => $query->where('is_visible_on_homepage', $request->query('visible') === '1'))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();
    }
}
