<?php

namespace DarkBlog\Composers;

use DarkBlog\Models\Post;
use DarkBlog\Models\Subscriber;
use Illuminate\View\View;

class BlogDashboardComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            'draft_count'      => Post::draft()->count(),
            'published_count'  => Post::published()->count(),
            'scheduled_count'  => Post::scheduled()->count(),
            'subscriber_count' => Subscriber::count()
        ]);

    }
}
