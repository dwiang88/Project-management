<?php

class CommentsController extends BaseController {

    public function submit()
    {
        //dd(Input::all());

        $comment = new Comment;
        $comment->commentable_id = Input::get('type_id');
        $comment->commentable_type = Input::get('type');
        $comment->user_id = Auth::user()->id;
        $comment->content = Input::get('comment');
        $comment->save();

        //Go back to the page when successfully submited
        return Redirect::back();
    }

    public function edit()
    {
        //only if ajax request
        if (Request::ajax())
        {
            $comment = Comment::find(Input::get('id'));
            //check if the user is the same who created comment
            if($comment->user_id == Auth::user()->id){

                $comment->content = Input::get('content');
                $comment->save(); //Save new comment
            }
        }else{
            App::abort(404);
        }
    }

}