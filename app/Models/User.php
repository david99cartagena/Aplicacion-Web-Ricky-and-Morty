<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Tarea;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="David Cartagena"),
 *     @OA\Property(property="role", type="string", example="admin"),
 *     @OA\Property(property="email", type="string", format="email", example="david@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example="2025-07-01T10:00:00Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-14T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-14T12:05:00Z")
 * )
 */

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // use HasFactory, Notifiable;
    // protected $fillable = ['name', 'email', 'password'];
    // protected $hidden = ['password'];

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
