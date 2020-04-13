@extends('layouts.app')

@section('content')
@include('userprofiles.profileheader')
<ul class="nav nav-tabs">
    <li class="nav-item">
    <a href="/users/{{$user->id}}/posts" class="nav-link">Blog Posts</a>
    </li>
    <li class="nav-item">
        <a href="/users/{{$user->id}}/comments" class="nav-link active">Comments</a>
    </li>
</ul>
<h2>{{$user->name}}'s Comments</h2>
<small>{{$comments->total()}} Results</small>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (count($comments) > 0)
            @include('posts.comments')
            {{$comments->links()}}
            @else
            <p>No posts found</p>
            @endif
        </div>
    </div>
</div>
@endsection
