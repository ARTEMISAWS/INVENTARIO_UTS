<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteMantenimiento extends Model
{
    use HasFactory;

    protected $table = 'reportes_mantenimiento';

    protected $fillable = [
        'articulo_id',
        'usuario_reporta_id',
        'tipo',
        'descripcion_problema',
        'descripcion_solucion',
        'fecha_reporte',
        'fecha_solucion',
        'estado',
    ];

    protected $casts = [
        'fecha_reporte' => 'datetime',
        'fecha_solucion' => 'datetime',
    ];

    // --- RELACIONES ---

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function usuario_reporta()
    {
        return $this->belongsTo(User::class, 'usuario_reporta_id');
    }
}