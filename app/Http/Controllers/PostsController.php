<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\BlogComment;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $postsCollection = Post::orderBy('created_at', 'desc');

        $search = $request->query('search');

        if ($search) {
            $postsCollection = $postsCollection->whereRaw('searchtext @@ plainto_tsquery(\'english\', ?)', [$search])
                ->orderByRaw('ts_rank(searchtext, plainto_tsquery(\'english\', ?)) DESC', [$search]);
        }

        $posts = $postsCollection->paginate(10);

        $data = array(
            'posts' => $posts,
            'search' => $search,
        );

        return view('posts.index')->with($data);
    }

    /**
     * Upload images from CKEditor
     *
     * @return \Illuminate\Http\Response
     */
    public function imgUpload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('images'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/' . $fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";


            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'title' => 'required|max:250',
                'body' => 'required|max:1999',
                'cover_image' => 'image|nullable|max:1999',
            ]
        );

        // Handle file upload
        if ($request->hasFile('cover_image')) {
            // Get filename with the extension
            $filenameWithExt =  $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        // Create Post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!ctype_digit($id)) {
            return redirect('/posts/')->with('error', 'Bad Request')->setStatusCode(400);
        }

        $post = Post::find($id);

        if (!$post) {
            return redirect('/posts/')->with('error', 'Post Not Found')->setStatusCode(404);
        }

        $comments = BlogComment::where('blog_post_id', '=', $id)->paginate(25);

        $data = array(
            'post' => $post,
            'comments' => $comments,
        );

        return view('posts.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!ctype_digit($id)) {
            return redirect('/posts/')->with('error', 'Bad Request')->setStatusCode(400);
        }

        $post = Post::find($id);

        if (!$post) {
            return redirect('/posts/')->with('error', 'Post Not Found')->setStatusCode(404);
        }

        // Check for correct user
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        return view('posts.edit')->with('post', $post);
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
        // Edit Post
        if (!ctype_digit($id)) {
            return redirect('/posts/')->with('error', 'Bad Request')->setStatusCode(400);
        }

        $post = Post::find($id);

        if (!$post) {
            return redirect('/posts/')->with('error', 'Post Not Found')->setStatusCode(404);
        }

        $this->validate(
            $request,
            [
                'title' => 'required|max:250',
                'body' => 'required|max:1999',
                'cover_image' => 'image|nullable|max:1999',
            ]
        );

        // Handle file upload
        if ($request->hasFile('cover_image')) {
            // Get filename with the extension
            $filenameWithExt =  $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        }

        // Check for correct user
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if ($request->hasFile('cover_image')) {
            $old_image = $post->cover_image;
            $post->cover_image = $fileNameToStore;
            // Delete old image
            Storage::delete('public/cover_images/' . $old_image);
        }
        $post->save();

        return redirect('/posts/' . $id)->with('success', 'Post Edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete Post
        if (!ctype_digit($id)) {
            return redirect('/posts/')->with('error', 'Bad Request')->setStatusCode(400);
        }

        $post = Post::find($id);

        if (!$post) {
            return redirect('/posts/')->with('error', 'Post Not Found')->setStatusCode(404);
        }

        // Check for correct user
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        if ($post->cover_image !== 'noimage.jpg') {
            // Delete image
            Storage::delete('public/cover_images/' . $post->cover_image);
        }

        $post->delete();

        return redirect('/posts')->with('success', 'Post Deleted');
    }

    /**
     * Remove the specified Post's Cover Image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeCoverImage(Request $request, $id)
    {
        if (!ctype_digit($id)) {
            return redirect('/posts/')->with('error', 'Bad Request')->setStatusCode(400);
        }

        $post = Post::find($id);

        if (!$post) {
            return redirect('/posts/')->with('error', 'Post Not Found')->setStatusCode(404);
        }

        // Check for correct user
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        $old_image = $post->cover_image;
        $post->cover_image = '';
        $post->save();
        // Delete image
        Storage::delete('public/cover_images/' . $old_image);

        return redirect('/posts/' . $id)->with('success', 'Post Edited');
    }
}
