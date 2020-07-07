<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Caja;

class CajaController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{

            $sede = DB::select("SELECT * FROM sedes");
            $caja = DB::select("SELECT *, se.descripcion as sede, co.descripcion AS centro_costo, c.descripcion AS descripcion FROM cajas c JOIN centros_costo co ON c.id_centro_costo = co.id_centro_costo JOIN sedes AS se ON c.id_sede = se.id_sede");
            $nombre = $_SESSION['nombre'];

            return view('crud.caja', compact('caja','nombre','sede'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $caja = DB::select("SELECT * FROM cajas WHERE id_caja = '$id'");
            
        return response()->json($caja);
    }

    public function getCosto(){

        $id = Input::get('id');

        $sede = DB::select("SELECT id_centro_operacion FROM sedes WHERE id_sede = '$id'");

        $co = $sede[0]->id_centro_operacion;

        $costo = DB::select("SELECT * FROM centros_costo WHERE id_centro_operacion = '$co'");
            
        return response()->json($costo);
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required',
            'id_sede' => 'required',
            'id_centro_costo' => 'required',
            'tipo_venta' => 'required',
        ]);
  
        Caja::create($request->all());
   
        return redirect('regcaj')
                        ->with('success','Centro de costo creado satisfactoriamente.');
    }

    public function update(Request $request, $caja)
    {
        $request->validate([
            'descripcion' => 'required',
            'id_sede' => 'required',
            'id_centro_costo' => 'required',
            'tipo_venta' => 'required',
            'estado' => 'required',
        ]);
  
        // $caja->update($request->all());
        $caja = Caja::find($caja);
        $caja->descripcion = $request->get('descripcion');
        $caja->id_sede = $request->get('id_sede');
        $caja->id_centro_costo = $request->get('id_centro_costo');
        $caja->tipo_venta = $request->get('tipo_venta');
        $caja->estado = $request->get('estado');
        $caja->save();
  
        return redirect('regcaj')
                        ->with('success','Centro de costo actualizado satisfactoriamente.');
    }
  
    public function destroy($caja)
    {
        $caja->delete();
  
        return redirect('regcaj')
                        ->with('success','Centro de costo eliminado satisfactoriamente.');
    }
}
