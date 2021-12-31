<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;
    protected $table = "tags";
    protected $fillable = [
        "name",
    ];

    protected $appends = [
        "articles", 
    ];

    public function getArticlesAttribute()
    {
        return $this->articles()->get();
    }

    public function articles()
    {
        return $this->belongsToMany(News::class, "news_tags", "tag_id", "news_id");
    }


}
