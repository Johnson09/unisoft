<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Tercero;

class TerceroController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{

            $departamento = DB::select("SELECT * FROM departamentos");
            $naturaleza = DB::select("SELECT * FROM naturaleza");
            $tipo = DB::select("SELECT * FROM tipos_id");
            $tercero = DB::select("SELECT *, n.descripcion AS naturaleza, ti.tipo_id AS tipo, c.nombre AS ciudad, d.nombre AS departamento, t.nombre AS nombre FROM terceros t JOIN naturaleza n ON t.id_naturaleza = n.id_naturaleza JOIN tipos_id ti ON t.tipo_id_tercero = ti.id_tipo_id JOIN ciudades c ON t.ciudad_id = c.ciudad_id JOIN departamentos d ON t.departamento_id = d.departamento_id");
            $nombre = $_SESSION['nombre'];

            return view('crud.tercero', compact('tercero','nombre','departamento','tipo','naturaleza'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $tercero = DB::select("SELECT * FROM terceros WHERE id_tercero = '$id'");
            
        return response()->json($tercero);
    }

    public function getCiudades(){

        $id = Input::get('id');

        $ciudad = DB::select("SELECT * FROM ciudades WHERE departamento_id = '$id'");
            
        return response()->json($ciudad);
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'tipo_id_tercero' => 'required',
            'id_tercero' => 'required',
            'id_naturaleza' => 'required',
            'nombre' => 'required',
            'ciudad_id' => 'required',
            'departamento_id' => 'required',
            'digito_v' => 'required',
            'contacto' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'sw_tipo_tercero' => 'required',
        ]);
  
        Tercero::create($request->all());

        $id_tercero = $request->get('id_tercero');
        $tipo = $request->get('sw_tipo_tercero');

        if ($tipo == '1') {
            DB::table('terceros_proveedores')->insert(
                ['id_tercero' => $id_tercero, 'estado' => 1]
            );
        }
   
        return redirect('regterc')
                        ->with('success', 'Empresa creada satisfactoriamente.');
    }

    public function update(Request $request, $tercero)
    {
        $request->validate([
            'tipo_id_tercero' => 'required',
            'id_naturaleza' => 'required',
            'nombre' => 'required',
            'ciudad_id' => 'required',
            'departamento_id' => 'required',
            'digito_v' => 'required',
            'contacto' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'sw_tipo_tercero' => 'required',
        ]);
  
        // $company->update($request->all());
        $tercero = Tercero::find($tercero);
        $tercero->tipo_id_tercero = $request->get('tipo_id_tercero');
        $tercero->id_naturaleza = $request->get('id_naturaleza');
        $tercero->nombre = $request->get('nombre');
        $tercero->departamento_id = $request->get('departamento_id');
        $tercero->ciudad_id = $request->get('ciudad_id');
        $tercero->digito_v = $request->get('digito_v');
        $tercero->contacto = $request->get('contacto');
        $tercero->email = $request->get('email');
        $tercero->direccion = $request->get('direccion');
        $tercero->telefono = $request->get('telefono');
        $tercero->sw_tipo_tercero = $request->get('sw_tipo_tercero');
        $tercero->save();
  
        return redirect('regterc')
                        ->with('success', 'Empresa actualizada satisfactoriamente.');
    }
  
    public function destroy($tercero)
    {
        $tercero->delete();
  
        return redirect('regterc')
                        ->with('success', 'Empresa eliminada satisfactoriamente.');
    }
}
