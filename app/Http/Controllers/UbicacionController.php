<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Ubicacion;

class UbicacionController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{
            
            $ubicacion = DB::select("SELECT *, u.descripcion AS descripcion, tp.descripcion AS tipo FROM ubicaciones u JOIN tipos_ubicaciones tp ON u.tipo_ubicacion = tp.id_tipo_ubicacion");
            $tipo = DB::select("SELECT * FROM tipos_ubicaciones");
            $nombre = $_SESSION['nombre'];

            return view('crud.ubicacion', compact('ubicacion','nombre','tipo'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $ubicacion = DB::select("SELECT * FROM ubicaciones WHERE id_ubicacion = '$id'");
            
        return response()->json($ubicacion);
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required',
            'tipo_ubicacion' => 'required',
        ]);
  
        Ubicacion::create($request->all());

        $ubi1 = $request->get('ubi0');
        $ubi2 = $request->get('ubi1');
        $ubi3 = $request->get('ubi2');

        if (!empty($ubi1)) {
            $id_ubicacion = DB::select('SELECT * FROM ubicaciones ORDER BY created_at DESC LIMIT 1');
            DB::table('ubicaciones1')->insert(
                ['id_ubicacion' => $id_ubicacion[0]->id_ubicacion, 'descripcion' => $ubi1]
            );

            if (!empty($ubi2)) {
                $id_ubicacion1 = DB::select('SELECT * FROM ubicaciones1 ORDER BY created_at DESC LIMIT 1');
                DB::table('ubicaciones2')->insert(
                    ['id_ubicacion1' => $id_ubicacion1[0]->id_ubicacion1, 'descripcion' => $ubi2]
                );

                if (!empty($ubi3)) {
                    $id_ubicacion2 = DB::select('SELECT * FROM ubicaciones2 ORDER BY created_at DESC LIMIT 1');
                    DB::table('ubicaciones3')->insert(
                        ['id_ubicacion2' => $id_ubicacion2[0]->id_ubicacion2, 'descripcion' => $ubi3]
                    );
                }
            }
        }
   
        return redirect('regubi')
                        ->with('success','Centro Operacion creado satisfactoriamente.');
    }

    public function update(Request $request, $ubicacion)
    {
        $request->validate([
            'descripcion' => 'required',
            'tipo_ubicacion' => 'required',
        ]);
  
        $ubicacion = Ubicacion::find($ubicacion);
        $ubicacion->descripcion = $request->get('descripcion');
        $ubicacion->tipo_ubicacion = $request->get('tipo_ubicacion');
        $ubicacion->save();
  
        return redirect('regubi')
                        ->with('success','Centro Operacion actualizado satisfactoriamente.');
    }
  
    public function destroy($ubicacion)
    {
        $ubicacion->delete();
  
        return redirect('regubi')
                        ->with('success','Centro Operacion eliminado satisfactoriamente.');
    }
}
