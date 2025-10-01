<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamos';

    protected $fillable = [
        'articulo_id',
        'usuario_solicitante_id',
        'usuario_despacha_id',
        'usuario_recibe_id',
        'fecha_prestamo',
        'fecha_devolucion_estimada',
        'fecha_devolucion_real',
        'estado',
        'observaciones_prestamo',
        'observaciones_devolucion',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'fecha_prestamo' => 'datetime',
        'fecha_devolucion_estimada' => 'datetime',
        'fecha_devolucion_real' => 'datetime',
    ];

    // --- RELACIONES ---

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function usuario_solicitante()
    {
        return $this->belongsTo(User::class, 'usuario_solicitante_id');
    }

    public function usuario_despacha()
    {
        return $this->belongsTo(User::class, 'usuario_despacha_id');
    }
    
    public function usuario_recibe()
    {
        return $this->belongsTo(User::class, 'usuario_recibe_id');
    }
}