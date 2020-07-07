<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoEgresoInventarioTemporal extends Model
{
    protected $table ='inv_doc_eai_temp';
    protected $primaryKey = 'numero';
    protected $fillable =[
            'numero',
            'prefijo',
            'id_bodega',
            'id_usuario',
            'id_tipo_doc_bodega',
            'observaciones',
            'fecha_registro'
        ];
}
