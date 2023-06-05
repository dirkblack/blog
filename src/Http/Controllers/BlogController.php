<?php

namespace DarkBlog\Http\Controllers;

use DarkBlog\Mail\SubscriberEmail;
use DarkBlog\Models\Post;
use DarkBlog\Models\Subscriber;
use DarkBlog\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()->get();

        return view('blog::index', [
            'posts' => $posts
        ]);
    }

    public function admin(Request $request)
    {
        return view('blog::admin');
    }

    public function edit($id)
    {
        return view('blog::edit', ['post' => Post::find($id)]);
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
            'epilogue' => $request['epilogue'],
            'preview'  => $request['preview']
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
        return view('blog::subscribe');
    }

    public function subscribeForce(Request $request)
    {
        $this->getPermittedUserOrAbort($request->user(), 'subscribe.force');

        return view('blog::subscribe-force');
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

        return view('blog::show', ['post' => $post]);
    }

    public function showDrafts(Request $request)
    {
        $posts = Post::draft()->get();

        return view('blog::drafts', [
            'posts' => $posts
        ]);
    }

    public function showPublished(Request $request)
    {
        $posts = Post::published()->get();

        return view('blog::published', [
            'posts' => $posts
        ]);
    }

    public function showScheduled(Request $request)
    {
        $posts = Post::scheduled()->get();

        return view('blog::drafts', [
            'posts' => $posts
        ]);
    }

    public function showSubscribers()
    {
        $subscribers = Subscriber::all();

        return view('blog::subscribers', [
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
        $post->preview = $request['preview'];
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

        return view('blog::tag', [
            'tag'   => $tag,
            'posts' => $posts
        ]);
    }

    public function create()
    {
        return view('blog::create');
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

    public function upload(Request $request)
    {
        $file_name = isset($request['file']) ? $request['file'] : null;

        return view('blog::upload', ['file' => $file_name]);
    }

    public function storeFile(Request $request)
    {
        $path = $request->file('file_upload')->storeAs(
            'public', $request['file_name']
        );

        return redirect(route('blog.drafts'));
    }

    public function sendTestEmail(Request $request, Post $post)
    {
        Mail::to($request->user()->email)->send(new SubscriberEmail($post));

        return redirect((route('blog.show', ['slug' => $post->slug])));
    }
}
