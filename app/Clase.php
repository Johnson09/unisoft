<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $table ='clases';
    protected $primaryKey = 'id_clase';
    protected $fillable =[
            'id_clase',
            'id_grupo',
            'descripcion',
            'estado'
        ];
}
