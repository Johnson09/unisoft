<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Clase;

class ClaseController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{
            
            $grupo = DB::select("SELECT * FROM grupos");
            $clase = DB::select("SELECT *, c.descripcion AS descripcion, g.descripcion AS grupo FROM clases c JOIN grupos g ON c.id_grupo = g.id_grupo");
            $nombre = $_SESSION['nombre'];

            return view('crud.clase', compact('clase','nombre','grupo'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $clase = DB::select("SELECT * FROM clases WHERE id_clase = '$id'");
            
        return response()->json($clase);
    }

    public function getClases(){

        $id = Input::get('id');

        $clase = DB::select("SELECT * FROM clases WHERE id_grupo = '$id'");
            
        return response()->json($clase);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_clase' => 'required',
            'id_grupo' => 'required',
            'descripcion' => 'required',
        ]);
  
        Clase::create($request->all());
   
        return redirect('regclas')
                        ->with('success','Centro Operacion creado satisfactoriamente.');
    }

    public function update(Request $request, $clase)
    {
        $request->validate([
            'id_grupo' => 'required',
            'descripcion' => 'required',
            'estado' => 'required',
        ]);
  
        $clase = Clase::find($clase);
        $clase->id_grupo = $request->get('id_grupo');
        $clase->descripcion = $request->get('descripcion');
        $clase->estado = $request->get('estado');
        $clase->save();
  
        return redirect('regclas')
                        ->with('success','Centro Operacion actualizado satisfactoriamente.');
    }
  
    public function destroy($clase)
    {
        $clase->delete();
  
        return redirect('regclas')
                        ->with('success','Centro Operacion eliminado satisfactoriamente.');
    }
}
