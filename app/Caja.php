<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table ='cajas';
    protected $primaryKey = 'id_caja';
    protected $fillable =[
            'id_caja',
            'descripcion',
            'id_sede',
            'id_centro_costo',
            'tipo_venta',
            'estado'
        ];
}
