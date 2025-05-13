<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;
    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_super_admin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_super_admin' => 'boolean',
    ];
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Get the formatted role name
     *
     * @return string
     */
    public function getFormattedRoleAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->role));
    }
}
