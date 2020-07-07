<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\CentroOperacion;

class COController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{
            
            $ciudad = DB::select("SELECT * FROM ciudades");
            $company = DB::select("SELECT * FROM empresas");
            $operaciones = DB::select("SELECT *, co.direccion AS direccion, co.telefono AS telefono FROM centros_operacion co JOIN empresas em ON co.id_empresa = em.id_empresa JOIN ciudades c ON co.ciudad_id = c.ciudad_id");
            $nombre = $_SESSION['nombre'];

            return view('crud.centro_operacion', compact('operaciones','nombre','ciudad','company'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $co = DB::select("SELECT * FROM centros_operacion WHERE id_centro_operacion = '$id'");
            
        return response()->json($co);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_centro_operacion' => 'required',
            'id_empresa' => 'required',
            'descripcion' => 'required',
            'ciudad_id' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
        ]);
  
        CentroOperacion::create($request->all());
   
        return redirect('regco')
                        ->with('success','Centro Operacion creado satisfactoriamente.');
    }

    public function update(Request $request, $co)
    {
        $request->validate([
            'id_empresa' => 'required',
            'descripcion' => 'required',
            'ciudad_id' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
        ]);
  
        $co = CentroOperacion::find($co);
        $co->id_empresa = $request->get('id_empresa');
        $co->descripcion = $request->get('descripcion');
        $co->ciudad_id = $request->get('ciudad_id');
        $co->direccion = $request->get('direccion');
        $co->telefono = $request->get('telefono');
        $co->save();
  
        return redirect('regco')
                        ->with('success','Centro Operacion actualizado satisfactoriamente.');
    }
  
    public function destroy($co)
    {
        $co->delete();
  
        return redirect('regco')
                        ->with('success','Centro Operacion eliminado satisfactoriamente.');
    }
}
