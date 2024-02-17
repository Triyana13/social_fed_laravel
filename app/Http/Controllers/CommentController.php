<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function index($id) {
        $post = Post::find($id);
    if(!$post) {
        return response([
            'message' => 'Post not found'
        ],403);
    }
    // return response([
    //     'comments' =>$post->comments()->with('user:id','name','image')->get()
    // ],200);
    // return response([
    //     'comments' => $post->comments()->with('user')->get()
    // ], 200);
    return response([
        'comments' => $post->comments()->with(['user' => function ($query) {
            $query->select('id', 'name', 'image');
        }])->get()
    ], 200);
    }


    public function store(Request $request, $id) {
        $post = Post::find($id);
    if(!$post) {
        return response([
            'message' => 'Post not found'
        ],403);
    }

    $attrs = $request->validate([
        'comments' => 'required|string'
    ]);
    
    Comment::create([
        'comment' => $attrs['comments'],
        'post_id' => $id,
        'user_id' => auth()->user()->id
    ]);

    return response([
        'message' => 'Comment Created'
    ],200);
    }

    public function update(Request $request, $id){
        $comment = Comment::find($id);

        if(!$comment){
            return response ([
                'message' => 'Comment not found'
            ],403);
        }
        if($comment->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission Denied'
            ],403);
        }
        $attrs =$request->validate([
            'comment' => 'required|string'
        ]);
        $comment->update([
            'comment' => $attrs['comment']
        ]);
        return response([
            'message' => 'Comment updated'
        ],200);
    }


    public function destroy($id) {
        if(!$comment){
            return response ([
                'message' => 'Comment not found'
            ],403);
        }
        if($comment->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission Denied'
            ],403);
        }
        $comment->delete();
        return response([
            'message' => 'Comment deleted'
        ],200);
    }
}
