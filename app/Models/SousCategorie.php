<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousCategorie extends Model
{
    use HasFactory;

    protected $fillable = ['sous_categorie'];

    public function video(){
        return $this->hasMany(Video::class);
    }

    public function categorie(){
        return $this->belongsTo(Categorie::class);
    }
}
