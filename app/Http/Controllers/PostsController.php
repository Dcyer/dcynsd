<?php

namespace App\Http\Controllers;

use App\Post;
use App\Services\PostService;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index(Request $request)
    {
        $tag         = $request->get('tag');
        $postService = new PostService($tag);
        $data        = $postService->lists();
        $layout      = $tag ? Tag::layout($tag) : 'posts.index';

        return view($layout, $data);
    }

    public function show($slug, Request $request)
    {
        $post = Post::with('tags')->where('slug', $slug)->firstOrFail();
        $tag  = $request->get('tag');
        if ($tag) {
            $tag = Tag::where('tag', $tag)->firstOrFail();
        }

        return view($post->layout, compact('post', 'tag'));
    }
}
