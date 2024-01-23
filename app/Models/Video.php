<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['titre','path_video', 'duree'];

    public function categorie(){
        return $this->belongsTo(Categorie::class);
    }

    public function user(){
        return $this->belongsTo(Video::class);
    }
}
