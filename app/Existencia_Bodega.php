<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Existencia_Bodega extends Model
{
    protected $table ='existencias_bodegas';
    protected $primaryKey = 'id_producto';
    protected $fillable =[
            'id_bodega',
            'id_producto',
            'existencia_inicial',
            'existencia_actual',
            'existencia_minima',
            'existencia_maxima'
        ];
}
