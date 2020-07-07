<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoCompraDetalle extends Model
{
    protected $table ='inv_doc_ic_detalle';
    protected $primaryKey = 'numero';
    protected $fillable =[
            'numero',
            'id_tipo_doc_bodega',
            'id_producto',
            'cantidad',
            'costo_actual',
            'costo_compra',
            'lote',
            'fecha_vto'
        ];
}
