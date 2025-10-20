<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdImage extends Model
{
    protected $fillable = ['path'];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}

