<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Subclase;

class SubclaseController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{
            
            $clase = DB::select("SELECT * FROM clases");
            $subclase = DB::select("SELECT *, c.descripcion AS clase, s.descripcion AS descripcion FROM subclases s JOIN clases c ON s.id_clase = c.id_clase");
            $nombre = $_SESSION['nombre'];

            return view('crud.subclase', compact('clase','nombre','subclase'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $subclase = DB::select("SELECT * FROM subclases WHERE id_subclase = '$id'");
            
        return response()->json($subclase);
    }

    public function getSubclases(){

        $id = Input::get('id');

        $subclase = DB::select("SELECT * FROM subclases WHERE id_clase = '$id'");
            
        return response()->json($subclase);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_subclase' => 'required',
            'id_clase' => 'required',
            'descripcion' => 'required',
        ]);
  
        Subclase::create($request->all());
   
        return redirect('regsubc')
                        ->with('success','Centro Operacion creado satisfactoriamente.');
    }

    public function update(Request $request, $subclase)
    {
        $request->validate([
            'id_clase' => 'required',
            'descripcion' => 'required',
            'estado' => 'required',
        ]);
  
        $subclase = Subclase::find($subclase);
        $subclase->id_clase = $request->get('id_clase');
        $subclase->descripcion = $request->get('descripcion');
        $subclase->estado = $request->get('estado');
        $subclase->save();
  
        return redirect('regsubc')
                        ->with('success','Centro Operacion actualizado satisfactoriamente.');
    }
  
    public function destroy($subclase)
    {
        $subclase->delete();
  
        return redirect('regsubc')
                        ->with('success','Centro Operacion eliminado satisfactoriamente.');
    }
}
