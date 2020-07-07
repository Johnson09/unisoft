<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $usuario = $request->get('usuario');
        $password = $request->get('contraseña');

        $validar = DB::select("SELECT us.usuario_id, us.nombre, us.cargo FROM system_usuarios us WHERE us.usuario = '$usuario' and us.password = md5('$password')");

        session_start();

        if ($validar == null && empty($validar)) {
            return redirect('/')->with('status', 'Usuario o Contraseña incorrecta!');
        }else{
            foreach ($validar as $key => $value){
                $_SESSION['id'] = $value->usuario_id;
                $_SESSION['nombre'] = $value->nombre;
                $_SESSION['cargo'] = $value->cargo;
                $set = $value->usuario_id;
                $nombre = $value->nombre;
            }

            return view('home.index', compact('set','nombre'));
        }
    }
}
