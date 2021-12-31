<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsTag extends Model
{
    use HasFactory;
    protected $table = "news_tags";
    protected $fillable = [
        "news_id",
        "tag_id",
    ];

    public function news()
    {
        return $this->belongsTo(News::class, "news_id");
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, "tag_id");
    }
}
