<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Hil;
use App\Property;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $guarded = ['id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
    ];
    protected $hidden = [
        'password',
    ];

    public function hils(){
        return $this->belongsToMany(Hil::class);
    }
    public function properties(){
        return $this->belongsToMany(Property::class);
    }
}
