<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla si no sigue la convención plural exacta (opcional, pero seguro)
    protected $table = 'dependencias';

    // Desactivar timestamps si tu tabla no tiene created_at y updated_at
    public $timestamps = false;

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'nombre',
        'tipo',
    ];
}
