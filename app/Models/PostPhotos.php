<?php

namespace App\Models;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostPhotos extends Model
{
    use HasFactory;
    protected $fillable = [
        'postId',
        'photo'
    ];



    public function worker(){
        return $this->belongsto(Post::class,'postId');
    }
}
