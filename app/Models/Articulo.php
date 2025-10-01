<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'articulos';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * Esta es una medida de seguridad para proteger tu base de datos.
     * Solo los campos listados aquí podrán ser guardados usando métodos como create() o update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'codigo_uts',
        'descripcion',
        'marca',
        'modelo',
        'numero_serie',
        'estado',
        'calcomania',
        'categoria_id',
        'ubicacion_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones con otros Modelos
    |--------------------------------------------------------------------------
    |
    | Estas funciones definen cómo se conecta un Artículo con otras partes
    | de tu aplicación. Esto te permite hacer consultas muy poderosas y limpias.
    |
    */

    /**
     * Define la relación "pertenece a": Un artículo pertenece a una Categoría.
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Define la relación "pertenece a": Un artículo pertenece a una Ubicación.
     */
    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class);
    }

    /**
     * Define la relación "tiene muchos": Un artículo puede tener muchos Préstamos.
     */
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    /**
     * Define la relación "tiene muchos": Un artículo puede tener muchos Reportes de Mantenimiento.
     */
    public function reportes()
    {
        return $this->hasMany(ReporteMantenimiento::class);
    }
}