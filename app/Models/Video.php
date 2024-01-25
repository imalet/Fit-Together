<?php

namespace App\Models;

use App\Models\SousCategorie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'path_video', 'duree'];

    public function sousCategorie()
    {
        return $this->belongsTo(SousCategorie::class);
    }

    public function user()
    {
        return $this->belongsTo(Video::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }
}
