<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupos extends Model
{
    protected $table ='grupos';
    protected $primaryKey = 'id_grupo';
    protected $fillable =[
            'id_grupo',
            'descripcion',
            'estado'
        ];
}
