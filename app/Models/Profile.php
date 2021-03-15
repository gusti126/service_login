<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Profile extends Model
{
    protected $table = 'profile';

    protected $fillable = [
        'image','user_id', 'jenis_kelamin', 'tanggal_lahir', 'no_tlp', 'alamat','referal', 'point'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
