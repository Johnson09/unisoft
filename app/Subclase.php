<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subclase extends Model
{
    protected $table ='subclases';
    protected $primaryKey = 'id_subclase';
    protected $fillable =[
            'id_subclase',
            'id_clase',
            'descripcion',
            'estado'
        ];
}
