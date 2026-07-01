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
            'title' => 'Case Studies — MAPZOON',
            'description' => 'See real results. Explore how MAPZOON helped local businesses rank higher on Google Maps and grow their customer base.',
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
