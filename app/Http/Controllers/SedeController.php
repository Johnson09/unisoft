<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Sede;

class SedeController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{

            $operacion = DB::select("SELECT * FROM centros_operacion");
            $sede = DB::select("SELECT *, co.descripcion AS centro_operacion, s.descripcion AS descripcion FROM sedes s JOIN centros_operacion co ON s.id_centro_operacion = co.id_centro_operacion");
            $nombre = $_SESSION['nombre'];

            return view('crud.sede', compact('sede','nombre','operacion'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $sede = DB::select("SELECT * FROM sedes WHERE id_sede = '$id'");
            
        return response()->json($sede);
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required',
            'id_centro_operacion' => 'required',
        ]);
  
        Sede::create($request->all());
   
        return redirect('regsed')
                        ->with('success','Centro de costo creado satisfactoriamente.');
    }

    public function update(Request $request, $sede)
    {
        $request->validate([
            'descripcion' => 'required',
            'id_centro_operacion' => 'required',
            'estado' => 'required',
        ]);
  
        // $sede->update($request->all());
        $sede = Sede::find($sede);
        $sede->descripcion = $request->get('descripcion');
        $sede->id_centro_operacion = $request->get('id_centro_operacion');
        $sede->estado = $request->get('estado');
        $sede->save();
  
        return redirect('regsed')
                        ->with('success','Centro de costo actualizado satisfactoriamente.');
    }
  
    public function destroy($sede)
    {
        $sede->delete();
  
        return redirect('regsed')
                        ->with('success','Centro de costo eliminado satisfactoriamente.');
    }
}
