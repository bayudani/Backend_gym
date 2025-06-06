<?php

namespace App\Http\Controllers;

use Firefly\FilamentBlog\Models\Category;
use Firefly\FilamentBlog\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
   public function getAllCategories()
{
    $categories = Category::withCount(['posts as total_posts' => function ($query) {
        $query->where('status', 'published');
    }])->get();

    // mapping kalau mau lebih clean (optional)
    $cleanCategories = $categories->map(function ($category) {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'total_posts' => $category->total_posts,
        ];
    });

    return response()->json($cleanCategories);
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
