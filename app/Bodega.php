<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $table ='bodegas';
    protected $primaryKey = 'id_bodega';
    protected $fillable =[
            'id_bodega',
            'id_centro_costo',
            'descripcion',
            'direccion',
            'sw_estado'
        ];
}
