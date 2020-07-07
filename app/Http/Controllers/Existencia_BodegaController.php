<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Existencia_Bodega;

class Existencia_BodegaController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{
            
            $bodega = DB::select("SELECT * FROM bodegas");
            $producto = DB::select("SELECT * FROM productos");
            $existencia = DB::select("SELECT *, b.descripcion AS bodega, p.descripcion AS producto FROM existencias_bodegas eb JOIN bodegas b ON eb.id_bodega = b.id_bodega JOIN productos p ON eb.id_producto = p.id_producto");
            $nombre = $_SESSION['nombre'];

            return view('crud.existencia_bodega', compact('bodega','nombre','existencia','producto'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $existencia = DB::select("SELECT * FROM existencias_bodegas WHERE id = '$id'");
            
        return response()->json($existencia);
    }

    public function validProducto(){

        $id = Input::get('id');

        $existencia = DB::select("SELECT * FROM inventarios_productos WHERE id_producto = '$id'");
            
        return response()->json($existencia);
    }

    public function save(Request $request)
    {
        $request->validate([
            'id_bodega' => 'required',
            'id_producto' => 'required',
            'existencia_minima' => 'required',
            'existencia_maxima' => 'required',
        ]);
  
        Existencia_Bodega::create($request->all());
   
        return redirect('regbod')
                        ->with('success','Centro Operacion creado satisfactoriamente.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_bodega' => 'required',
            'id_producto' => 'required',
            'existencia_inicial' => 'required',
            'existencia_actual' => 'required',
            'existencia_minima' => 'required',
            'existencia_maxima' => 'required',
        ]);
  
        Existencia_Bodega::create($request->all());
   
        return redirect('regexsbod')
                        ->with('success','Centro Operacion creado satisfactoriamente.');
    }

    public function update(Request $request, $existencia)
    {
        $request->validate([
            'id_bodega' => 'required',
            'id_producto' => 'required',
            'existencia_inicial' => 'required',
            'existencia_actual' => 'required',
            'existencia_minima' => 'required',
            'existencia_maxima' => 'required',
        ]);
  
        $existencia = Existencia_Bodega::find($existencia);
        $existencia->id_bodega = $request->get('id_bodega');
        $existencia->id_producto = $request->get('id_producto');
        $existencia->existencia_inicial = $request->get('existencia_inicial');
        $existencia->existencia_actual = $request->get('existencia_actual');
        $existencia->existencia_minima = $request->get('existencia_minima');
        $existencia->existencia_maxima = $request->get('existencia_maxima');
        $existencia->save();
  
        return redirect('regexsbod')
                        ->with('success','Centro Operacion actualizado satisfactoriamente.');
    }
  
    public function destroy($existencia)
    {
        $existencia->delete();
  
        return redirect('regexsbod')
                        ->with('success','Centro Operacion eliminado satisfactoriamente.');
    }
}
