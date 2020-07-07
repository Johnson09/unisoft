<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tercero extends Model
{
    protected $table ='terceros';
    protected $primaryKey = 'id_tercero';
    protected $fillable =[
            'tipo_id_tercero',
            'id_tercero',
            'id_naturaleza',
            'nombre',
            'ciudad_id',
            'departamento_id',
            'digito_v',
            'contacto',
            'email',
            'direccion',
            'telefono',
            'sw_tipo_tercero'
        ];
}
