<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoCompraDetalleTemporal extends Model
{
    protected $table ='inv_doc_ic_detalle_temp';
    protected $primaryKey = 'id';
    protected $fillable =[
            'id',
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
