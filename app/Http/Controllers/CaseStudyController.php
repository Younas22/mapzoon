<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use Illuminate\View\View;

class CaseStudyController extends Controller
{
    public function index(): View
    {
        $caseStudies = CaseStudy::query()
            ->active()
            ->orderBy('display_order')
            ->orderByDesc('created_at')
            ->get();

        return view('pages.case-studies.index', [
            'caseStudies' => $caseStudies,
            'title' => 'Local SEO Case Studies | MapZoon',
            'description' => 'Explore Local SEO case studies from MapZoon and discover how we help businesses improve Google Maps rankings, increase online visibility, and attract more local customers across the USA.',
            'canonical' => url('/case-studies'),
            'ogDescription' => 'See how MapZoon helps local businesses grow with Local SEO, Google Business Profile optimization, and website development.',
            'twitterDescription' => 'Discover real Local SEO strategies and business growth stories from MapZoon.',
        ]);
    }

    public function show(string $slug): View
    {
        $caseStudy = CaseStudy::query()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $others = CaseStudy::query()
            ->active()
            ->where('id', '!=', $caseStudy->id)
            ->orderBy('display_order')
            ->limit(3)
            ->get();

        return view('pages.case-studies.show', [
            'caseStudy' => $caseStudy,
            'others' => $others,
            'title' => $caseStudy->title.' — Case Study | MAPZOON',
            'description' => \Str::limit($caseStudy->description, 160),
        ]);
    }
}
