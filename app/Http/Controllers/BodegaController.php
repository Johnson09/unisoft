<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Bodega;

class BodegaController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{

            $costo = DB::select("SELECT * FROM centros_costo");
            $bodega = DB::select("SELECT *, cc.descripcion AS costo, b.direccion AS direccion, b.descripcion AS descripcion FROM bodegas b JOIN centros_costo cc ON b.id_centro_costo = cc.id_centro_costo");
            $existencia = DB::select("SELECT *, g.descripcion AS grupo, c.descripcion AS clase, sc.descripcion AS subclase, p.descripcion AS producto, p.id_producto FROM productos p LEFT JOIN inventarios_productos ip ON p.id_producto = ip.id_producto LEFT JOIN existencias_bodegas b ON p.id_producto = b.id_producto LEFT JOIN bodegas bd ON b.id_bodega = bd.id_bodega JOIN grupos g ON g.id_grupo = p.id_grupo JOIN clases c ON c.id_clase = p.id_clase JOIN subclases sc ON sc.id_subclase = p.id_subclase");
            $nombre = $_SESSION['nombre'];

            return view('crud.bodega', compact('bodega','nombre','costo','existencia'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $bodega = DB::select("SELECT * FROM bodegas WHERE id_bodega = '$id'");
            
        return response()->json($bodega);
    }

    public function getProducto(){
        $id = Input::get('id');
        $bod = Input::get('bod');

        $id_producto = DB::select("SELECT eb.id_producto AS producto FROM existencias_bodegas eb JOIN productos p ON eb.id_producto = p.id_producto WHERE eb.id_producto = '$id' AND eb.id_bodega = '$bod'");

        if (count($id_producto) == 0) {
            $codigo_barra = DB::select("SELECT eb.id_producto AS producto FROM existencias_bodegas eb JOIN productos p ON eb.id_producto = p.id_producto WHERE p.cod_barras = '$id' AND eb.id_bodega = '$bod'");

            if (count($codigo_barra) == 0) {
                $descripcion = DB::select("SELECT eb.id_producto AS producto FROM existencias_bodegas eb JOIN productos p ON eb.id_producto = p.id_producto WHERE p.descripcion = '$id' AND eb.id_bodega = '$bod'");
    
                if (count($descripcion) == 0) {
                    $producto = DB::select("SELECT id_producto FROM productos WHERE id_producto = '$id' AND sw_estado = '1'");
        
                    if (count($producto) == 0) {
                        $codigo = DB::select("SELECT id_producto FROM productos WHERE cod_barras = '$id' AND sw_estado = '1'");
            
                        if (count($codigo) == 0) {
                            $des = DB::select("SELECT id_producto FROM productos WHERE descripcion = '$id' AND sw_estado = '1'");
                
                            return response()->json($des);
                            
                        }else {
                            return response()->json($codigo);
                        }
                        
                    }else {
                        return response()->json($producto);
                    }
                }else {
                    return response()->json($descripcion);
                }
                
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
            'id_centro_costo' => 'required',
            'descripcion' => 'required',
            'direccion' => 'required',
        ]);
  
        Bodega::create($request->all());
   
        return redirect('regbod')
                        ->with('success','Centro Operacion creado satisfactoriamente.');
    }

    public function update(Request $request, $bodega)
    {
        $request->validate([
            'id_centro_costo' => 'required',
            'descripcion' => 'required',
            'direccion' => 'required',
            'sw_estado' => 'required',
        ]);
  
        $bodega = Bodega::find($bodega);
        $bodega->id_centro_costo = $request->get('id_centro_costo');
        $bodega->descripcion = $request->get('descripcion');
        $bodega->direccion = $request->get('direccion');
        $bodega->sw_estado = $request->get('sw_estado');
        $bodega->save();
  
        return redirect('regbod')
                        ->with('success','Centro Operacion actualizado satisfactoriamente.');
    }
  
    public function destroy($bodega)
    {
        $bodega->delete();
  
        return redirect('regbod')
                        ->with('success','Centro Operacion eliminado satisfactoriamente.');
    }
}
