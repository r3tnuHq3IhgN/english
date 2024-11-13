<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Word extends Model
{
    use HasFactory, Searchable;

    protected $table = 'word';
    protected $fillable = [
        'user_id',
        'eng',
        'type',
        'vi',
        'example',
        'date_created',
    ];
    public function scopeCheckWordExists($query, $uid, $eng, $type)
    {
        return $query->where('user_id', $uid)->where('eng', $eng)->where('type', $type)->count();
    }
    public function scopeDates($query, $uid)
    {
        return $query->where('user_id', $uid)->distinct('date_created')->pluck('date_created');
    }

    public function scopeSearchWord($query, $uid, $search)
    {
        return $query->where('user_id', $uid)->where('eng', 'like', '%' . $search . '%')->get();
    }

    public function scopeSearchFulltext($query, $uid, $search)
    {
        return $query->where('user_id', $uid)->whereRaw("MATCH(eng) AGAINST(? IN BOOLEAN MODE)", $search)->get();
    }
}
