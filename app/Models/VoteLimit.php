<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteLimit extends Model
{
    use HasFactory;
    protected $fillable=[
        'limit'
    ];
}
