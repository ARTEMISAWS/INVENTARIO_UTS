<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;

     /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'ubicaciones';


    protected $fillable = ['nombre', 'descripcion'];

    public function articulos()
    {
        return $this->hasMany(Articulo::class);
    }
}