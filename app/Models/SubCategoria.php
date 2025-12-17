<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;
    protected $table = 'subcategorias';
    protected $fillable = ['nombre', 'categoria_id', 'descripcion', 'cantidad'];

    // Relación inversa con Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // Relación con Artículos
    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'subcategoria_id');
    }
}