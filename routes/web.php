<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index.index');
});

Route::get('/logout', function () {
    session_start();
    session_destroy();
    return view('index.index');
});

Route::get('/ini2', function () {
    return view('index.second');
});

// Bodegas
Route::resource('/regbod', 'BodegaController');

Route::get('/getDetailsBod', 'BodegaController@getDetails');

Route::get('/validProducto2', 'BodegaController@getProducto');

// Bodegas - Ubicaciones
Route::resource('/regbodubi', 'Bodega_UbicacionController');

Route::get('/getNivel1', 'Bodega_UbicacionController@getUbicacion1');

Route::get('/getNivel2', 'Bodega_UbicacionController@getUbicacion2');

Route::get('/getNivel3', 'Bodega_UbicacionController@getUbicacion3');

// Caja
Route::resource('/regcaj', 'CajaController');

Route::get('/getDetailsCaj', 'CajaController@getDetails');

Route::get('/getCost', 'CajaController@getCosto');

// Clase
Route::resource('/regclas', 'ClaseController');

Route::get('/getDetailsclas', 'ClaseController@getDetails');

Route::get('/getClas', 'ClaseController@getClases');

// Centro Costos
Route::resource('/regcc', 'CCController');

Route::get('/getDetailsCC', 'CCController@getDetails');

// Centro Operacion
Route::resource('/regco', 'COController');

Route::get('/getDetailsCO', 'COController@getDetails');

// Empresa
Route::resource('/regemp', 'CompanyController');

Route::get('/getDetailsComp', 'CompanyController@getDetails');

// Existencia Bodega
Route::resource('/regexsbod', 'Existencia_BodegaController');

Route::get('/getDetailsExsBod', 'Existencia_BodegaController@getDetails');

Route::get('/getValidProd', 'Existencia_BodegaController@validProducto');

Route::post('/regexisbod', 'Existencia_BodegaController@save');

// Grupo
Route::resource('/reggrup', 'GruposController');

Route::get('/getDetailsgrup', 'GruposController@getDetails');

// Inicio
Route::resource('/home', 'HomeController');

// Inventario Producto
Route::resource('/reginvpro', 'Inventario_ProductoController');

Route::get('/getDetailsInPro', 'Inventario_ProductoController@getDetails');

Route::get('/validProducto1', 'Inventario_ProductoController@getProducto');

// Login
Route::resource('/login', 'LoginController');

// Productos
Route::resource('/regprod', 'ProductController');

Route::get('/getDetailsProd', 'ProductController@getDetails');

Route::get('/getConsecutivo', 'ProductController@getId');

// Ingreso Ajuste Inventario
Route::resource('/regdociai', 'DocumentoInventarioController');

Route::get('/getDocuments', 'DocumentoInventarioController@getDocumentosInventario');

Route::get('/deteleDoc/{codigo}','DocumentoInventarioController@destroy');

Route::get('/createDocInv', 'DocumentoInventarioController@createDoc');

Route::get('/showDocPdf/{numero}', 'DocumentoInventarioController@show');

Route::get('/getDocPdf/{numero}', 'DocumentoInventarioController@loadNewPDF');

Route::get('/saveDetDocInv', 'DocumentoInventarioController@saveDetail');

Route::get('/searchDetDocInv', 'DocumentoInventarioController@searchDetail');

Route::get('/updateDetDocInv', 'DocumentoInventarioController@updateDetail');

Route::get('/deleteDetDocInv', 'DocumentoInventarioController@deleteDetail');

Route::get('/validProducto', 'DocumentoInventarioController@getProducto');

Route::get('/getProductoData', 'DocumentoInventarioController@getData');

// Egreso Por Venta
Route::resource('/regdocepv', 'DocumentoEgresoVentaController');

Route::get('/getDtoVent', 'DocumentoEgresoVentaController@getDocumentosEgresoVenta');

Route::get('/deteleDocVent/{codigo}','DocumentoEgresoVentaController@destroy');

Route::get('/createEgreVent', 'DocumentoEgresoVentaController@createDoc');

Route::get('/showDocPdfVent/{numero}', 'DocumentoEgresoVentaController@show');

Route::get('/getDocPdfVent/{numero}', 'DocumentoEgresoVentaController@loadNewPDF');

Route::get('/saveDetDocEgreVent', 'DocumentoEgresoVentaController@saveDetail');

Route::get('/searchDetDocEgreVent', 'DocumentoEgresoVentaController@searchDetail');

Route::get('/updateDetDocEgreVent', 'DocumentoEgresoVentaController@updateDetail');

Route::get('/deleteDetDocVent', 'DocumentoEgresoVentaController@deleteDetail');

