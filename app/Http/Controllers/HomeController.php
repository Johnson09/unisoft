<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{

            $set = $_SESSION['id'];
            $nombre = $_SESSION['nombre'];
                
            return view('home.index', compact('set','nombre'));
        }

    }
}
