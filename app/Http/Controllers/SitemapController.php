<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CaseStudy;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    protected const STATIC_PAGES = [
        ['route' => 'home',           'priority' => '1.0', 'changefreq' => 'daily'],
        ['route' => 'services',       'priority' => '0.9', 'changefreq' => 'weekly'],
        ['route' => 'pricing',        'priority' => '0.8', 'changefreq' => 'weekly'],
        ['route' => 'website',        'priority' => '0.7', 'changefreq' => 'monthly'],
        ['route' => 'pos-system',     'priority' => '0.7', 'changefreq' => 'monthly'],
        ['route' => 'process',        'priority' => '0.6', 'changefreq' => 'monthly'],
        ['route' => 'about',          'priority' => '0.7', 'changefreq' => 'monthly'],
        ['route' => 'why-choose-us',  'priority' => '0.6', 'changefreq' => 'monthly'],
        ['route' => 'team',           'priority' => '0.5', 'changefreq' => 'monthly'],
        ['route' => 'testimonials',   'priority' => '0.6', 'changefreq' => 'monthly'],
        ['route' => 'faq',            'priority' => '0.5', 'changefreq' => 'monthly'],
        ['route' => 'contact.page',   'priority' => '0.8', 'changefreq' => 'monthly'],
        ['route' => 'case-studies',   'priority' => '0.8', 'changefreq' => 'weekly'],
        ['route' => 'jobs',           'priority' => '0.6', 'changefreq' => 'weekly'],
        ['route' => 'blog.index',     'priority' => '0.8', 'changefreq' => 'daily'],
    ];

    public function index(): Response
    {
        $urls = collect(self::STATIC_PAGES)->map(fn ($page) => [
            'loc' => route($page['route']),
            'lastmod' => now(),
            'changefreq' => $page['changefreq'],
            'priority' => $page['priority'],
        ]);

        CaseStudy::query()->active()->get(['slug', 'updated_at'])->each(function ($caseStudy) use ($urls) {
            $urls->push([
                'loc' => route('case-studies.show', $caseStudy->slug),
                'lastmod' => $caseStudy->updated_at,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ]);
        });

        BlogPost::query()->published()->get(['slug', 'updated_at'])->each(function ($post) use ($urls) {
            $urls->push([
                'loc' => route('blog.show', $post->slug),
                'lastmod' => $post->updated_at,
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ]);
        });

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /login',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines)."\n", 200)
            ->header('Content-Type', 'text/plain');
    }
}
