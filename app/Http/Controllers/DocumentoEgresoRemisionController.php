<?php

namespace App\Http\Controllers;

use PDF;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

use App\Existencia_Bodega;
use App\DocumentoEgresoRemision;
use App\Existencia_Bodega_Lote;
use App\DocumentoEgresoRemisionDetalle;
use App\DocumentoEgresoRemisionTemporal;
use App\DocumentoEgresoRemisionDetalleTemporal;

class DocumentoEgresoRemisionController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{

            $bodega = DB::select("SELECT b.id_bodega, b.descripcion FROM bodegas_documentos bd JOIN bodegas b ON bd.id_bodega = b.id_bodega GROUP BY b.id_bodega, b.descripcion");
            $documento = DB::select("SELECT idi.numero, idi.created_at, idi.observaciones, SUM(idid.costo_total) as total FROM inv_doc_epr_temp idi JOIN inv_doc_epr_detalle_temp idid ON idi.numero = idid.numero GROUP BY idi.numero, idi.created_at, idi.observaciones");

            $nombre = $_SESSION['nombre'];

            return view('documentos.egreso_remision', compact('nombre','bodega','documento'));
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

        $documento = DB::select("SELECT idi.numero, idi.created_at, idi.observaciones, SUM(idid.costo_total) as total FROM inv_doc_epr idi JOIN inv_doc_epr_detalle idid ON idi.numero = idid.numero WHERE $query idi.numero IS NOT NULL GROUP BY idi.numero, idi.created_at, idi.observaciones");
        
