@extends('layouts.app')

@section('content')
<br>
<h5><a href="/users/{{$user->id}}">View my profile</a></h5>
<br>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a href="/dashboard" class="nav-link">Your Blog Posts</a>
    </li>
    <li class="nav-item">
        <a href="/myprofile" class="nav-link active">Edit My Profile</a>
    </li>
    <li class="nav-item">
        <a href="/mysecurity" class="nav-link">Account Security</a>
    </li>
</ul>
<br>
<h1>{{$user->name}}'s Profile</h1>
<br>
<div class="row">
    <div class="col-md-4 col-sm-4">
        @if ($user->profile_image)
        <img class="profile-header-image" src="/storage/profile_images/{{$user->profile_image}}" alt="Profile Image" style="width:100%">
        @endif
        <br>
        <br>
        {!! Form::open(['action' => ['MyprofileController@removeProfileImage'], 'method' => 'DELETE']) !!}
        @csrf
        {!! Form::submit('Remove Profile Image', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </div>
    <div class="col-md-8 col-sm-4">
        {!! Form::open(['action' => 'MyprofileController@profileUpdate', 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
        @csrf
        <div class="form-group">
            {{Form::label('name', 'Change Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'New Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('profile_image', 'Change Profile Picture')}}
            {{Form::file('profile_image')}}
        </div>
        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
</div>
<hr>


@endsection
