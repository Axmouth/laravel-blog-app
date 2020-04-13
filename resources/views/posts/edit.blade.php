@extends('layouts.app')

@section('content')
    <h1>Edit Post</h1>
    {!! Form::open(['action' => ['PostsController@update', $post->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        @csrf
        <div class="form-group">
            {{Form::label('title', 'Title')}}
            {{Form::text('title', $post->title, ['class' => 'form-control', 'placeholder' => 'Title'])}}
        </div>
        <div class="form-group">
            {{Form::label('body', 'Body')}}
            {{Form::textarea('body', $post->body, ['id' => 'article-ckeditor','class' => 'form-control', 'placeholder' => 'Body Text'])}}
        </div>
        <div class="form-group">
            {{Form::label('cover_image', 'Cover Image')}}
            {{Form::file('cover_image')}}
        </div>
        {{Form::hidden('_method', 'PUT')}}
        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
    <br>
    <br>
    @if ($post->cover_image)
    {!! Form::open(['action' => ['PostsController@removeCoverImage', $post->id], 'method' => 'DELETE']) !!}
    @csrf
    {!! Form::submit('Remove Cover Image ('.$post->cover_image.')', ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
    <img src="/storage/cover_images/{{$post->cover_image}}" alt="Cover Image" style="width:100%">
    @endif
@endsection
