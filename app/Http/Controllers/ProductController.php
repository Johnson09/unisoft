<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Producto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['id'])) {
            return redirect('/')->with('status', 'Credenciales invalidos!');
        }else{

        	$product = DB::select("SELECT *, g.descripcion AS grupo, c.descripcion AS clase, sc.descripcion AS subclase, p.descripcion AS descripcion, um.abreviatura AS medida, m.descripcion AS marca, t.nombre AS proveedor, uv.descripcion AS unidad_venta FROM productos p JOIN grupos g ON p.id_grupo = g.id_grupo JOIN clases c ON p.id_clase = c.id_clase JOIN subclases sc ON p.id_subclase = sc.id_subclase JOIN unds_medida um ON p.id_und_med = um.id_und_med JOIN marcas m ON p.id_marca = m.id_marca JOIN terceros_proveedores tp ON p.id_proveedor = tp.id_proveedor JOIN terceros t ON tp.id_tercero = t.id_tercero JOIN unidades_venta uv ON p.und_venta = uv.uid_und_venta");
            $grupo = DB::select("SELECT * FROM grupos");
            $unidad = DB::select("SELECT * FROM unds_medida");
            $proveedor = DB::select("SELECT * FROM terceros_proveedores tp JOIN terceros t ON tp.id_tercero = t.id_tercero");
            $marca = DB::select("SELECT * FROM marcas");
            $venta = DB::select("SELECT * FROM unidades_venta");
            $nombre = $_SESSION['nombre'];

            return view('crud.producto', compact('nombre','grupo','product','unidad','proveedor','marca','venta'));
        }

    }

    public function getDetails(){

        $id = Input::get('id');

        $producto = DB::select("SELECT * FROM productos WHERE id_producto = '$id'");
            
        return response()->json($producto);
    }

    public function getId(){

        $id = Input::get('id');

        $producto = DB::select("SELECT * FROM productos WHERE id_producto::text like '%$id%' ORDER BY created_at DESC LIMIT 1");
            
        return response()->json($producto);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_producto' => 'required',
            'cod_barras' => 'required',
            'id_grupo' => 'required',
            'id_clase' => 'required',
            'id_subclase' => 'required',
            'descripcion' => 'required',
            'id_und_med' => 'required',
            'id_proveedor' => 'required',
            'id_marca' => 'required',
            'sw_iva' => 'required',
            'und_venta' => 'required',
            'cant_und_venta' => 'required',
        ]);
  
        Producto::create($request->all());
   
        return redirect('regprod')
                        ->with('success','Centro de costo creado satisfactoriamente.');
    }

    public function update(Request $request, $product)
    {
        $request->validate([
            'id_grupo' => 'required',
            'id_clase' => 'required',
            'id_subclase' => 'required',
            'descripcion' => 'required',
            'id_und_med' => 'required',
            'id_proveedor' => 'required',
            'id_marca' => 'required',
            'sw_iva' => 'required',
            'sw_estado' => 'required',
            'und_venta' => 'required',
            'cant_und_venta' => 'required',
        ]);
  
        // $product->update($request->all())};
        $product = Producto::find($product);
        $product->id_grupo = $request->get('id_grupo');
        $product->id_clase = $request->get('id_clase');
        $product->id_subclase = $request->get('id_subclase');
        $product->descripcion = $request->get('descripcion');
        $product->id_und_med = $request->get('id_und_med');
        $product->id_proveedor = $request->get('id_proveedor');
        $product->id_marca = $request->get('id_marca');
        $product->sw_iva = $request->get('sw_iva');
        $product->sw_estado = $request->get('sw_estado');
        $product->und_venta = $request->get('und_venta');
        $product->cant_und_venta = $request->get('cant_und_venta');

        $product->save();
  
        return redirect('regprod')
                        ->with('success','Centro de costo actualizado satisfactoriamente.');
    }
  
    public function destroy(Product $product)
    {
        $product->delete();
  
        return redirect('regprod')
                        ->with('success','Centro de costo eliminado satisfactoriamente.');
    }
}
