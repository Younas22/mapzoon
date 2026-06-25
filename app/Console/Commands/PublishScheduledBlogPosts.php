<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;

class PublishScheduledBlogPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-scheduled-blog-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flip scheduled blog posts to published once their publish date has passed';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $count = BlogPost::query()
            ->scheduled()
            ->where('published_at', '<=', now())
            ->update(['status' => 'published']);

        $this->info("Published {$count} scheduled blog post(s).");
    }
}
