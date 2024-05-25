<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
        'name','image','current_price','weight','user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function vote()
    {
        return $this->hasMany(VoteProduct::class);
    }
    public function getImageAttribute($value)
    {
        return asset('storage/' . $value);
    }
    
    public function getVoteCountAttribute()
    {
        return $this->vote()->count();
    }
    
}
