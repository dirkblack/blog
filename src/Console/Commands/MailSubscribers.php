<?php

namespace DarkBlog\Console\Commands;

use DarkBlog\Mail\SubscriberEmail;
use DarkBlog\Models\Post;
use DarkBlog\Models\Subscriber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'darkblog:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send published posts to subscribed users';

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
     * @return int
     */
    public function handle()
    {
        // We need a post to send out
        // Get the most recently published
        $post = Post::nextPostForSubscribers();

        // to our list of subscribers
        $subscribers = Subscriber::all();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new SubscriberEmail($post));
        }

        $post->markAsPublished();
    }
}
