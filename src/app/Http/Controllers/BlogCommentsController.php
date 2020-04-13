<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogComment;
use App\Post;

class BlogCommentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => []]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'title' => 'required|max:100',
                'body' => 'required|max:450',
            ]
        );

        // Create Post
        $comment = new BlogComment();
        $comment->title = $request->input('title');
        $comment->body = $request->input('body');
        $comment->user_id = auth()->user()->id;
        $comment->blog_post_id = $id;
        $comment->save();

        return redirect('/posts/' . $id . '#comments' . $comment->id)->with('success', 'Comment Posted');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!ctype_digit($id)) {
            return redirect('/posts/')->with('error', 'Bad Request')->setStatusCode(400);
        }

        $comment = BlogComment::find($id);

        if (!$comment) {
            return redirect('/posts/')->with('error', 'Comment Not Found')->setStatusCode(404);
        }

        $post_id = $comment->post->id;

        // Check for correct user
        if (auth()->user()->id !== $comment->user_id) {
            return redirect('/posts/' . $post_id)->with('error', 'Unauthorized Page');
        }

        $comment->delete();

        return redirect('/posts/' . $post_id . '#comments')->with('success', 'Comment Removed');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!ctype_digit($id)) {
            return redirect('/posts/')->with('error', 'Bad Request')->setStatusCode(400);
        }

        $comment = BlogComment::find($id);

        if (!$comment) {
            return redirect('/posts/')->with('error', 'Comment Not Found')->setStatusCode(404);
        }

        $post = $comment->post;
        $post_id = $post->id;


        // Check for correct user
        if (auth()->user()->id !== $comment->user_id) {
            return redirect('/posts/' . $post_id . '#comments' . $comment->id)->with('error', 'Unauthorized Page');
        }

        $data = array(
            'comment' => $comment,
            'post' => $post,
        );

        return view('posts.editcomment')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!ctype_digit($id)) {
            return redirect('/posts/')->with('error', 'Bad Request')->setStatusCode(400);
        }

        // Edit Post
        $comment = BlogComment::find($id);

        if (!$comment) {
            return redirect('/posts/')->with('error', 'Comment Not Found')->setStatusCode(404);
        }

        $post = $comment->post;
        $post_id = $post->id;

        $this->validate(
            $request,
            [
                'title' => 'required|max:100',
                'body' => 'required|max:450',
            ]
        );

        // Check for correct user
        if (auth()->user()->id !== $comment->user_id) {
            return redirect('/posts/' . $post->id . '#comments' . $comment->id)->with('error', 'Unauthorized Page');
        }

        $comment->title = $request->input('title');
        $comment->body = $request->input('body');
        $comment->save();

        return redirect('/posts/' . $post->id . '#comments' . $comment->id)->with('success', 'Post Edited');
    }
}
