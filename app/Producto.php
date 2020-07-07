<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table ='productos';
    protected $primaryKey = 'id_producto';
    protected $fillable =[
            'id_producto',
            'cod_barras',
            'id_grupo',
            'id_clase',
            'id_subclase',
            'descripcion',
            'id_und_med',
            'id_proveedor',
            'id_marca',
            'sw_iva',
            'sw_estado',
            'und_venta',
            'cant_und_venta'
        ];
}
