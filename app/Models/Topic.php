<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    protected $table = "topic";
    protected $fillable = [
        "title",
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
        return $this->hasMany(Article::class, "topic_id");
    }
}