        return response()->json($documento);
    }

    public function getProducto(){
        $id = Input::get('id');
        $bod = Input::get('bod');

        $id_producto = DB::select("SELECT p.id_producto, ip.costo, ip.sw_fv_lote, eb.existencia_actual FROM productos p LEFT JOIN existencias_bodegas eb ON eb.id_producto = p.id_producto LEFT JOIN existencias_bodegas_lt_fv ebl ON ebl.id_producto = p.id_producto JOIN inventarios_productos ip ON p.id_producto = ip.id_producto WHERE eb.id_producto = '$id' AND eb.id_bodega = '$bod' OR p.id_producto = '$id' GROUP BY p.id_producto, ip.costo, ip.sw_fv_lote, eb.existencia_actual");

        if (empty($id_producto)) {
            $codigo_barra = DB::select("SELECT p.id_producto, ip.costo, ip.sw_fv_lote, eb.existencia_actual FROM productos p LEFT JOIN existencias_bodegas eb ON eb.id_producto = p.id_producto LEFT JOIN existencias_bodegas_lt_fv ebl ON ebl.id_producto = p.id_producto JOIN inventarios_productos ip ON p.id_producto = ip.id_producto WHERE p.cod_barras = '$id' AND eb.id_bodega = '$bod' OR p.id_producto = '$id' GROUP BY p.id_producto, ip.costo, ip.sw_fv_lote, eb.existencia_actual");

            if (empty($codigo_barra)) {
                $descripcion = DB::select("SELECT p.id_producto, ip.costo, ip.sw_fv_lote, eb.existencia_actual FROM productos p LEFT JOIN existencias_bodegas eb ON eb.id_producto = p.id_producto LEFT JOIN existencias_bodegas_lt_fv ebl ON ebl.id_producto = p.id_producto JOIN inventarios_productos ip ON p.id_producto = ip.id_producto WHERE p.descripcion = '$id' AND eb.id_bodega = '$bod' OR p.id_producto = '$id' GROUP BY p.id_producto, ip.costo, ip.sw_fv_lote, eb.existencia_actual");
    
                return response()->json($descripcion);
                
            }else {
                return response()->json($codigo_barra);
            }
        }else {
            return response()->json($id_producto);
        }
        
    }

    public function getData(){

        $numero = intval(Input::get('id'));

        $detailsDoc = DB::select("SELECT eb.id, p.id_producto, p.descripcion as producto, eb.cantidad, eb.costo_und, ip.precio_venta, ip.iva, eb.lote, eb.fecha_vto, eb.costo_total, tdb.id_bodega FROM inv_doc_epr_detalle_temp eb JOIN productos p ON eb.id_producto = p.id_producto JOIN inventarios_productos ip ON p.id_producto = ip.id_producto JOIN bodegas_documentos tdb ON tdb.id_tipo_doc_bodega = eb.id_tipo_doc_bodega WHERE eb.numero = $numero");

        return response()->json($detailsDoc);
        
    }

    public function createDoc()
    {
        session_start();

        $user = $_SESSION['id'];

        $notemp = DB::select("SELECT CASE WHEN numero IS NULL THEN 1 ELSE MAX(numero) + 1 END as numero FROM inv_doc_epr GROUP BY numero ORDER BY created_at DESC LIMIT 1");

        if (!empty($notemp)) {
            $num_check = $notemp[0]->numero;
        }else {
            $num_check = 1;
        }

        $check = DB::select("SELECT numero FROM inv_doc_epr_temp WHERE numero = $num_check");

        if (empty($check)) {

            // dd('insert 1', $num_check);

            $docInv = new DocumentoEgresoRemisionTemporal();
            $docInv->numero = $num_check;
            $docInv->prefijo = 'EPR';
            $docInv->id_usuario = $user;
            $docInv->save();
                
            return response()->json($num_check);
  
        }else{

            $numero = $check[0]->numero;

            $check_detail = DB::select("SELECT numero FROM inv_doc_epr_detalle_temp WHERE numero = $numero");

            if (empty($check_detail)) {

                // dd('update 1', $numero);

                $docInv = DocumentoEgresoRemisionTemporal::find($numero);
                $docInv->prefijo = 'EPR';
                $docInv->id_usuario = $user;
                $docInv->save();

                return response()->json($numero);

            }else{

                $check_temp = DB::select("SELECT MAX(numero) AS numero FROM inv_doc_epr_temp GROUP BY numero ORDER BY created_at DESC LIMIT 1");

                $num_temp_check = $check_temp[0]->numero;

                $check_num = DB::select("SELECT numero FROM inv_doc_epr_detalle_temp WHERE numero = $num_temp_check");

                if (empty($check_num)) {

                    // dd('update 2', $num_temp_check);
                    
                    $docInv = DocumentoEgresoRemisionTemporal::find($num_temp_check);
                    $docInv->prefijo = 'EPR';
                    $docInv->id_usuario = $user;
                    $docInv->save();

                    return response()->json($num_temp_check);
          
                }else{

                    $temp = DB::select("SELECT CASE WHEN numero IS NULL THEN 1 ELSE MAX(numero) + 1 END as numero FROM inv_doc_epr_temp GROUP BY numero ORDER BY created_at DESC LIMIT 1");

                    $num_temp = $temp[0]->numero;

                    // dd('insert 2', $num_temp);

                    $docInv = new DocumentoEgresoRemisionTemporal();
                    $docInv->numero = $numero;
                    $docInv->prefijo = 'EPR';
                    $docInv->id_usuario = $user;
                    $docInv->save();

                    return response()->json($num_temp);

                }

            }
        }

    }

    public function searchDetail()
    {
  
        $numero = Input::get('id');
        $producto = Input::get('pro');
        $lote = Input::get('lote');

        if (empty($lote)) {

            $bodDet = DB::select("SELECT * FROM inv_doc_epr_detalle_temp WHERE id_producto = '$producto' AND numero = $numero AND lote IS NULL");

        }else{

            $bodDet = DB::select("SELECT * FROM inv_doc_epr_detalle_temp WHERE id_producto = '$producto' AND numero = $numero AND lote = '$lote'");
            
        }
        
        return response()->json($bodDet);
    }

    public function updateDetail()
    {
  
        $registro = Input::get('id');
        $cantidad = Input::get('can');
        $lote = Input::get('lot');

        if (empty($lote)) {

            $bodDet = DB::select("SELECT cantidad, costo_und FROM inv_doc_epr_detalle_temp WHERE id = '$registro'");
            $new_cantidad = $cantidad + $bodDet[0]->cantidad;
            $total = $new_cantidad * $bodDet[0]->costo_und;

            $docInvDet = DocumentoEgresoRemisionDetalleTemporal::find($registro);
            $docInvDet->cantidad = $new_cantidad;
            $docInvDet->costo_total = $total;
            $docInvDet->save();

        }else{

            $bodDet = DB::select("SELECT cantidad, costo_und FROM inv_doc_epr_detalle_temp WHERE id = '$registro'");
            $new_cantidad = $cantidad + $bodDet[0]->cantidad;
            $total = $new_cantidad * $bodDet[0]->costo_und;

            $docInvDet = DocumentoEgresoRemisionDetalleTemporal::find($registro);
            $docInvDet->cantidad = $new_cantidad;
            $docInvDet->costo_total = $total;
            $docInvDet->save();
            
        }
            
        $detalle = DB::select("SELECT eb.id, p.id_producto, p.descripcion as producto, eb.cantidad, eb.costo_und, ip.precio_venta, ip.iva, eb.lote, eb.fecha_vto, eb.costo_total FROM inv_doc_epr_detalle_temp eb JOIN productos p ON eb.id_producto = p.id_producto JOIN inventarios_productos ip ON p.id_producto = ip.id_producto WHERE eb.id = $registro");
        
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

        $docInvDet = new DocumentoEgresoRemisionDetalleTemporal();
        $docInvDet->numero = $numero;
        $docInvDet->id_tipo_doc_bodega = 1;
        $docInvDet->id_producto = Input::get('pro');
        $docInvDet->cantidad = Input::get('can');
        $docInvDet->costo_und = Input::get('cos');
        $docInvDet->costo_total = $total;
        $docInvDet->lote = Input::get('lot');
        $docInvDet->fecha_vto = Input::get('vto');
        $docInvDet->save();

        $detalle = DB::select("SELECT eb.id, p.id_producto, p.descripcion as producto, eb.cantidad, eb.costo_und, ip.precio_venta, ip.iva, eb.lote, eb.fecha_vto, eb.costo_total FROM inv_doc_epr_detalle_temp eb JOIN productos p ON eb.id_producto = p.id_producto JOIN inventarios_productos ip ON p.id_producto = ip.id_producto WHERE eb.numero = $numero ORDER BY eb.created_at DESC LIMIT 1");
        
        return response()->json($detalle);
    }

    public function deleteDetail()
    {
  
        $id = Input::get('id');

        $doc = DB::delete("DELETE FROM inv_doc_epr_detalle_temp WHERE id = $id");
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
  
        $docInvTempo = DocumentoEgresoRemisionTemporal::find($numero);
        $docInvTempo->id_bodega = $request->get('id_bodega');
        $docInvTempo->id_tipo_doc_bodega = 1;
        $docInvTempo->observaciones = $request->get('observaciones');
        $docInvTempo->save();

        $docInvTemp = DB::select("SELECT * FROM inv_doc_epr_temp WHERE numero = $numero");

        $docInvDetTemp = DB::select("SELECT * FROM inv_doc_epr_detalle_temp WHERE numero = $numero");

        $docInv = new DocumentoEgresoRemision();
        $docInv->prefijo = $docInvTemp[0]->prefijo;
        $docInv->numero =   $docInvTemp[0]->numero;
        $docInv->id_usuario = $docInvTemp[0]->id_usuario;
        $docInv->id_bodega = $docInvTemp[0]->id_bodega;
        $docInv->id_tipo_doc_bodega = $docInvTemp[0]->id_tipo_doc_bodega;
        $docInv->observaciones = $docInvTemp[0]->observaciones;
        $docInv->save();

        foreach ($docInvDetTemp as $key => $value) {
            $docInvDet = new DocumentoEgresoRemisionDetalle();
            $docInvDet->numero = $value->numero;
            $docInvDet->id_tipo_doc_bodega = $value->id_tipo_doc_bodega;
            $docInvDet->id_producto = $value->id_producto;
            $docInvDet->cantidad = $value->cantidad;
            $docInvDet->costo_und = $value->costo_und;
            $docInvDet->costo_total = $value->costo_total;
            $docInvDet->lote = $value->lote;
            $docInvDet->fecha_vto = $value->fecha_vto;
            $docInvDet->save();
        }

        $docInvDetPro = DB::select("SELECT id_producto, cantidad FROM inv_doc_epr_detalle_temp WHERE numero = $numero");

        if (count($docInvDetPro) != 0) {
            foreach ($docInvDetPro as $key => $val) {
            	$codPro = $val->id_producto;

                $exisBode = DB::select("SELECT * FROM existencias_bodegas WHERE id_producto = '$codPro' AND id_bodega = $bodega");

                if (count($exisBode) != 0) {
                	$actual = $exisBode[0]->existencia_actual;
                	$canti = $exisBode[0]->existencia_actual - $val->cantidad;
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

        $docInvDetPros = DB::select("SELECT * FROM inv_doc_epr_detalle_temp WHERE numero = $numero AND lote IS NOT NULL");

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

        $InvDetDel = DB::delete("DELETE FROM inv_doc_epr_detalle_temp WHERE numero = $numero");

		$InvDel = DB::delete("DELETE FROM inv_doc_epr_temp WHERE numero = $numero");
  
        return redirect('regdocepr')->with('success','Documento Ingreso Ajuste Inventario Ingresado Satisfactoriamente.');
    }

    public function show($numero)
    {
        ini_set('max_execution_time', 300);

        $docInv = DB::select("SELECT *, b.descripcion as bodega, u.nombre as usuario, i.created_at FROM inv_doc_epr i JOIN bodegas b ON i.id_bodega = b.id_bodega JOIN system_usuarios u ON i.id_usuario = u.usuario_id JOIN tipos_doc_bodega tb ON i.id_tipo_doc_bodega = tb.id_tipo_doc_bodega WHERE i.numero = $numero");

        $docInvDet = DB::select("SELECT * FROM inv_doc_epr_detalle i JOIN inv_doc_epr b ON i.numero = b.numero JOIN productos u ON i.id_producto = u.id_producto JOIN tipos_doc_bodega tb ON i.id_tipo_doc_bodega = tb.id_tipo_doc_bodega WHERE i.numero = $numero");

        $docInvTot = DB::select("SELECT SUM(costo_total) AS total FROM inv_doc_epr_detalle WHERE numero = $numero");

        $total = $docInvTot[0]->total;
        
        return view('formatos_documentos.doc', compact('docInv','docInvDet','total'));
    }

    public function loadNewPDF($numero){
        // Generador de pdf
        ini_set('max_execution_time', 300);

        $docInv = DB::select("SELECT *, b.descripcion as bodega, u.nombre as usuario, i.created_at FROM inv_doc_epr i JOIN bodegas b ON i.id_bodega = b.id_bodega JOIN system_usuarios u ON i.id_usuario = u.usuario_id JOIN tipos_doc_bodega tb ON i.id_tipo_doc_bodega = tb.id_tipo_doc_bodega WHERE i.numero = $numero");

        $docInvDet = DB::select("SELECT * FROM inv_doc_epr_detalle i JOIN inv_doc_epr b ON i.numero = b.numero JOIN productos u ON i.id_producto = u.id_producto JOIN tipos_doc_bodega tb ON i.id_tipo_doc_bodega = tb.id_tipo_doc_bodega WHERE i.numero = $numero");

        $docInvTot = DB::select("SELECT SUM(costo_total) AS total FROM inv_doc_epr_detalle WHERE numero = $numero");

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

        $detail = DB::delete("DELETE FROM inv_doc_epr_detalle_temp WHERE numero = $numero");

        $doc = DB::delete("DELETE FROM inv_doc_epr_temp WHERE numero = $numero");

        return redirect('regdocepr');

    }
}
