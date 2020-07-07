<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table ='sedes';
    protected $primaryKey = 'id_sede';
    protected $fillable =[
            'id_sede',
            'descripcion',
            'id_centro_operacion',
            'estado'
        ];
}
