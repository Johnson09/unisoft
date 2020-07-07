<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Empresa;

class CompanyController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{

            $ciudad = DB::select("SELECT * FROM ciudades");
            $tipo = DB::select("SELECT * FROM tipos_id");
            $company = DB::select("SELECT *, ti.tipo_id AS tipo FROM empresas em JOIN tipos_id ti ON em.tipo_id = ti.id_tipo_id JOIN ciudades c ON em.ciudad_id = c.ciudad_id");
            $nombre = $_SESSION['nombre'];

            return view('crud.empresa', compact('company','nombre','ciudad','tipo'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $company = DB::select("SELECT * FROM empresas WHERE id_empresa = '$id'");
            
        return response()->json($company);
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'id_empresa' => 'required',
            'tipo_id' => 'required',
            'numero_id' => 'required',
            'representante_legal' => 'required',
            'ciudad_id' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required',
        ]);
  
        Empresa::create($request->all());
   
        return redirect('regemp')
                        ->with('success', 'Empresa creada satisfactoriamente.');
    }

    public function edit($company)
    {
        return redirect()->action(
            'CompanyController@update', ['company' => $company]
        );
    }

    public function update(Request $request, $company)
    {
        $request->validate([
            'tipo_id' => 'required',
            'numero_id' => 'required',
            'representante_legal' => 'required',
            'ciudad_id' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'estado' => 'required',
        ]);
  
        // $company->update($request->all());
        $empresa = Empresa::find($company);
        $empresa->tipo_id = $request->get('tipo_id');
        $empresa->numero_id = $request->get('numero_id');
        $empresa->representante_legal = $request->get('representante_legal');
        $empresa->ciudad_id = $request->get('ciudad_id');
        $empresa->direccion = $request->get('direccion');
        $empresa->telefono = $request->get('telefono');
        $empresa->email = $request->get('email');
        $empresa->estado = $request->get('estado');
        $empresa->save();
  
        return redirect('regemp')
                        ->with('success', 'Empresa actualizada satisfactoriamente.');
    }
  
    public function destroy($company)
    {
        $company->delete();
  
        return redirect('regemp')
                        ->with('success', 'Empresa eliminada satisfactoriamente.');
    }
}
