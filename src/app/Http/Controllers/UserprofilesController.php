<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\BlogComment;

class UserprofilesController extends Controller
{
    public function show($id)
    {
        if (!ctype_digit($id)) {
            return redirect('/posts/')->with('error', 'Bad Request')->setStatusCode(400);
        }

        return redirect('/users/' . $id . '/posts/');
    }

    public function showPosts($id)
    {
        if (!ctype_digit($id)) {
            return redirect('/posts/')->with('error', 'Bad Request')->setStatusCode(400);
        }

        $user =  User::find($id);

        if (!$user) {
            return redirect('/posts/')->with('error', 'Profile Not Found')->setStatusCode(404);
        }

        $posts = Post::orderBy('created_at', 'desc')->where('user_id', '=', $id)->paginate(10);
        $data = array(
            'posts' => $posts,
            'user' => $user,
        );

        return view('userprofiles.blogposts')->with($data);
    }

    public function showComments($id)
    {
        if (!ctype_digit($id)) {
            return redirect('/posts/')->with('error', 'Bad Request')->setStatusCode(400);
        }

        $user =  User::find($id);

        if (!$user) {
            return redirect('/posts/')->with('error', 'Profile Not Found')->setStatusCode(404);
        }

        $posts = Post::orderBy('created_at', 'desc')->where('user_id', '=', $id)->paginate(10);
        $comments = BlogComment::orderBy('created_at', 'desc')->where('user_id', '=', $id)->paginate(25);
        $data = array(
            'posts' => $posts,
            'user' => $user,
            'comments' => $comments,
        );

        return view('userprofiles.blogpostcomments')->with($data);
    }
}
