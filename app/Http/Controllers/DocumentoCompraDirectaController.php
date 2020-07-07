<?php

namespace App\Http\Controllers;

use PDF;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

use App\Existencia_Bodega;
use App\DocumentoCompra;
use App\Existencia_Bodega_Lote;
use App\DocumentoCompraDetalle;
use App\DocumentoCompraTemporal;
use App\DocumentoCompraDetalleTemporal;

class DocumentoCompraDirectaController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{

            $bodega = DB::select("SELECT b.id_bodega, b.descripcion FROM bodegas_documentos bd JOIN bodegas b ON bd.id_bodega = b.id_bodega GROUP BY b.id_bodega, b.descripcion");
            $proveedor = DB::select("SELECT * FROM terceros_proveedores tp JOIN terceros t ON tp.id_tercero = t.id_tercero");
            $tipo = DB::select("SELECT * FROM terceros_proveedores tp JOIN terceros t ON tp.id_tercero = t.id_tercero JOIN tipos_id ti ON t.tipo_id_tercero = ti.id_tipo_id");
            $documento = DB::select("SELECT idi.numero, idi.created_at, idi.observaciones, SUM(idid.costo_compra) as total FROM inv_doc_ic_temp idi LEFT JOIN inv_doc_ic_detalle_temp idid ON idi.numero = idid.numero GROUP BY idi.numero, idi.created_at, idi.observaciones");

            $nombre = $_SESSION['nombre'];

