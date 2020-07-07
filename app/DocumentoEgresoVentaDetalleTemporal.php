<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoEgresoVentaDetalleTemporal extends Model
{
    protected $table ='inv_doc_epv_detalle_temp';
    protected $primaryKey = 'id';
    protected $fillable =[
            'id',
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
