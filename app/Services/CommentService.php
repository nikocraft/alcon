<?php

namespace App\Services;

use Auth;
use Timezone;
use Carbon\Carbon;
use App\Models\Core\Comments\Comment;
use App\Models\Core\Comments\Subscriber;

class CommentService
{
    public function store($request)
    {
        if(get_website_setting('comments.moderation')) {
            $status = Comment::PENDING;
        } else {
            $status = Comment::APPROVED;
        }

        if(Auth::user()) {
            $username = empty(Auth::user()->firstname) ? Auth::user()->username : Auth::user()->firstname .' '. Auth::user()->lastname;
            $user_id = Auth::user()->id;
            $status = Comment::APPROVED;
            $email = Auth::user()->email;
            $name = $username;
            $website = $request->website;
        } else {
            $user_id = null;
            $email = $request->email;
            $name = $request->name;
            $website = $request->website;
        }

        $contentId = $request->content_id;
        $body = $request->body;
        $parentId = $request->parent_id;
        $visitorIp = $request->ip;

        $comment = Comment::create([
            'user_id' => $user_id,
            'content_id' => $contentId,
            'body' => $body,
            'parent_id' => $parentId,
            'name' => $name,
            'email' => $email,
            'website' => $website,
            'visitor_ip' => $visitorIp,
            'status' => $status
        ]);

        return $comment;
    }
}