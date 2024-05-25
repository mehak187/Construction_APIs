<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nickyclockinsystem_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'title',
        'role',
        'phone',
        'usernumber',
        'timeAdded',
        'businessName',
        'mobilePhone',
        'homePhone',
        'address',
        'city',
        'state',
        'zip',
        'website',
        'location',
        'post',
        'description',
        'addedBy',
        'userId',
        'current_pipeline_stage',
        'profile_pic',
        'isSubscribed',
        'hourly_rate',
        'overtime_rate',
        'salary',
        'e_wallet_balance',
        'monthlysalary',
        'otime',
        'allow',
        'date',
        'loginAuth',
        'work_shift_status',
        'lunch_status',
        'break_status',
        'site',
        'working_hours',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function getImageAttribute($value)
    {   
        if ($value == null) {
            return asset('myimgs/avatar.png');
        } else {
            return asset('myimgs/' . $value);
        }
    }

    public function referredMerchantsCount()
    {
        return $this->merchants()->count();
    }

    public function merchants()
    {
        return $this->hasMany(Merchant::class);
    }
}
