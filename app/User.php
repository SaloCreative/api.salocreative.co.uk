<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'surname', 'company', 'phone', 'image'
    ];

    protected $casts = [
        'suspended' => 'boolean',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function scopeActive($query)
    {
        return $query->where('suspended', '=', false);
    }

    public function scopeInactive($query)
    {
        return $query->where('suspended', '=', true);
    }

    public function scopeByToken($query, $token)
    {
        return $query->where('password', '=', $token);
    }
}