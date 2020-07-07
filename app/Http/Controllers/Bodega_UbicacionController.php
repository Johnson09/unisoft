<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Bodega_Ubicacion;

class Bodega_UbicacionController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{

            $bodega = DB::select("SELECT * FROM bodegas");
            $ubicacion = DB::select("SELECT * FROM ubicaciones");
            $bodubi = DB::select("SELECT *, b.descripcion AS bodega, u.descripcion AS ubicacion, u1.descripcion AS nivel1, u2.descripcion AS nivel2, u3.descripcion AS nivel3 FROM bodegas_ubicaciones bu JOIN bodegas b ON bu.id_bodega = b.id_bodega JOIN ubicaciones u ON bu.id_ubicacion = u.id_ubicacion JOIN ubicaciones1 u1 ON bu.id_ubicacion1 = u1.id_ubicacion1 JOIN ubicaciones2 u2 ON bu.id_ubicacion2 = u2.id_ubicacion2 JOIN ubicaciones3 u3 ON bu.id_ubicacion3 = u3.id_ubicacion3");
            $nombre = $_SESSION['nombre'];

            return view('crud.bodega_ubicacion', compact('bodega','nombre','ubicacion','bodubi'));
        }

    }

    // public function getDetails(){

    //     $id = Input::get('id');

    //     $caja = DB::select("SELECT * FROM bodegas_ubicaciones WHERE id_caja = '$id'");
            
    //     return response()->json($caja);
    // }

    public function getUbicacion1(){

        $id = Input::get('id');

        $ubicacion = DB::select("SELECT * FROM ubicaciones1 WHERE id_ubicacion = '$id'");
            
        return response()->json($ubicacion);
    }

    public function getUbicacion2(){

        $id = Input::get('id');

        $ubicacion = DB::select("SELECT * FROM ubicaciones2 WHERE id_ubicacion1 = '$id'");
            
        return response()->json($ubicacion);
    }

    public function getUbicacion3(){

        $id = Input::get('id');

        $ubicacion = DB::select("SELECT * FROM ubicaciones3 WHERE id_ubicacion2 = '$id'");
            
        return response()->json($ubicacion);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_bodega' => 'required',
            'id_ubicacion' => 'required',
            'id_ubicacion1' => 'required',
            'id_ubicacion2' => 'required',
            'id_ubicacion3' => 'required',
        ]);
  
        Bodega_Ubicacion::create($request->all());
   
        return redirect('regbodubi')
                        ->with('success','Centro de costo creado satisfactoriamente.');
    }

    // public function update(Request $request, $caja)
    // {
    //     $request->validate([
    //         'descripcion' => 'required',
    //         'id_sede' => 'required',
    //         'id_centro_costo' => 'required',
    //         'tipo_venta' => 'required',
    //         'estado' => 'required',
    //     ]);
  
    //     // $caja->update($request->all());
    //     $caja = Caja::find($caja);
    //     $caja->descripcion = $request->get('descripcion');
    //     $caja->id_sede = $request->get('id_sede');
    //     $caja->id_centro_costo = $request->get('id_centro_costo');
    //     $caja->tipo_venta = $request->get('tipo_venta');
    //     $caja->estado = $request->get('estado');
    //     $caja->save();
  
    //     return redirect('regcaj')
    //                     ->with('success','Centro de costo actualizado satisfactoriamente.');
    // }
  
    // public function destroy($caja)
    // {
    //     $caja->delete();
  
    //     return redirect('regcaj')
    //                     ->with('success','Centro de costo eliminado satisfactoriamente.');
    // }
}
