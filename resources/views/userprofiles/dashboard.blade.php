@extends('layouts.app')

@section('content')
<br>
<h5><a href="/users/{{$user->id}}">View my profile</a></h5>
<br>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a href="/dashboard" class="nav-link active">Your Blog Posts</a>
    </li>
    <li class="nav-item">
        <a href="/myprofile" class="nav-link">Edit My Profile</a>
    </li>
    <li class="nav-item">
        <a href="/mysecurity" class="nav-link">Account Security</a>
    </li>
</ul>
<br>
<h1>{{$user->name}}'s Dashboard</h1>
<br>
<a class="btn btn-primary" href="/posts/create">Create Post</a>
<br>
<br>
<div class="row">
    <div class="col-md-8">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        @if (count($posts) > 0)
        <table class="table table-striped">
            <tr>
                <th></th>
                <th>Title</th>
                <th></th>
                <th></th>
            </tr>
            @foreach ($posts as $post)
            <tr>
                <td>
                    @if ($post->cover_image)
                    <a href="/posts/{{$post->id}}">
                        <img src="/storage/cover_images/{{$post->cover_image}}" class="dashboard-cover-image" alt="Cover Image" style="width:100%">
                    </a>
                    @else
                    <a href="/posts/{{$post->id}}">
                        <img src="/nopostimage.jpg" class="dashboard-cover-image" alt="Cover Image" style="width:100%">
                    </a>
                    @endif
                </td>
                <td><a href="/posts/{{$post->id}}">{{$post->title}}</a></td>
                <td><a href="/posts/{{$post->id}}/edit" class="btn btn-outline-secondary">Edit</a></td>
                <td>
                    {!! Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'DELETE', 'class' => 'float-right']) !!}
                    @csrf
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
            @endforeach
        </table>
        @else
        <p>You have no posts</p>
        @endif
    </div>
</div>
@endsection
