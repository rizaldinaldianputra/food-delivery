<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'gender',
        'role_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'role_id' => 'integer',
    ];


    // ✅ Relasi ke Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isCustomer()
    {
        return $this->role?->name === 'customer';
    }

    public function isMerchant()
    {
        return $this->role?->name === 'merchant';
    }

    // ✅ Implementasi metode untuk JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Biasanya ID user
    }

    public function getJWTCustomClaims()
    {
        return []; // Bisa tambahkan custom claims jika diperlukan
    }
}