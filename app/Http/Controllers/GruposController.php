<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Grupos;

class GruposController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{
            
            $grupo = DB::select("SELECT * FROM grupos");
            $nombre = $_SESSION['nombre'];

            return view('crud.grupo', compact('grupo','nombre'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $grupo = DB::select("SELECT * FROM grupos WHERE id_grupo = '$id'");
            
        return response()->json($grupo);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_grupo' => 'required',
            'descripcion' => 'required',
        ]);
  
        Grupos::create($request->all());
   
        return redirect('reggrup')
                        ->with('success','Centro Operacion creado satisfactoriamente.');
    }

    public function update(Request $request, $grupo)
    {
        $request->validate([
            'descripcion' => 'required',
            'estado' => 'required',
        ]);
  
        $grupo = Grupos::find($grupo);
        $grupo->descripcion = $request->get('descripcion');
        $grupo->estado = $request->get('estado');
        $grupo->save();
  
        return redirect('reggrup')
                        ->with('success','Centro Operacion actualizado satisfactoriamente.');
    }
  
    public function destroy($grupo)
    {
        $grupo->delete();
  
        return redirect('reggrup')
                        ->with('success','Centro Operacion eliminado satisfactoriamente.');
    }
}
