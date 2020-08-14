<h1>{{$user->name}}'s Profile</h2><div class="row">
    <div class="col-md-4 col-sm-4">
        @if ($user->profile_image)
        <img class="profile-header-image" src="/storage/profile_images/{{$user->profile_image}}" alt="Profile Image" style="width:100%">
        @endif
    </div>
    <div class="col-md-8 col-sm-4">
        <small>Member since {{$user->created_at}}</small>
    </div>
</div>
<hr>
