<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Existencia_Bodega_Lote extends Model
{
    protected $table ='existencias_bodegas_lt_fv';
    protected $primaryKey = 'id_producto';
    protected $fillable =[
            'id_bodega',
            'id_producto',
            'lote',
            'fecha_vto',
            'existencia'
        ];
}
