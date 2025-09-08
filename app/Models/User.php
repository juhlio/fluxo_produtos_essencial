<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Atributos preenchíveis.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atributos ocultos nas serializações.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /* =========================
     |  RELACIONAMENTOS
     |=========================*/

    /**
     * Papéis do usuário (pivot: role_user).
     */
    public function roles()
    {
        // Role está no mesmo namespace App\Models
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /* =========================
     |  HELPERS DE PAPÉIS
     |=========================*/

    /** Verifica se possui um papel específico. */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /** Verifica se possui algum dos papéis. */
    public function hasAnyRole(array|string $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /** Atalho para admin. */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
