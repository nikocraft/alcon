<?php

namespace App\Http\Controllers\Frontend\Core\Comments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Session;

use App\Models\User;
use App\Models\Core\Content\Content;
use App\Models\Core\Comments\Comment;
use App\Models\Core\Comments\Subscriber;

use App\Services\CommentService;

use App\Http\Resources\Frontend\CommentResource;
use App\Http\Requests\Frontend\PostCommentRequest;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
	{
        $this->commentService = $commentService;
    }
    
    public function index(Request $request) {
        $comments = Comment::approved()->with('author')->where('content_id', $request->content)->get();
        $comments = CommentResource::collection($comments);
        $user = Auth::user() ? User::findOrFail(Auth::user()->id)->select('firstname', 'lastname', 'username')->first() : null;

        return response()->json([
            'comments' => $comments,
            'user' => $user
        ], 200);
    }

    public function store(PostCommentRequest $request)
    {
        $comment = $this->commentService->store($request);

        return response()->json([
            'comment' => $comment
        ], 200);
    }
}
