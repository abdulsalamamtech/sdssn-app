<?php

namespace App\Models\Api;

use App\Models\Api\Podcast;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'podcast_id',
        'content',
        'likes'
    ];

    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project that this comment is associated with.
     */
    public function podcast()
    {
        return $this->belongsTo(Podcast::class, 'podcast_id');

    }
}
