<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ADMIN = 1;
    const USER= 0;
    const APPROVED_STATUS = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'status',
        'role',
        'profile',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    public function getProfileAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
        return $value;
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query->whereRaw("CONCAT_WS(' ', first_name, last_name, email) LIKE ?", ['%' . $term . '%']);
        });
    }

     // JWT methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function scopeAdmin($query)
    {
        return $query->where('is_admin',self::ADMIN);
    }

    public function scopeUser($query)
    {
        return $query->where('is_admin',self::USER);
    }

    public function scopeApproved($query)
    {
        return $query->where('status',self::APPROVED_STATUS);
    }

    public function scopeWhereEmail($query, $email)
    {
        return $query->where('email', $email);
    }
}
