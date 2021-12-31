<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $table = "news";
    protected $fillable = [
        "title",
        "slug",
        "content",
        "image_thumb",
        "status",
        "topic_id",
    ];

    protected $appends = [
        "topic_name", 
        "tags",
    ];

    public function getTopicNameAttribute()
    {
        return $this->topic->name;
    }

    public function getTagsAttribute()
    {
        return $this->tags()->get();
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, "topic_id");
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, "news_tags", "news_id", "tag_id");
    }
}
