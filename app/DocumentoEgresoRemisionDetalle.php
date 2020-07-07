<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoEgresoRemisionDetalle extends Model
{
    protected $table ='inv_doc_epr_detalle';
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
