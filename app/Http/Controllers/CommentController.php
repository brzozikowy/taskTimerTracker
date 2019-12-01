<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Comment;
use \App\Task;

class CommentController extends Controller
{
    public function addComment(Request $request, $task_id){
        $task = Task::findOrFail($task_id);

        $comment  = new Comment();
        $comment->task_id = $task_id;
        $comment->description = $request->description;

        if(isset($request->auth) && !is_null($request->auth)){
            $comment->user_id = (int)$request->auth;
        }

        $comment->save();

    }
}
