<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoInventarioDetalle extends Model
{
    protected $table ='inv_doc_iai_detalle';
    protected $primaryKey = 'numero';
    protected $fillable =[
            'numero',
            'id_tipo_doc_bodega',
            'id_producto',
            'cantidad',
            'costo_und',
            'costo_total',
            'lote',
            'fecha_vto'
        ];
}