Route::get('/validPtoVent', 'DocumentoEgresoVentaController@getProducto');

Route::get('/getPtoDataVent', 'DocumentoEgresoVentaController@getData');

// Egreso Ajuste Inventario
Route::resource('/regdoceai', 'DocumentoEgresoInventarioController');

Route::get('/getDto', 'DocumentoEgresoInventarioController@getDocumentosInventario');

Route::get('/deteleEgre/{codigo}','DocumentoEgresoInventarioController@destroy');

Route::get('/createDocEgre', 'DocumentoEgresoInventarioController@createDoc');

Route::get('/showEgrePdf/{numero}', 'DocumentoEgresoInventarioController@show');

Route::get('/getEgrePdf/{numero}', 'DocumentoEgresoInventarioController@loadNewPDF');

Route::get('/saveDetDocEgre', 'DocumentoEgresoInventarioController@saveDetail');

Route::get('/searchDetDocEgre', 'DocumentoEgresoInventarioController@searchDetail');

Route::get('/updateDetDocEgre', 'DocumentoEgresoInventarioController@updateDetail');

Route::get('/deleteDetDocEgre', 'DocumentoEgresoInventarioController@deleteDetail');

Route::get('/validPro', 'DocumentoEgresoInventarioController@getProducto');

Route::get('/getProData', 'DocumentoEgresoInventarioController@getData');

// Egreso Por Remision
Route::resource('/regdocepr', 'DocumentoEgresoRemisionController');

Route::get('/getDocRem', 'DocumentoEgresoRemisionController@getDocumentosInventario');

Route::get('/deteleEgreRem/{codigo}','DocumentoEgresoRemisionController@destroy');

Route::get('/createDocEgreRem', 'DocumentoEgresoRemisionController@createDoc');

Route::get('/showEgreRemPdf/{numero}', 'DocumentoEgresoRemisionController@show');

Route::get('/getEgreRemPdf/{numero}', 'DocumentoEgresoRemisionController@loadNewPDF');

Route::get('/saveDetDocEgreRem', 'DocumentoEgresoRemisionController@saveDetail');

Route::get('/searchDetDocEgreRem', 'DocumentoEgresoRemisionController@searchDetail');

Route::get('/updateDetDocEgreRem', 'DocumentoEgresoRemisionController@updateDetail');

Route::get('/deleteDetDocEgreRem', 'DocumentoEgresoRemisionController@deleteDetail');

Route::get('/validProRem', 'DocumentoEgresoRemisionController@getProducto');

Route::get('/getProDataRem', 'DocumentoEgresoRemisionController@getData');

// Ingreso Compra Directa
Route::resource('/regdocicd', 'DocumentoCompraDirectaController');

Route::get('/getDocCompra', 'DocumentoCompraDirectaController@getDocumentosInventario');

Route::get('/deteleDocCompra/{codigo}','DocumentoCompraDirectaController@destroy');

Route::get('/createDocCompra', 'DocumentoCompraDirectaController@createDoc');

Route::get('/showDocCompraPdf/{numero}', 'DocumentoCompraDirectaController@show');

Route::get('/getDocCompraPdf/{numero}', 'DocumentoCompraDirectaController@loadNewPDF');

Route::get('/saveDetDocCompra', 'DocumentoCompraDirectaController@saveDetail');

Route::get('/searchDetDocCompra', 'DocumentoCompraDirectaController@searchDetail');

Route::get('/updateDetDocCompra', 'DocumentoCompraDirectaController@updateDetail');

Route::get('/deleteDetDocCompra', 'DocumentoCompraDirectaController@deleteDetail');

Route::get('/validProd', 'DocumentoCompraDirectaController@getProducto');

Route::get('/getProdData', 'DocumentoCompraDirectaController@getData');

Route::get('/getIdPro', 'DocumentoCompraDirectaController@getProveedor0');

Route::get('/getNomPro', 'DocumentoCompraDirectaController@getProveedor1');

// Sede
Route::resource('/regsed', 'SedeController');

Route::get('/getDetailsSed', 'SedeController@getDetails');

// Subclase
Route::resource('/regsubc', 'SubclaseController');

Route::get('/getDetailssubc', 'SubclaseController@getDetails');

Route::get('/getSubc', 'SubclaseController@getSubclases');

// Tercero
Route::resource('/regterc', 'TerceroController');

Route::get('/getDetailsTerc', 'TerceroController@getDetails');

Route::get('/getCiudad', 'TerceroController@getCiudades');

// Ubicacion
Route::resource('/regubi', 'UbicacionController');

Route::get('/getDetailsUbi', 'UbicacionController@getDetails');