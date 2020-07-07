<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoCompra extends Model
{
    protected $table ='inv_doc_ic';
    protected $primaryKey = 'numero';
    protected $fillable =[
            'numero',
            'prefijo',
            'id_bodega',
            'id_usuario',
            'id_tipo_doc_bodega',
            'tipo_id_proveedor',
            'id_proveedor',
            'prefijo_factura',
            'nro_factura',
            'fecha_factura',
            'observaciones',
            'fecha_registro'
        ];
}
