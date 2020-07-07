<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CentroCosto extends Model
{
    protected $table ='centros_costo';
    protected $primaryKey = 'id_centro_costo';
    protected $fillable =[
            'id_centro_costo',
            'id_centro_operacion',
            'descripcion',
            'cuenta',
            'estado'
        ];
}
