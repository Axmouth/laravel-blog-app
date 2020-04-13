@extends('layouts.app')

@section('content')
<h2>Edit Comment</h1>
    <small>From post <a href="/posts/{{$post->id}}">{{$post->title}}</a></small>
    {!! Form::open(['action' => ['BlogCommentsController@update', $comment->id], 'method' => 'PUT']) !!}
    @csrf
    <div class="form-group">
        {{Form::label('title', 'Title')}}
        {{Form::text('title', $comment->title, ['class' => 'form-control', 'placeholder' => 'Title'])}}
    </div>
    <div class="form-group">
        {{Form::label('body', 'Body')}}
        {{Form::textarea('body', $comment->body, ['class' => 'form-control', 'placeholder' => 'Body Text'])}}
    </div>
    {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}

    @endsection
