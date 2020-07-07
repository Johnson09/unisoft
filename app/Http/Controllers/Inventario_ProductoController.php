<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Inventario_Producto;

class Inventario_ProductoController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{
            
            $empresa = DB::select("SELECT * FROM empresas");
            $producto = DB::select("SELECT * FROM productos");
            $grupo = DB::select("SELECT * FROM grupos");
            $inventario = DB::select("SELECT *, g.descripcion AS grupo, c.descripcion AS clase, sc.descripcion AS subclase, p.descripcion AS producto, e.representante_legal AS empresa FROM inventarios_productos ip JOIN empresas e ON ip.id_empresa = e.id_empresa JOIN productos p ON ip.id_producto = p.id_producto JOIN grupos g ON ip.id_grupo = g.id_grupo JOIN clases c ON ip.id_clase = c.id_clase JOIN subclases sc ON ip.id_subclase = sc.id_subclase");
            $existencia = DB::select("SELECT *, g.descripcion AS grupo, c.descripcion AS clase, sc.descripcion AS subclase, p.descripcion AS producto, p.id_producto FROM productos p LEFT JOIN inventarios_productos ip ON p.id_producto = ip.id_producto JOIN grupos g ON g.id_grupo = p.id_grupo JOIN clases c ON c.id_clase = p.id_clase JOIN subclases sc ON sc.id_subclase = p.id_subclase WHERE ip.costo IS NULL");
            $nombre = $_SESSION['nombre'];

            return view('crud.inventario_producto', compact('inventario','nombre','grupo','empresa','producto','existencia'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $inventario = DB::select("SELECT * FROM inventarios_productos WHERE id = '$id'");
            
        return response()->json($inventario);
    }

    public function getProducto(){
        $id = Input::get('id');

        $id_producto = DB::select("SELECT id_producto, id_grupo, id_clase, id_subclase FROM productos WHERE id_producto = '$id' AND sw_estado = '1'");

        if (count($id_producto) == 0) {
            $codigo_barra = DB::select("SELECT id_producto, id_grupo, id_clase, id_subclase FROM productos WHERE cod_barras = '$id' AND sw_estado = '1'");

            if (count($codigo_barra) == 0) {
                $descripcion = DB::select("SELECT id_producto, id_grupo, id_clase, id_subclase FROM productos WHERE descripcion = '$id' AND sw_estado = '1'");
    
                return response()->json($descripcion);
                
            }else {
                return response()->json($codigo_barra);
            }
        }else {
            return response()->json($id_producto);
        }
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_empresa' => 'required',
            'id_producto' => 'required',
            'id_grupo' => 'required',
            'id_clase' => 'required',
            'id_subclase' => 'required',
            'costo' => 'required',
            'precio_venta' => 'required',
            'iva' => 'required',
            'sw_fv_lote' => 'required',
        ]);
  
        Inventario_Producto::create($request->all());
   
        return redirect('reginvpro')
                        ->with('success','Centro Operacion creado satisfactoriamente.');
    }

    public function update(Request $request, $inventario)
    {
        $request->validate([
            'id_empresa' => 'required',
            'id_producto' => 'required',
            'id_grupo' => 'required',
            'id_clase' => 'required',
            'id_subclase' => 'required',
            'costo_anterior' => 'required',
            'costo' => 'required',
            'precio_venta' => 'required',
            'iva' => 'required',
            'sw_fv_lote' => 'required',
        ]);
  
        $inventario = Inventario_Producto::find($inventario);
        $inventario->id_empresa = $request->get('id_empresa');
        $inventario->id_producto = $request->get('id_producto');
        $inventario->id_grupo = $request->get('id_grupo');
        $inventario->id_clase = $request->get('id_clase');
        $inventario->id_subclase = $request->get('id_subclase');
        $inventario->costo_anterior = $request->get('costo_anterior');
        $inventario->costo = $request->get('costo');
        $inventario->precio_venta = $request->get('precio_venta');
        $inventario->iva = $request->get('iva');
        $inventario->sw_fv_lote = $request->get('sw_fv_lote');
        $inventario->save();
  
        return redirect('reginvpro')
                        ->with('success','Centro Operacion actualizado satisfactoriamente.');
    }
  
    public function destroy($inventario)
    {
        $inventario->delete();
  
        return redirect('reginvpro')
                        ->with('success','Centro Operacion eliminado satisfactoriamente.');
    }
}
