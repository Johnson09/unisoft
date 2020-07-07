<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bodega_Ubicacion extends Model
{
    protected $table ='bodegas_ubicaciones';
    protected $primaryKey = 'id_bodega';
    protected $fillable =[
            'id_bodega',
            'id_ubicacion',
            'id_ubicacion1',
            'id_ubicacion2',
            'id_ubicacion3'
        ];
}
