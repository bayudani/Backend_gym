<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firefly\FilamentBlog\Models\Comment;

class ComentarController extends Controller
{
    // Get all approved comments
    public function index()
    {
        $comments = Comment::with(['post', 'user']) // pastikan relasi ini benar
            ->where('approved', true)
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => strip_tags($comment->comment),
                    'post_title' => $comment->post->title ?? '[judul tidak ditemukan]',
                    'user_name' => $comment->user->name ?? '[anonim]',
                    'created_at' => $comment->created_at->toDateTimeString(),
                ];
            });

        return response()->json($comments);
    }

    // Store new comment
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'post_id' => 'required|exists:fblog_posts,id',
                'comment' => 'required|string|max:1000',
            ]);

            $userId = auth()->id();
            $postId = $validated['post_id'];

            // Cek apakah user sudah pernah komentar di post ini
            $existingComment = Comment::where('user_id', $userId)
                ->where('post_id', $postId)
                ->first();

            if ($existingComment) {
                return response()->json([
                    'message' => 'Kamu sudah pernah komentar di postingan ini.',
                ], 409); // 409 = Conflict
            }

            $comment = Comment::create([
                'name' => auth()->user()->name,
                'user_id' => $userId,
                'post_id' => $postId,
                'comment' => $validated['comment'],
                'approved' => false,
            ]);

            return response()->json([
                'message' => 'Komentar berhasil dikirim! Menunggu persetujuan admin.',
                'comment' => $comment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengirim komentar!',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Tambahkan di dalam class ComentarController
public function destroy($id)
{
    try {
        $comment = Comment::findOrFail($id);

        // Cek apakah user yang login adalah pemilik komentar
        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Kamu tidak punya akses untuk menghapus komentar ini.',
            ], 403); // Forbidden
        }

        $comment->delete();

        return response()->json([
            'message' => 'Komentar berhasil dihapus.',
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Gagal menghapus komentar!',
            'message' => $e->getMessage(),
        ], 500);
    }
}

}
