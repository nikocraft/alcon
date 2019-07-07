<?php

namespace App\Http\Controllers\Backend\SPA\Comments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Comments\Comment;
use App\Models\Core\Content\Content;
use Session;

use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $page = $request->page;
        $append = array();

        $comments = Comment::with('content')->latest();

        if($status) {
            switch ($request->status) {
                case 'approved':
                    $comments = $comments->approved();
                break;

                case 'pending':
                    $comments = $comments->pending();
                break;

                case 'spam':
                    $comments = Comment::spam();
                break;
            }
        }

        $comments = $comments->orderBy('id', 'desc')->paginate(3);

        $commentsCount = Comment::latest()->count();
        $approvedCount = Comment::where('status', Comment::APPROVED)->count();
        $pendingCount = Comment::where('status', Comment::PENDING)->count();
        $spamCount = Comment::where('status', Comment::SPAM)->count();

        return CommentResource::collection($comments)
            ->additional(compact('commentsCount', 'approvedCount', 'pendingCount', 'spamCount'));
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->status = $request->status;
        $comment->save();
        return new CommentResource($comment);
    }

    public function destroy(Request $request, $id) {
        $comment = Comment::findOrFail($id);

        if($comment->delete()) {
            return response()->json(null, 204);
        } else {
            return response()->json([
                'message' => 'Could not delete.'
            ], 403);
        }
    }
}
