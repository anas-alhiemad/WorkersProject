<?php

namespace App\Models;
use App\Models\Worker;
use App\Models\PostPhotos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'worker_id',
        'price',
        'status',
        'rejec_reason'
    ];

    public function photos(){
        return $this->hasMany(PostPhotos::class,'postId');
    }

    public function worker(){
        return $this->belongsto(Worker::class,'worker_id');
    }

    // hasMany   belongsto
}
