@extends('layouts.app')

@section('content')
<a href="/posts" class="btn btn-outline-secondary">
    < Go Back</a> <h1>{{$post->title}}</h1>
        @if ($post->cover_image)
        <img src="/storage/cover_images/{{$post->cover_image}}" alt="Cover Image" style="width:100%">
        @endif
        <div>
            {!! $post->body !!}
        </div>
        <hr>
        <small>Written on {{$post->created_at}} by <a href="/users/{{$post->user->id}}">
            @if ($post->user->profile_image)
            <img class="profile-image" src="/storage/profile_images/{{$post->user->profile_image}}" alt="Profile Image" style="width:100%">
            @endif
            {{$post->user->name}}</a></small>
        <hr>
        @if (!Auth::guest())
        @if (Auth::user()->id === $post->user_id)
        <a href="/posts/{{$post->id}}/edit" class="btn btn-outline-secondary">Edit</a>
        {!! Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'DELETE', 'class' => 'float-right']) !!}
        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
        @endif
        @endif
        <hr>

        @auth
        @include('posts.commentform')
        @endauth
        @guest
        <hr>
        <h4><a href="/login">Login</a>/<a href="/register">Register</a> in order to post comments</h4>
        @endguest

        <hr>
        <h4 id="comments">{{$comments->total()}} Comments</h4>

        @include('posts.comments')
        @endsection
