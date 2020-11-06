<?php

namespace DarkBlog\Console\Commands;

use DarkBlog\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PublishPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'darkblack:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish and scheduled Blog Posts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Post::where('status', Post::STATUS_SCHEDULED)
            ->where('published', '<', Carbon::now()->toDateTimeString())
            ->update([
                'status' => Post::STATUS_PUBLISHED
            ]);
    }
}
