<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', // Laravel Breeze usa 'name', asegúrate de que coincida con tu migración
        'cedula',
        'email',
        'password',
        'role', // ✅ Añadimos el campo 'role' aquí
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones con otros Modelos
    |--------------------------------------------------------------------------
    */

    /**
     * Un usuario (como solicitante) puede tener muchos Préstamos.
     */
    public function prestamos_solicitados()
    {
        return $this->hasMany(Prestamo::class, 'usuario_solicitante_id');
    }

    /**
     * Un usuario (como admin) puede despachar muchos Préstamos.
     */
    public function prestamos_despachados()
    {
        return $this->hasMany(Prestamo::class, 'usuario_despacha_id');
    }

    /**
     * Un usuario (como admin) puede recibir la devolución de muchos Préstamos.
     */
    public function prestamos_recibidos()
    {
        return $this->hasMany(Prestamo::class, 'usuario_recibe_id');
    }

    /**
     * Un usuario puede reportar muchos daños o mantenimientos.
     */
    public function reportes_realizados()
    {
        return $this->hasMany(ReporteMantenimiento::class, 'usuario_reporta_id');
    }
}