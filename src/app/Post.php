<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\BlogComment;

class Post extends Model
{
    // Table Name
    protected $table = 'posts';

    // Primary Key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCommentsCount()
    {
        $count = BlogComment::where('blog_post_id', '=', $this->id)->count();
        return $count;
    }
}
