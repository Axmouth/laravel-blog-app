@extends('layouts.app')

@section('content')
@include('userprofiles.profileheader')
<ul class="nav nav-tabs">
    <li class="nav-item">
    <a href="/users/{{$user->id}}/posts" class="nav-link active">Blog Posts</a>
    </li>
    <li class="nav-item">
        <a href="/users/{{$user->id}}/comments" class="nav-link">Comments</a>
    </li>
</ul>
<h2>{{$user->name}}'s Posts</h2>
<small>{{$posts->total()}} Results</small>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (count($posts) > 0)
            @foreach ($posts as $post)
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        @if ($post->cover_image)
                        <a href="/posts/{{$post->id}}">
                            <img src="/storage/cover_images/{{$post->cover_image}}" alt="Cover Image" style="width:100%">
                        </a>
                        @else
                        <a href="/posts/{{$post->id}}">
                            <img src="/nopostimage.jpg" alt="Cover Image" style="width:100%">
                        </a>
                        @endif
                        <div class="col-md-4 col-sm-4">
                            <h3><a href="/posts/{{$post->id}}">{{$post->title}}</a></h3>
                            <small>Written on {{$post->created_at}} by
                                @if ($post->user->profile_image)
                                <img class="profile-image" src="/storage/profile_images/{{$post->user->profile_image}}" alt="Profile Image" style="width:100%">
                                @endif
                                <a href="/users/{{$post->user->id}}">{{$post->user->name}}</a></small>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            @endforeach
            {{$posts->links()}}
            @else
            <p>No posts found</p>
            @endif
        </div>
    </div>
</div>
@endsection
