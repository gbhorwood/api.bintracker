<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * App\Models\User
 *
 * @property Int $id
 * @property String $first_name
 * @property String $email
 * @property Mixed $role
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Determine if this user is an admin
     *
     * @return bool
     */
    public function isAdministrator():bool
    {
        return $this->role_id === 1;
    }

    /**
     * Retrive user's role
     */
    public function role():BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Serialization
     */
    public function jsonSerialize():Array
    {
        return [
            'id' => $this->id,
            'name' => $this->first_name,
            'email' => $this->email,
            'role' => $this->role,
        ];
    } // jsonSerialize
}
