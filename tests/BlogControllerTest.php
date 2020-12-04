<?php

namespace Tests;

use DarkBlog\Console\Commands\MailSubscribers;
use DarkBlog\Console\Commands\PublishPosts;
use DarkBlog\Mail\SubscriberEmail;
use DarkBlog\Models\Post;
use DarkBlog\Models\Slug;
use DarkBlog\Models\Subscriber;
use DarkBlog\Models\Tag;
use App\Models\Role;
use App\Models\Site;
use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    public $response;
    public $user;

    /** @test */
    public function guest_can_see_index()
    {
        $published_posts = Post::factory()->count(5)->create([
            'published' => Carbon::now()->subDay()->toDateTimeString()
        ]);

        // Should see latest published posts
        $this->get('/Blog')
            ->assertSee($published_posts[0]->title);
    }

    /** @test */
    public function create_post()
    {
        /*
         * Create a new Post
         * Should default to Draft
         * Slug should be created
         */

        $this->createAndLoginUser();

        $test_title = 'This is a Test Title';
        $test_body = 'This is the test body';

        $this->get(route('blog.admin'))
            ->assertOk()
            ->assertSee('New Post');

        $this->get('/Blog/create')
            ->assertOk()
            ->assertSee('Create');

        $this->post('/Blog', [
            'title' => $test_title,
            'body'  => $test_body
        ])->assertRedirect('/Blog/drafts');

        $this->get('/Blog/drafts')
            ->assertSee($test_title);

        $this->assertDatabaseHas('posts', [
            'title'     => $test_title,
            'body'      => $test_body,
            'published' => null,
            'user_id'   => $this->user->id,
            'slug'      => Slug::slugify($test_title)
        ]);
    }

    /** @test */
    public function show_post()
    {
        $post = Post::factory()->create([
            'published' => Carbon::now()->subDay()->toDateTimeString()
        ]);

        $this->get('/Blog/' . $post->id)
            ->assertOk()
            ->assertSee($post->title)
            ->assertSee($post->body);
    }

    /** @test */
    public function must_have_title()
    {
        $this->createAndLoginUser();

        $this->withExceptionHandling();

        $this->post('/Blog', [
            'title' => '',
            'body'  => 'Test Body'
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    public function must_have_body()
    {
        $this->createAndLoginUser();

        $this->withExceptionHandling();

        $this->post('/Blog', [
            'title' => 'Test Title',
            'body'  => ''
        ])->assertSessionHasErrors('body');
    }

    /** @test */
    public function update_post()
    {
        $this->createAndLoginUser();

        $post = Post::create([
            'user_id' => $this->user->id,
            'title'   => 'Test Post',
            'body'    => 'Test Body',
        ]);

        $this->get('/Blog/' . $post->id . '/edit')
            ->assertSee($post->title);

        $this->post('/Blog/' . $post->id, [
            'title' => 'Updated Title',
            'body'  => 'Updated Body',
        ])->assertRedirect('/Blog/' . $post->id);

        $this->assertDatabaseHas('posts', [
            'id'      => $post->id,
            'user_id' => $this->user->id,
            'title'   => 'Updated Title',
            'body'    => 'Updated Body',
        ]);
    }

    /** @test */
    public function see_published_posts()
    {
        $published_posts = Post::factory()->count(5)->create([
            'published' => Carbon::now()->subDay()->toDateTimeString()
        ]);

        $draft_post = Post::factory()->create();

        $response = $this->get('/Blog');

        // See published posts
        foreach ($published_posts as $post) {
            $response->assertSee($post->title)
                ->assertSee($post->body);
        }

        // Don't see draft posts
        $response->assertDontSee($draft_post->title);
    }

    /** @test */
    public function dont_see_draft_posts()
    {
        $draft_post = Post::factory()->create();

        $this->get('/Blog')
            ->assertDontSee($draft_post->title);
    }

    /** @test */
    public function process_markdown()
    {
        $this->createAndLoginUser();

        // The body of the post should be processed as Markdown
        $post = Post::create([
            'user_id' => $this->user->id,
            'title'   => 'Test Post',
            'body'    => '# Test Body' // This should resolve to a header
        ]);

        $this->assertEquals('<h1>Test Body</h1>', $post->bodyHtml());
    }

    /** @test */
    public function tag_post()
    {
        $this->createAndLoginUser();

        $test_tag = 'newtag';

        $post = Post::create([
            'user_id' => $this->user->id,
            'title'   => 'Test Post',
            'body'    => 'Test Body'
        ]);

        $this->post('/Blog/' . $post->id . '/tag/' . $test_tag);

        // The tag should automatically be created
        $this->assertDatabaseHas('tags', [
            'name' => $test_tag
        ]);

        $tag = Tag::where('name', $test_tag)->first();

        // database link between tag and post
        $this->assertDatabaseHas('tagged', [
            'tag_id'      => $tag->id,
            'tagged_id'   => $post->id,
            'tagged_type' => Post::class
        ]);

        $this->assertEquals(1, count($post->tags));

        $this->assertEquals($test_tag, $post->tags[0]->name);
    }

    /** @test */
    public function view_posts_by_tag()
    {
        $published_posts = Post::factory()->count(3)->create([
            'published' => Carbon::now()->subDay()->toDateTimeString()
        ]);

        $tags = Tag::factory()->count(3)->create();

        // Apply each tag once to a different posts
        foreach ($published_posts as $index => $post) {
            $post->tags()->save($tags[$index]);
        }

        foreach ($tags as $index => $tag) {
            $tagged_title = $published_posts[$index]->title;
            $un_tagged_titles = [];
            foreach ($published_posts as $post) {
                if ($post->title <> $tagged_title) {
                    $un_tagged_titles[] = $post->title;
                }
            }

            $response = $this->get('/Blog/tag/' . $tag->name)
                ->assertSee($published_posts[$index]->title);

            foreach ($un_tagged_titles as $title) {
                $response->assertDontSee($title);
            }
        }
    }

    /** @test */
    public function author_can_view_draft_post()
    {
        $this->createAndLoginUser();

        $draft_post = Post::factory()->create();

        $this->get('/Blog/' . $draft_post->id)
            ->assertSee($draft_post->title);
    }

    /** @test */
    public function see_tags_on_main_page()
    {
        $tags = Tag::factory()->count(3)->create();

        $this->get('/')
            ->assertOk()
            ->assertSee($tags[0]->tag);
    }

    /** @test */
    public function publish_post()
    {
        $this->createAndLoginUser();

        // begin with a draft post
        $post = Post::factory()
            ->create([
            ]);

        // We should not see it yet
        $this->get('/Blog')
            ->assertDontSee($post->title);

        // Publish the post
        $this->post(route('blog.publish', ['post' => $post->id]));

        $this->assertDatabaseHas('posts', [
            'id'        => $post->id,
            'published' => Carbon::now()->toDateTimeString()
        ]);

        // the post should be visible on the index page
        $this->get('/Blog')
            ->assertSee($post->title);
    }

    /** @test */
    public function schedule_post()
    {
        $this->createAndLoginUser();

        // Begin with a draft post
        $post = Post::factory()
            ->create([
            ]);

        // Schedule the post
        $this->post(route('blog.schedule', [
            'post'     => $post->id,
            'schedule' => Carbon::now()->addMinutes(10)->toDateTimeString()
        ]));

        $this->assertDatabaseHas('posts', [
            'id'        => $post->id,
            'published' => Carbon::now()->addMinutes(10)->toDateTimeString()
        ]);

        // We should not see it on the index page
        $this->get('/Blog')
            ->assertDontSee($post->title);
    }

    /** @test */
    public function master_can_subscribe_user()
    {
        // A master can sign up a subscriber without requiring that email be verified

        $this->createAndLoginUser();

        $this->get(route('blog.admin'))
            ->assertSee('Subscribers');

        $this->get(route('blog.subscribers'))
            ->assertSee('Add Subscriber');

        // The user needs to have permission, we don't yet
        $this->get(route('blog.subscribe.force'))
            ->assertForbidden();

        $this->post(route('blog.subscribe.force.post'), [
            'email' => 'testuser@example.com',
            'name'  => 'Test User'
        ])->assertForbidden();

        // Give user permission
        $this->user->is_super_admin = true;
        $this->user->save();

        $this->get(route('blog.subscribe.force'))
            ->assertOk();

        // Post to controller
        $this->post(route('blog.subscribe.force.post'), [
            'email' => 'testuser@example.com',
            'name'  => 'Test User'
        ])
            ->assertRedirect(route('blog.subscribers'));

        // Should see in database
        $this->assertDatabaseHas('subscribers', [
            'email'    => 'testuser@example.com',
            'name'     => 'Test User',
            'verified' => true
        ]);
    }

    /** @test */
    public function show_subscribers()
    {
        $this->createAndLoginUser();

        $users = Subscriber::factory()->count(3)->create();

        $response = $this->get('/Blog/subscribers');

        $response->assertOk();

        foreach ($users as $user) {
            $response->assertSee($user->first_name);
        }
    }

    /** @test */
    public function delete_post()
    {
        $this->createAndLoginUser();

        $draft_post = Post::factory()->create();

        $this->delete(route('blog.delete', ['post' => $draft_post->id]));

        $this->assertDatabaseMissing('posts', [
            'id' => $draft_post->id
        ]);
    }

    /** @test */
    public function admin()
    {
        $this->createAndLoginUser();

        $drafts = Post::factory()->count(3)->create();
        $published = Post::factory()->count(11)->create([
            'published' => Carbon::now()->subSecond()->toDateTimeString()
        ]);

        $this->get(route('blog.admin'))
            ->assertOk()
            ->assertSee('New Post')
            ->assertSee('Drafts')
            ->assertSee(count($drafts)) // Draft Count
            ->assertSee('Published')
            ->assertSee(count($published)); // Published Count
    }

    /** @test */
    public function guest_cannot_subscribe()
    {
        // When a guests subscribes we use the term "register" to differentiate from an admin subscribing someone
        $this->get('/Blog')
            ->assertDontSee('Subscribe');
//            ->assertSee('Subscribe');

//        $this->get('/Blog/subscribe')
//            ->assertOk();
//
//        // Post to controller
//        $this->post('/Blog/subscribe', [
//            'email' => 'testuser@example.com',
//            'name'  => 'Test User'
//        ])->assertRedirect('/Blog');
//
//        // Should see in database
//        $this->assertDatabaseHas('subscribers', [
//            'email' => 'testuser@example.com',
//            'name'  => 'Test User'
//        ]);

        // TODO: Guest should receive an email verifying subscription which contains unsubscribe link
        //  Should be required to verify subscription by clicking in email
    }

    /** @test */
    public function show_drafts()
    {
        $this->createAndLoginUser();

        $drafts = Post::factory()->count(3)->create();
        $published = Post::factory()->create([
            'published' => Carbon::now()->subSecond()->toDateTimeString()
        ]);

        $this->get(route('blog.drafts'))
            ->assertOk()
            ->assertSee($drafts[0]->title)
            ->assertSee($drafts[1]->title)
            ->assertSee($drafts[2]->title)
            ->assertDontSee($published->title)
            ->assertSee('Drafts');
    }

    /** @test */
    public function show_published()
    {
        $this->createAndLoginUser();

        $draft = Post::factory()->create();
        $published = Post::factory()->count(3)->create([
            'published' => Carbon::now()->subSecond()->toDateTimeString()
        ]);

        $this->get(route('blog.published'))
            ->assertOk()
            ->assertSee($published[0]->title)
            ->assertSee($published[1]->title)
            ->assertSee($published[2]->title)
            ->assertDontSee($draft->title)
            ->assertSee('Drafts');
    }

    /** @test */
    public function show_scheduled()
    {
        $this->createAndLoginUser();

        $draft = Post::factory()->create();
        $scheduled = Post::factory()->count(3)->create([
            'published' => Carbon::now()->addSeconds(5)->toDateTimeString()
        ]);

        $this->get(route('blog.scheduled'))
            ->assertOk()
            ->assertSee($scheduled[0]->title)
            ->assertSee($scheduled[1]->title)
            ->assertSee($scheduled[2]->title)
            ->assertDontSee($draft->title)
            ->assertSee('Drafts');
    }

    /** @test */
    public function mail_list_command()
    {
        Mail::fake();

        $post = Post::factory()->create([
            'published' => Carbon::now()->subSecond()->toDateTimeString()
        ]);

        $subscribers = Subscriber::factory()->count(2)->create();

        Artisan::call(MailSubscribers::class);

        Mail::assertSent(SubscriberEmail::class, 2);

        foreach ($subscribers as $subscriber) {
            Mail::assertSent(SubscriberEmail::class, function ($mail) use ($subscriber, $post) {
                return $mail->hasTo($subscriber->email) &&
                       $mail->post->title == $post->title;
            });
        }

        $post->refresh();

        $this->assertEquals(Post::STATUS_PUBLISHED, $post->status);
    }

    /** @test */
    public function email_contents()
    {
        $prologue = 'This email should contain a prologue';
        $epilogue = 'This email has an epilogue';

        $post = Post::factory()->create([
            'published' => Carbon::now()->subSecond()->toDateTimeString(),
            'prologue'  => $prologue,
            'epilogue'  => $epilogue
        ]);

        $email = (new SubscriberEmail($post))->render();

        $this->assertStringContainsString($post->title, $email);
        $this->assertStringContainsString($prologue, $email);
        $this->assertStringContainsString($epilogue, $email);
    }

    /** @test */
    public function post_can_have_prologue_and_epilogue()
    {
        $this->createAndLoginUser();

        $test_title = 'This is a Test Title';
        $test_body = 'This is the test body';
        $test_prologue = 'The prologue';
        $test_epilogue = 'The epilogue';

        $this->post('/Blog', [
            'title'    => $test_title,
            'body'     => $test_body,
            'prologue' => $test_prologue,
            'epilogue' => $test_epilogue
        ]);

        $this->assertDatabaseHas('posts', [
            'title'     => $test_title,
            'body'      => $test_body,
            'prologue'  => $test_prologue,
            'epilogue'  => $test_epilogue,
            'published' => null
        ]);

        // Should see prologue for drafts
        $this->get(route('blog.drafts'))
            ->assertSee($test_title)
            ->assertSee('Body')
            ->assertSee('Prologue')
            ->assertSee('Epilogue')
            ->assertSee($test_prologue)
            ->assertSee($test_epilogue);

        // But not in Published posts
        $post = Post::first();
        $post->published = Carbon::now()->subSecond()->toDateTimeString();
        $post->save();

        $this->get(route('blog'))
            ->assertSee($test_title)
            ->assertDontSee('Body')
            ->assertDontSee('Prologue')
            ->assertDontSee('Epilogue')
            ->assertDontSee($test_prologue)
            ->assertDontSee($test_epilogue);
    }

    /** @test */
    public function show_post_by_slug()
    {
        $post = Post::factory()->create([
            'published' => Carbon::now()->subDay()->toDateTimeString()
        ]);

        $this->get('/Blog/' . $post->slug)
            ->assertOk()
            ->assertSee($post->title)
            ->assertSee($post->body);
    }

    // Guest can subscribe

    // subscriber can unsubscribe

    // Can view posts by a title slug

    // Stats record visitors

    // Republish an old post (make it sticky)

    // Includes Markdown package

    // Can include an image in a Post

    private function createAndLoginUser()
    {
        $this->user = User::factory()->create();

        $this->response = $this->actingAs($this->user);

        return $this->user;
    }

    private function createAndLoginMaster()
    {
        $this->user = User::factory()->create(['is_super_admin' => true]);

        $this->response = $this->actingAs($this->user);

        return $this->user;
    }
}
