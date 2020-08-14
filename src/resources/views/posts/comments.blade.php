@foreach ($comments as $comment)

<div class="card bg-light" id="comments{{$comment->id}}">
    <div class="card-body">
        <h5>{{$comment->title}}</h5><a class="float-right" href="/posts/{{$comment->post->id}}#comments{{$comment->id}}">{{$comment->id}}#</a>
        <small>Posted on {{$comment->created_at}} by <a href="/users/{{$comment->user->id}}"><img class="profile-image" src="/storage/profile_images/{{$comment->user->profile_image}}" alt="Profile Image" style="width:100%">
                {{$comment->user->name}}</a></small>
        <p>{{$comment->body}}</p>

        @if (Auth::user() && Auth::user()->id === $comment->user_id)
        <div class="btn-group">
            <button class="btn" type="button" id="dropdownMenu{{$comment->id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons">more_vert</i></button>
            <div class="dropdown">
                <div class="dropdown-menu" aria-labelledby="dropdownMenu{{$comment->id}}">
                    <a class="dropdown-item" href="/comments/{{$comment->id}}/edit">Edit</a>
                    <a class="dropdown-item" href="/comments/{{$comment->id}}" onclick="event.preventDefault();
                                       document.getElementById('remove-comment-form').submit();">
                        {{ __('Remove') }}
                    </a>
                    {!! Form::open(['action' => ['BlogCommentsController@destroy', $comment->id], 'method' => 'DELETE', 'style' => 'display: none;', 'id' => 'remove-comment-form']) !!}
                    @csrf
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
<br>

@endforeach
