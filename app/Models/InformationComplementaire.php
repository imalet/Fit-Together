<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformationComplementaire extends Model
{
    use HasFactory;

    protected $fillable = ['bio'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
