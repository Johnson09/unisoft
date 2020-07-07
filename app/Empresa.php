<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table ='empresas';
    protected $primaryKey = 'id_empresa';
    protected $fillable =[
            'id_empresa',
            'tipo_id',
            'numero_id',
            'representante_legal',
            'ciudad_id',
            'direccion',
            'telefono',
            'email',
            'estado'
        ];
}
