<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Tarea",
 *     type="object",
 *     required={"titulo", "fecha_vencimiento"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=2),
 *     @OA\Property(property="titulo", type="string", example="Estudiar Swagger"),
 *     @OA\Property(property="descripcion", type="string", example="Documentar correctamente una API"),
 *     @OA\Property(property="fecha_vencimiento", type="string", format="date", example="2025-08-15"),
 *     @OA\Property(property="estado", type="string", example="Pendiente"),
 *     @OA\Property(property="rick_morty_personaje_id", type="integer", example=12),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-14T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-14T12:05:00Z")
 * )
 */

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'fecha_vencimiento',
        'estado',
        'rick_morty_personaje_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
