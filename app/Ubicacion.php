<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table ='ubicaciones';
    protected $primaryKey = 'id_ubicacion';
    protected $fillable =[
            'id_ubicacion',
            'descripcion',
            'tipo_ubicacion'
        ];
}
