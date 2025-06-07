<?php

namespace App\Http\Controllers;

use Firefly\FilamentBlog\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LikeController extends Controller
{
    // 👍 Like a post
    public function like($id)
    {
        try {
            $post = Post::where('id', $id)->where('status', 'published')->firstOrFail();

            if ($post->likes()->where('user_id', auth()->id())->exists()) {
                return response()->json(['message' => 'Kamu sudah like post ini.'], 409);
            }

            $post->likes()->attach(auth()->id());

            return response()->json(['message' => 'Post berhasil di-like!']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post tidak ditemukan atau belum dipublikasikan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat like post.', 'error' => $e->getMessage()], 500);
        }
    }

    // 👎 Unlike a post
    public function unlike($id)
    {
        try {
            $post = Post::where('id', $id)->where('status', 'published')->firstOrFail();

            $post->likes()->detach(auth()->id());

            return response()->json(['message' => 'Like berhasil dibatalkan.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post tidak ditemukan atau belum dipublikasikan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat unlike post.', 'error' => $e->getMessage()], 500);
        }
    }

    // 👀 Get total like count
    public function getLikes($id)
    {
        try {
            $post = Post::where('id', $id)->where('status', 'published')->withCount('likes')->firstOrFail();

            return response()->json(['likes' => $post->likes_count]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post tidak ditemukan atau belum dipublikasikan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil jumlah like.', 'error' => $e->getMessage()], 500);
        }
    }
}
