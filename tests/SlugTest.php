<?php

namespace Tests;

use Carbon\Carbon;
use DarkBlog\Models\Post;
use DarkBlog\Models\Slug;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlugTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function generates_unique_slug()
    {
        /*
         * Each Post must have a unique slug
         */

        $test_title = 'this is a test title';
        $slug_no_1 = Slug::generate($test_title);

        factory(Post::class)->create([
            'title' => $test_title
        ]);

        // verify there is a record with the slug
        $this->assertDatabaseHas('posts', [
            'title'     => $test_title,
            'slug'      => $slug_no_1
        ]);

        // That slug is taken,
        // so if we use the title to generate a new one,
        // we should get different results

        $this->assertNotEquals($slug_no_1, Slug::generate($test_title));
    }

}
