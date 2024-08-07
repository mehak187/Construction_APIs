<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteManagement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nickyClockinSystem_site_management';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Title',
        'Location',
        'Description',
        'Images',
        'timeAdded',
        'userId',
        'lat',
        'lng',
        'supervisor',
        'employees',
        'role',
        'projectCode'
    ];
}
