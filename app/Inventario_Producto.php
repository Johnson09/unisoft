<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario_Producto extends Model
{
    protected $table ='inventarios_productos';
    protected $primaryKey = 'id_empresa';
    protected $fillable =[
            'id_empresa',
            'id_producto',
            'id_grupo',
            'id_clase',
            'id_subclase',
            'costo_anterior',
            'costo',
            'precio_venta',
            'iva',
            'sw_fv_lote'
        ];
}
