<?php

namespace App\Http\Controllers;

use Firefly\FilamentBlog\Blog;
use Firefly\FilamentBlog\Models\Category;
use Firefly\FilamentBlog\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class blogController extends Controller
{
    //
    // get all blog post, filter by status 'published'
    public function index(Request $request)
    {
        // get all blog post, filter by status 'published'
        $posts = Post::where('status', 'published')->get();

        // mapping biar hapus tag HTML
        $cleanPosts = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'sub_title' => $post->sub_title,
                'excerpt' => Str::limit(strip_tags($post->body), 200), // bisa buat preview
                'body' => $post->body,
                'status' => $post->status,
                'published_at' => $post->published_at,
                'cover_photo_path' => $post->cover_photo_path,
                'photo_alt_text' => $post->photo_alt_text,
                'user_id' => $post->user_id,
            ];
        });

        return response()->json($cleanPosts);

        // return view with posts
        // return response()->json($posts);
    }

    // get single blog post by slug
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'sub_title' => $post->sub_title,
            'body' => $post->body, // hilangkan HTML di sini juga
            'status' => $post->status,
            'published_at' => $post->published_at,
            'cover_photo_path' => $post->cover_photo_path,
            'photo_alt_text' => $post->photo_alt_text,
            'user_id' => $post->user_id,
        ]);
    }

    // get all category
    public function getAllCategories(Request $request)
    {
        $category = Category::with(['posts' => function ($query) {
                $query->where('status', 'published');
            }])
            ->firstOrFail();

        $posts = Post::all();

        // mapping biar hapus tag HTML
        $cleanPosts = $posts->map(function ($posts) {
            return [
                'id' => $posts->id,
                'title' => $posts->title,
                'slug' => $posts->slug,
            ];
        });
        return response()->json([
            'category' => $category->name,
            'posts' => $cleanPosts,
        ]);
    }

    // get blog by category
    public function getPostByCategoryName($name)
    {
        $category = Category::where('name', $name)
            ->with(['posts' => function ($query) {
                $query->where('status', 'published');
            }])
            ->firstOrFail();

        $posts = Post::all();

        // mapping biar hapus tag HTML
        $cleanPosts = $posts->map(function ($posts) {
            return [
                'id' => $posts->id,
                'title' => $posts->title,
                'slug' => $posts->slug,
            ];
        });
        return response()->json([
            'category' => $category->name,
            'posts' => $cleanPosts,
        ]);
    }
}
