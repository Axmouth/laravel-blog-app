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
        <a href="/myprofile" class="nav-link">Edit My Profile</a>
    </li>
    <li class="nav-item">
    <a href="/mysecurity" class="nav-link active">Account Security</a>
    </li>
</ul>
<br>
<hr>
<h5><a href="/password/reset">Reset/Change Password</a></h5>
<hr>

@endsection
