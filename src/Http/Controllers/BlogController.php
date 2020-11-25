<?php

namespace DarkBlog\Http\Controllers;

use DarkBlog\Models\Post;
use DarkBlog\Models\Subscriber;
use DarkBlog\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()->get();

        return view('darkblog::index', [
            'posts' => $posts
        ]);
    }

    public function admin(Request $request)
    {
        return view('darkblog::admin');
    }

    public function edit($id)
    {
        return view('darkblog::edit', ['post' => Post::find($id)]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body'  => 'required'
        ]);

        Post::create([
            'user_id'  => $request->user()->id,
            'title'    => $request['title'],
            'body'     => $request['body'],
            'prologue' => $request['prologue'],
            'epilogue' => $request['epilogue']
        ]);

        return redirect('/Blog/drafts');
    }

    public function storeSubscription(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'name'  => 'required'
        ]);

        Subscriber::create([
            'email' => $request['email'],
            'name'  => $request['name']
        ]);

        return redirect('/Blog');
    }

    public function storeSubscriptionForce(Request $request)
    {
        $this->getPermittedUserOrAbort($request->user(), 'subscribe.force');

        $this->validate($request, [
            'email' => 'required|email',
            'name'  => 'required'
        ]);

        $subscriber = Subscriber::create([
            'email' => $request['email'],
            'name'  => $request['name']
        ]);

        // A forced Subscription means the email does not have to be verified
        $subscriber->verify();

        return redirect(route('blog.subscribers'));
    }

    public function subscribe()
    {
        return view('darkblog::subscribe');
    }

    public function subscribeForce(Request $request)
    {
        $this->getPermittedUserOrAbort($request->user(), 'subscribe.force');

        return view('darkblog::subscribe-force');
    }

    public function show($slug_or_id)
    {
        if (is_numeric($slug_or_id)) {
            $post = Post::find($slug_or_id);
        }
        else {
            $post = Post::where('slug', $slug_or_id)->first();
        }

        if ( ! $post) {
            throw new \Exception('No Post Found');
        }

        return view('darkblog::show', ['post' => $post]);
    }

    public function showDrafts(Request $request)
    {
        $posts = Post::draft()->get();

        return view('darkblog::drafts', [
            'posts' => $posts
        ]);
    }

    public function showPublished(Request $request)
    {
        $posts = Post::published()->get();

        return view('darkblog::published', [
            'posts' => $posts
        ]);
    }

    public function showScheduled(Request $request)
    {
        $posts = Post::scheduled()->get();

        return view('darkblog::drafts', [
            'posts' => $posts
        ]);
    }

    public function showSubscribers()
    {
        $subscribers = Subscriber::all();

        return view('darkblog::subscribers', [
            'subscribers' => $subscribers
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $post->title = $request['title'];
        $post->body = $request['body'];
        $post->published = $request['published'];
        $post->prologue = $request['prologue'];
        $post->epilogue = $request['epilogue'];
        $post->save();

        return redirect('/Blog/' . $post->id);
    }

    public function addTag(Request $request, Post $post)
    {
        $tag = Tag::firstOrCreate(['name' => $request['tag']]);

        $post->tags()->save($tag);
    }

    public function showTag(Request $request, $tag)
    {
        $posts = Post::tagged($tag)->get();

        return view('darkblog::tag', [
            'tag'   => $tag,
            'posts' => $posts
        ]);
    }

    public function create()
    {
        return view('darkblog::create');
    }

    public function destroy(Request $request, Post $post)
    {
        $post->delete();

        return redirect('/Blog');
    }

    public function publish(Request $request, Post $post)
    {
        $post->publish();

        return redirect('/Blog');
    }

    public function schedule(Request $request, Post $post, $schedule)
    {
        $carbon = Carbon::parse($schedule);

        $post->schedule($carbon);

        return redirect('/Blog');
    }
}
