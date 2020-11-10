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
        return view('darkblog::admin', [
            'draft_count'     => Post::draft()->count(),
            'published_count' => Post::published()->count()
        ]);
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
            'user_id' => $request->user()->id,
            'title'   => $request['title'],
            'body'    => $request['body']
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

        Subscriber::create([
            'email' => $request['email'],
            'name'  => $request['name']
        ]);

        return redirect('/Blog/subscribers');
    }

    public function subscribe()
    {
        return view('darkblog::subscribe');
    }

    public function subscribeForce(Request $request)
    {
        $this->getPermittedUserOrAbort($request->user(), 'subscribe.force');

        return view('darkblog::subscribe');
    }

    public function show(Post $post)
    {
        return view('darkblog::show', ['post' => $post]);
    }

    public function showDrafts(Request $request)
    {
        $posts = Post::draft()->get();

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
        $post->status = $request['status'];
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