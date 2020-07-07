<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CentroOperacion extends Model
{
    protected $table ='centros_operacion';
    protected $primaryKey = 'id_centro_operacion';
    protected $fillable =[
            'id_centro_operacion',
            'id_empresa',
            'descripcion',
            'ciudad_id',
            'direccion',
            'telefono'
        ];
}
