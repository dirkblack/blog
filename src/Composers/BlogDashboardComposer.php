<?php

namespace App\Http\Composers;

use App\Models\Post;
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
        $published = Post::published()->count();
        $draft = Post::draft()->count();
        $view->with([
            'draft_count' => $draft,
            'published_count' => $published
        ]);

    }
}