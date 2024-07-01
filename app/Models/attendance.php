<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attendance extends Model
{
    use HasFactory;
       /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'location',
        'photo',
        'checkin',
        'checkout',
        'status',
        'uid',
        'project_name',
        'checkinPhoto',
        'checkoutPhoto',
        'overtime',
        'superviserid'
    ];
}
