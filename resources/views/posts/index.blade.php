@extends('layouts.app')

@section('content')
@if ($search)
<h1>Search Results for "{{$search}}"</h1>
@else
<h1>Posts</h1>
@endif
@if (count($posts) > 0)
@if ($search)
<small>{{$posts->total()}} Results</small>
@endif
@foreach ($posts as $post)
<div class="card bg-light">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 col-sm-4">
                @if ($post->cover_image)
                <a href="/posts/{{$post->id}}">
                    <img src="/storage/cover_images/{{$post->cover_image}}" alt="Cover Image" style="width:100%">
                </a>
                @else
                <a href="/posts/{{$post->id}}">
                    <img src="/storage/nopostimage.jpg" alt="Cover Image" style="width:100%">
                </a>
                @endif
            </div>
            <div class="col-md-4 col-sm-4">
                <h3><a href="/posts/{{$post->id}}">{{$post->title}}</a></h3>
                <small>Written on {{$post->created_at}} by <a href="/users/{{$post->user->id}}">
                    @if ($post->user->profile_image)
                    <img class="profile-image" src="/storage/profile_images/{{$post->user->profile_image}}" alt="Profile Image" style="width:100%">
                    @endif
                </small>{{$post->user->name}}</a>
                @if ($post->getCommentsCount() > 0)
                <p><a href="/posts/{{$post->id}}#comments">{{$post->getCommentsCount()}} Comments</a></p>
                @endif
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
@endsection
