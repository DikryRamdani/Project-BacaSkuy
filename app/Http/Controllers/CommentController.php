<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Manhwa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Store
    public function store(Request $request, Manhwa $manhwa)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'manhwa_id' => $manhwa->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
        ]);

        if ($request->ajax()) {
            $comment->load('user');
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->format('M d, Y \a\t H:i'),
                    'user' => [
                        'name' => $comment->user->name,
                        'avatar_url' => $comment->user->avatar_url,
                    ],
                ],
                'message' => 'Comment posted successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Comment posted successfully!');
    }

    // Update
    public function update(Request $request, Comment $comment)
    {
        // ownership check
        if (Auth::id() !== $comment->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ]);

        $comment->update([
            'content' => $validated['content'],
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'updated_at' => $comment->updated_at->format('M d, Y \a\t H:i'),
                ],
                'message' => 'Comment updated successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Comment updated successfully!');
    }

    // Destroy
    public function destroy(Comment $comment)
    {
        // ownership check
        if (Auth::id() !== $comment->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $comment->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
}
