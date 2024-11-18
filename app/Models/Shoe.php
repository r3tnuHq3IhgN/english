<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shoe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'size',
        'price',
        'description',
        'category',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function images()
    {
        return $this->hasMany(ShoeImage::class, 'shoe_id', 'id');
    }

    public function scopeGetAllShoesOfUser($query, $uid)
    {
        return $query->where('user_id', $uid)->with('images')->get();
    }
    public function scopeFindShoeOfUserWithId($query, $uid, $shoe_id)
    {
        return $query->where('user_id', $uid)->where('id', $shoe_id);
    }
}
