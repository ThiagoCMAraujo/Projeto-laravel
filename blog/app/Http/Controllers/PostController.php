<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->only('store', 'destroy');
    }
    public function postsall()
    {
        $posts = Post::orderBy('created_at', 'desc')->with(['user', 'likes'])->paginate(2);
        // $posts = Post::latest()->with(['user', 'likes'])->paginate(2);

        return view('posts.postsall', ['posts' => $posts]);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'description' => 'min:10|max:2000',
        ]);

        // dd(auth()->user()->id);

        $post = Post::create([
            'user_id' => auth()->user()->id,
            'description' => $request->description,
        ]);

        $post->save();

        //  $request->user()->posts()->create([
        //      'description' => $request->description,
        //  ]);

        // $request->user()->posts()->create($request->only('description'));

        return back();
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return back();
    }

    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post,
        ]);
    }
}
