<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPostSeo extends Model
{
    protected $table = 'blog_post_seo';

    protected $fillable = [
        'blog_post_id',
        'meta_title',
        'meta_description',
        'focus_keyword',
        'canonical_url',
        'og_title',
        'og_description',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'custom_schema',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }
}
