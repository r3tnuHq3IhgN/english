<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoeImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'shoe_id',
        'image_url'
    ];

    public function shoe()
    {
        return $this->belongsTo(Shoe::class);
    }
}
