<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\CentroCosto;

class CCController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{

            $operacion = DB::select("SELECT * FROM centros_operacion");
            $costos = DB::select("SELECT *, co.descripcion AS centro_operacion, cc.descripcion AS descripcion FROM centros_costo cc JOIN centros_operacion co ON cc.id_centro_operacion = co.id_centro_operacion");
            $nombre = $_SESSION['nombre'];

            return view('crud.centro_costo', compact('costos','nombre','operacion'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $cc = DB::select("SELECT * FROM centros_costo WHERE id_centro_costo = '$id'");
            
        return response()->json($cc);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_centro_costo' => 'required',
            'id_centro_operacion' => 'required',
            'descripcion' => 'required',
            'cuenta' => 'required',
        ]);
  
        CentroCosto::create($request->all());
   
        return redirect('regcc')
                        ->with('success','Centro de costo creado satisfactoriamente.');
    }

    public function update(Request $request, $cc)
    {
        $request->validate([
            'id_centro_operacion' => 'required',
            'descripcion' => 'required',
            'cuenta' => 'required',
            'estado' => 'required',
        ]);
  
        // $cc->update($request->all());
        $cc = CentroCosto::find($cc);
        $cc->id_centro_operacion = $request->get('id_centro_operacion');
        $cc->descripcion = $request->get('descripcion');
        $cc->cuenta = $request->get('cuenta');
        $cc->estado = $request->get('estado');
        $cc->save();
  
        return redirect('regcc')
                        ->with('success','Centro de costo actualizado satisfactoriamente.');
    }
  
    public function destroy($cc)
    {
        $cc->delete();
  
        return redirect('regcc')
                        ->with('success','Centro de costo eliminado satisfactoriamente.');
    }
}