            return view('documentos.compra', compact('nombre','bodega','tipo','proveedor','documento'));
        }

    }

    public function getDocumentosInventario(){
        $doc = Input::get('doc');
        $bod = Input::get('bod');
        $ini = Input::get('ini');
        $fin = Input::get('fin');

        $query = "";

        if (!empty($doc)) {
            $query .= "idi.numero = $doc AND ";
        }
        
        if (!empty($bod)) {
            $query .= "idi.id_bodega = $bod AND ";
        }
        
        if (!empty($ini)) {
            $query .= "idi.created_at > $ini AND ";
        }
        
        if (!empty($fin)) {
            $query .= "idi.created_at < $fin AND ";
        }

        $documento = DB::select("SELECT idi.numero, idi.created_at, idi.observaciones, SUM(idid.costo_compra) as total FROM inv_doc_ic idi JOIN inv_doc_ic_detalle idid ON idi.numero = idid.numero WHERE $query idi.numero IS NOT NULL GROUP BY idi.numero, idi.created_at, idi.observaciones");
        
        return response()->json($documento);
    }

    public function getProducto(){
        $id = Input::get('id');
        $bod = Input::get('bod');

        $id_producto = DB::select("SELECT p.id_producto, ip.costo, ip.sw_fv_lote, ebl.lote, ebl.fecha_vto FROM productos p LEFT JOIN existencias_bodegas eb ON p.id_producto = eb.id_producto LEFT JOIN existencias_bodegas_lt_fv ebl ON p.id_producto = ebl.id_producto LEFT JOIN inventarios_productos ip ON p.id_producto = ip.id_producto WHERE p.id_producto = '$id' AND eb.id_bodega = '$bod' OR p.id_producto = '$id' GROUP BY p.id_producto, ip.costo, ip.sw_fv_lote, ebl.lote, ebl.fecha_vto");

        if (empty($id_producto)) {
            $codigo_barra = DB::select("SELECT p.id_producto, ip.costo, ip.sw_fv_lote, ebl.lote, ebl.fecha_vto FROM productos p LEFT JOIN existencias_bodegas eb ON p.id_producto = eb.id_producto LEFT JOIN existencias_bodegas_lt_fv ebl ON p.id_producto = ebl.id_producto LEFT JOIN inventarios_productos ip ON p.id_producto = ip.id_producto WHERE p.cod_barras = '$id' AND eb.id_bodega = '$bod' OR p.id_producto = '$id' GROUP BY p.id_producto, ip.costo, ip.sw_fv_lote, ebl.lote, ebl.fecha_vto");

            if (empty($codigo_barra)) {
                $descripcion = DB::select("SELECT p.id_producto, ip.costo, ip.sw_fv_lote, ebl.lote, ebl.fecha_vto FROM productos p LEFT JOIN existencias_bodegas eb ON p.id_producto = eb.id_producto LEFT JOIN existencias_bodegas_lt_fv ebl ON p.id_producto = ebl.id_producto LEFT JOIN inventarios_productos ip ON p.id_producto = ip.id_producto WHERE p.descripcion = '$id' AND eb.id_bodega = '$bod' OR p.id_producto = '$id' GROUP BY p.id_producto, ip.costo, ip.sw_fv_lote, ebl.lote, ebl.fecha_vto");
    
                return response()->json($descripcion);
                
            }else {
                return response()->json($codigo_barra);
            }
        }else {
            return response()->json($id_producto);
        }
        
    }

    public function getProveedor0(){

        $numero = intval(Input::get('id'));

        $nombre = DB::select("SELECT nombre FROM terceros WHERE id_tercero = '$numero'");

        return response()->json($nombre);
        
    }

    public function getProveedor1(){

        $nombre = Input::get('nom');

        $proveedor = DB::select("SELECT id_tercero FROM terceros WHERE nombre = '$nombre'");

        return response()->json($proveedor);
        
    }

    public function getData(){

        $numero = intval(Input::get('id'));

        $headerDoc = DB::select("SELECT * FROM inv_doc_ic_temp tp JOIN terceros t ON tp.id_proveedor = t.id_tercero WHERE tp.numero = $numero");

        $detailsDoc = DB::select("SELECT eb.id, p.id_producto, p.descripcion as producto, eb.cantidad, eb.costo_actual, ip.precio_venta, ip.iva, eb.lote, eb.fecha_vto, eb.costo_compra, tdb.id_bodega FROM inv_doc_ic_detalle_temp eb JOIN productos p ON eb.id_producto = p.id_producto JOIN inventarios_productos ip ON p.id_producto = ip.id_producto JOIN bodegas_documentos tdb ON tdb.id_tipo_doc_bodega = eb.id_tipo_doc_bodega WHERE eb.numero = $numero");

        return response()->json([$headerDoc, $detailsDoc]);
        
    }

    public function createDoc()
    {
        session_start();

        $num = Input::get('num');

        $user = $_SESSION['id'];

        $bodega = Input::get('bod');
        $tipo = Input::get('tip');
        $proveedor = Input::get('pro');
        $prefijo = Input::get('pre');
        $factura = Input::get('fac');
        $fecha = Input::get('fec');
        $observacion = Input::get('obs');

        if (!empty($num)) {
  
            $docInv = DocumentoCompraTemporal::find($num);
            $docInv->prefijo = 'IC';
            $docInv->id_bodega = $bodega;
            $docInv->id_usuario = $user;
            $docInv->id_tipo_doc_bodega = 2;
            $docInv->tipo_id_proveedor = $tipo;
            $docInv->id_proveedor = $proveedor;
            $docInv->prefijo_factura = $prefijo;
            $docInv->nro_factura = $factura;
            $docInv->fecha_factura = $fecha;
            $docInv->observaciones = $observacion;
            $docInv->save();
      
            return response()->json($num);
        }else{
            $dat = DB::select("SELECT CASE WHEN numero IS NULL THEN 1 ELSE MAX(numero) + 1 END as numero FROM inv_doc_ic GROUP BY numero ORDER BY created_at DESC LIMIT 1");

            $numero = 0;
            if (empty($dat)) {
                $numero = 1;
            }else{
                $num_check = $dat[0]->numero;

                $check = DB::select("SELECT numero FROM inv_doc_ic_temp WHERE numero = $num_check");

                if (empty($check)) {
                    $numero = $dat[0]->numero;
                }else{
                    $temp = DB::select("SELECT CASE WHEN numero IS NULL THEN 1 ELSE MAX(numero) + 1 END as numero FROM inv_doc_ic_temp GROUP BY numero ORDER BY created_at DESC LIMIT 1");

                    if (empty($temp)) {
                        $numero = 1;
                    }else{
                        $numero = $temp[0]->numero;
                    }
                }
            }
            // dd($numero);
      
            $docInv = new DocumentoCompraTemporal();
            $docInv->numero = $numero;
            $docInv->prefijo = 'IC';
            $docInv->id_bodega = $bodega;
            $docInv->id_usuario = $user;
            $docInv->id_tipo_doc_bodega = 2;
            $docInv->tipo_id_proveedor = $tipo;
            $docInv->id_proveedor = $proveedor;
            $docInv->prefijo_factura = $prefijo;
            $docInv->nro_factura = $factura;
            $docInv->fecha_factura = $fecha;
            $docInv->observaciones = $observacion;
            $docInv->save();
      
            return response()->json($numero);
        }
    }

    public function searchDetail()
    {
  
        $numero = Input::get('id');
        $producto = Input::get('pro');
        $lote = Input::get('lote');

        if (empty($lote)) {

            $bodDet = DB::select("SELECT * FROM inv_doc_ic_detalle_temp WHERE id_producto = '$producto' AND numero = $numero AND lote IS NULL");

        }else{

            $bodDet = DB::select("SELECT * FROM inv_doc_ic_detalle_temp WHERE id_producto = '$producto' AND numero = $numero AND lote = '$lote'");
            
        }
        
        return response()->json($bodDet);
    }

    public function updateDetail()
    {
  
        $registro = Input::get('id');
        $cantidad = Input::get('can');
        $lote = Input::get('lot');

        if (empty($lote)) {

            $bodDet = DB::select("SELECT cantidad, costo_actual FROM inv_doc_ic_detalle_temp WHERE id = '$registro'");
            $new_cantidad = $cantidad + $bodDet[0]->cantidad;
            $total = $new_cantidad * $bodDet[0]->costo_actual;

            $docInvDet = DocumentoCompraDetalleTemporal::find($registro);
            $docInvDet->cantidad = $new_cantidad;
            $docInvDet->costo_compra = $total;
            $docInvDet->save();

        }else{

            $bodDet = DB::select("SELECT cantidad, costo_actual FROM inv_doc_ic_detalle_temp WHERE id = '$registro'");
            $new_cantidad = $cantidad + $bodDet[0]->cantidad;
            $total = $new_cantidad * $bodDet[0]->costo_actual;

            $docInvDet = DocumentoCompraDetalleTemporal::find($registro);
            $docInvDet->cantidad = $new_cantidad;
            $docInvDet->costo_compra = $total;
            $docInvDet->save();
            
        }
            
        $detalle = DB::select("SELECT eb.id, p.id_producto, p.descripcion as producto, eb.cantidad, eb.costo_actual, ip.precio_venta, ip.iva, eb.lote, eb.fecha_vto, eb.costo_compra FROM inv_doc_ic_detalle_temp eb JOIN productos p ON eb.id_producto = p.id_producto JOIN inventarios_productos ip ON p.id_producto = ip.id_producto WHERE eb.id = $registro");
        
        return response()->json($detalle);
    }

    public function saveDetail()
    {
  
        $cantidad = Input::get('can');
        $costo = Input::get('cos');
        $total = $cantidad * $costo;

        $bodega = Input::get('bod');

        $bode = DB::select("SELECT id_tipo_doc_bodega AS tipo FROM bodegas_documentos WHERE id_bodega = $bodega");

        $numero = Input::get('id');

        $docInvDet = new DocumentoCompraDetalleTemporal();
        $docInvDet->numero = $numero;
        $docInvDet->id_tipo_doc_bodega = 2;
        $docInvDet->id_producto = Input::get('pro');
        $docInvDet->cantidad = Input::get('can');
        $docInvDet->costo_actual = Input::get('cos');
        $docInvDet->costo_compra = $total;
        $docInvDet->lote = Input::get('lot');
        $docInvDet->fecha_vto = Input::get('vto');
        $docInvDet->save();

        $detalle = DB::select("SELECT eb.id, p.id_producto, p.descripcion as producto, eb.cantidad, eb.costo_actual, ip.precio_venta, ip.iva, eb.lote, eb.fecha_vto, eb.costo_compra FROM inv_doc_ic_detalle_temp eb JOIN productos p ON eb.id_producto = p.id_producto JOIN inventarios_productos ip ON p.id_producto = ip.id_producto WHERE eb.numero = $numero ORDER BY eb.created_at DESC LIMIT 1");
        
        return response()->json($detalle);
    }

    public function deleteDetail()
    {
  
        $id = Input::get('id');

        $doc = DB::delete("DELETE FROM inv_doc_ic_detalle_temp WHERE id = $id");
    }

    public function store(Request $request)
    {

        $request->validate([
            'id_bodega' => 'required',
            'observaciones' => 'required',
            'numero' => 'required',
        ]);

        $numero = $request->get('numero');
        $bodega = $request->get('id_bodega');

        $bode = DB::select("SELECT id_tipo_doc_bodega AS tipo FROM bodegas_documentos WHERE id_bodega = $bodega");
  
        // $docInvTempo = DocumentoCompraTemporal::find($numero);
        // $docInvTempo->id_bodega = $request->get('id_bodega');
        // $docInvTempo->id_tipo_doc_bodega = 2;
        // $docInvTempo->observaciones = $request->get('observaciones');
        // $docInvTempo->tipo_id_proveedor = $request->get('tipo_id_proveedor');
        // $docInvTempo->id_proveedor = $request->get('id_proveedor');
        // $docInvTempo->prefijo_factura = $request->get('prefijo_factura');
        // $docInvTempo->nro_factura = $request->get('nro_factura');
        // $docInvTempo->fecha_factura = $request->get('fecha_factura');
        // $docInvTempo->save();

        $docInvTemp = DB::select("SELECT * FROM inv_doc_ic_temp WHERE numero = $numero");

        $docInvDetTemp = DB::select("SELECT * FROM inv_doc_ic_detalle_temp WHERE numero = $numero");

        // dd($docInvDetTemp);

        $docInv = new DocumentoCompra();
        $docInv->prefijo = $docInvTemp[0]->prefijo;
        $docInv->numero =   $docInvTemp[0]->numero;
        $docInv->id_usuario = $docInvTemp[0]->id_usuario;
        $docInv->id_bodega = $docInvTemp[0]->id_bodega;
        $docInv->id_tipo_doc_bodega = $docInvTemp[0]->id_tipo_doc_bodega;
        $docInv->observaciones = $docInvTemp[0]->observaciones;
        $docInv->tipo_id_proveedor = $docInvTemp[0]->tipo_id_proveedor;
        $docInv->id_proveedor = $docInvTemp[0]->id_proveedor;
        $docInv->prefijo_factura = $docInvTemp[0]->prefijo_factura;
        $docInv->nro_factura = $docInvTemp[0]->nro_factura;
        $docInv->fecha_factura = $docInvTemp[0]->fecha_factura;
        $docInv->save();

        foreach ($docInvDetTemp as $key => $value) {
            $docInvDet = new DocumentoCompraDetalle();
            $docInvDet->numero = $value->numero;
            $docInvDet->id_tipo_doc_bodega = $value->id_tipo_doc_bodega;
            $docInvDet->id_producto = $value->id_producto;
            $docInvDet->cantidad = $value->cantidad;
            $docInvDet->costo_actual = $value->costo_actual;
            $docInvDet->costo_compra = $value->costo_compra;
            $docInvDet->lote = $value->lote;
            $docInvDet->fecha_vto = $value->fecha_vto;
            $docInvDet->save();

            $invPro = DB::update("UPDATE inventarios_productos SET costo = $value->costo_compra, costo_anterior = $value->costo_actual WHERE id_producto = '$value->id_producto'");
        }

        $docInvDetPro = DB::select("SELECT id_producto, cantidad FROM inv_doc_ic_detalle_temp WHERE numero = $numero");

        if (count($docInvDetPro) != 0) {
            foreach ($docInvDetPro as $key => $val) {

                $codPro = $val->id_producto;

                $exisBode = DB::select("SELECT * FROM existencias_bodegas WHERE id_producto = '$codPro' AND id_bodega = $bodega");

                if (count($exisBode) != 0) {
                    $actual = $exisBode[0]->existencia_actual;
                    $canti = $exisBode[0]->existencia_actual + $val->cantidad;
                    $existencia = DB::update("UPDATE existencias_bodegas SET existencia_inicial = $actual, existencia_actual = $canti WHERE id_producto = '$codPro' AND id_bodega = $bodega");

                }else{
                    $existencia = new Existencia_Bodega();
                    $existencia->id_bodega = $request->get('id_bodega');
                    $existencia->id_producto = $val->id_producto;
                    $existencia->existencia_inicial = 0;
                    $existencia->existencia_actual = 0;
                    $existencia->existencia_minima = 1;
                    $existencia->existencia_maxima = $val->cantidad;
                    $existencia->save();
                }
            }
        }

        $docInvDetPros = DB::select("SELECT * FROM inv_doc_ic_detalle_temp WHERE numero = $numero AND lote IS NOT NULL");

        if (count($docInvDetPros) != 0) {
            foreach ($docInvDetPros as $key => $val) {
                $existencialote = new Existencia_Bodega_Lote();
                $existencialote->id_bodega = $bodega;
                $existencialote->id_producto = $val->id_producto;
                $existencialote->lote = $val->lote;
                $existencialote->fecha_vto = $val->fecha_vto;
                $existencialote->existencia = $val->cantidad;
                $existencialote->save();
            }
        }

        $InvDetDel = DB::delete("DELETE FROM inv_doc_ic_detalle_temp WHERE numero = $numero");

        $InvDel = DB::delete("DELETE FROM inv_doc_ic_temp WHERE numero = $numero");
  
        return redirect('regdocicd')->with('success','Documento Ingreso Ajuste Inventario Ingresado Satisfactoriamente.');
    }

    public function show($numero)
    {
        ini_set('max_execution_time', 300);

        $docInv = DB::select("SELECT *, b.descripcion as bodega, u.nombre as usuario, i.created_at FROM inv_doc_ic i JOIN bodegas b ON i.id_bodega = b.id_bodega JOIN system_usuarios u ON i.id_usuario = u.usuario_id JOIN tipos_doc_bodega tb ON i.id_tipo_doc_bodega = tb.id_tipo_doc_bodega WHERE i.numero = $numero");

        $docInvDet = DB::select("SELECT *, u.descripcion as producto, i.costo_actual as costo_und, i.costo_compra as costo_total FROM inv_doc_ic_detalle i JOIN inv_doc_ic b ON i.numero = b.numero JOIN productos u ON i.id_producto = u.id_producto JOIN tipos_doc_bodega tb ON i.id_tipo_doc_bodega = tb.id_tipo_doc_bodega WHERE i.numero = $numero");

        $docInvTot = DB::select("SELECT SUM(costo_compra) AS total FROM inv_doc_ic_detalle WHERE numero = $numero");

        $total = $docInvTot[0]->total;
        
        return view('formatos_documentos.doc', compact('docInv','docInvDet','total'));
    }

    public function loadNewPDF($numero){
        // Generador de pdf
        ini_set('max_execution_time', 300);

        $docInv = DB::select("SELECT *, b.descripcion as bodega, u.nombre as usuario, i.created_at FROM inv_doc_ic i JOIN bodegas b ON i.id_bodega = b.id_bodega JOIN system_usuarios u ON i.id_usuario = u.usuario_id JOIN tipos_doc_bodega tb ON i.id_tipo_doc_bodega = tb.id_tipo_doc_bodega WHERE i.numero = $numero");

        $docInvDet = DB::select("SELECT *, u.descripcion as producto, i.costo_actual as costo_und, i.costo_compra as costo_total FROM inv_doc_ic_detalle i JOIN inv_doc_ic b ON i.numero = b.numero JOIN productos u ON i.id_producto = u.id_producto JOIN tipos_doc_bodega tb ON i.id_tipo_doc_bodega = tb.id_tipo_doc_bodega WHERE i.numero = $numero");

        $docInvTot = DB::select("SELECT SUM(costo_compra) AS total FROM inv_doc_ic_detalle WHERE numero = $numero");

        $total = $docInvTot[0]->total;

        $pdf = \PDF::loadView('formatos_documentos.pdf', compact('docInv','docInvDet','total'));
        //dd($pdf); 

        // $pdf->setPaper('A4', 'landscape'); // Formato de la hoja
        // return View('reporte.pdf', compact('encabezado','total','detalles')); // Ilustracion o vista del pdf
        $text = 'Documento_'.$numero.'_'.$docInv[0]->created_at.'.pdf';
        // Se genera o se returna el pdf de la cotizacion
        return $pdf->stream($text);
    }

    public function destroy($numero)
    {

        $detail = DB::delete("DELETE FROM inv_doc_ic_detalle_temp WHERE numero = $numero");

        $doc = DB::delete("DELETE FROM inv_doc_ic_temp WHERE numero = $numero");

        return redirect('regdocicd');

    }
}
