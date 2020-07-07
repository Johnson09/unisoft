@extends('layouts.app')

@section('content')
<script>
$(document).ready(function() {
    var table = $('#table_doc_reg').DataTable({
        "lengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "Todos"]],
        language: {
            processing: "Procesando...",
            search: "Buscar:",
            lengthMenu:    "Mostrar _MENU_ elementos",
            info:           "Mostrando _START_ a _END_ de _TOTAL_ elementos",
            infoEmpty:      "Mostrando 0 a 0 de 0 elementos",
            infoFiltered:   "(filtrado de _MAX_ elementos totales)",
            infoPostFix:    "",
            loadingRecords: "Cargando...",
            zeroRecords:    "No se encontraron registros coincidentes",
            emptyTable:     "No hay registros almacenados ",
            paginate: {
                first:      "Anterior",
                previous:   "Anterior",
                next:       "Siguiente",
                last:       "Siguiente"
            },
        }
    });

    $('#producto').on('change keyup', function(e){
        var consulta = e.target.value;
        table.search( consulta ).draw();
    });

    $('#consulta').on('click', function(e){
        var consulta = $('#producto').val();
        table.search( consulta ).draw();
    });
});

function agregar(id_bodega){

    $('#id_bodega').val(id_bodega);
    $('#bodega').val(id_bodega);

}

function agregarProducto(id_producto){
    
    $('#id_producto').val(id_producto);
    $('#proModal').modal('hide');

}

function actualizar(id){
    document.getElementById("form").action = "regbod/"+id;

    $.get('{{ action('BodegaController@getDetails') }}?id='+id, function(data) {
        $('#cod').empty();
        $('#des').empty();
	    $('#dir').empty();

        $.each(data, function(index, EmpObj){

            $('#cod').val(EmpObj.id_bodega);
            $('#cos').val(EmpObj.id_centro_costo);
            $('#des').val(EmpObj.descripcion);
            $('#dir').val(EmpObj.direccion);
            $('#est').val(EmpObj.sw_estado);
	    
        })
    })
}

function validarProducto(){

    var id = $('#producto').val();
    var bod = $('#id_bodega').val();

    $.get('{{ action('BodegaController@getProducto') }}?id='+id+'&bod='+bod, function(data) {
        
        $.each(data, function(index, EmpObj){
            var producto = EmpObj.id_producto;
            if (producto != null) {
                $('#id_producto').val(EmpObj.id_producto);
                swal({
                    title: 'Informacion!',
                    text: 'El producto ingresado no se encuentra en bodega.',
                    icon: "info",
                    buttons: "Aceptar!",
                });
            }else {
                swal({
                    title: 'Informacion!',
                    text: 'El producto ingresado ya se encuentra en bodega.',
                    icon: "info",
                    buttons: "Aceptar!",
                });
            }
        });
    });
}
</script>
<div class="container-fluid">
    <div class="row justify-content-center text-center">
        <div class="container-fluid text-center">
            <h1 style="color: #2c53c5; margin-top: -0.8em;"><b>REGISTRO DE BODEGA</b></h1>
        </div>
        <button class="btn btn-info" data-toggle="modal" data-target="#newModal">Añadir Bodega</button>
        <hr>

        <div class="table-responsive" style="background: #f9f9f9;">
            <table id="table_doc" class="cell-border compact stripe" style="background: #f9f9f9; font-size: 13px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>CENTRO COSTO</th>
                        <th>DESCRIPCION</th>
                        <th>DIRECCION</th>
                        <th>ESTADO</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                
                <!-- datos obtenidos mediante consulta - mostrados en la vista de la pagina -->
                    <tbody style="text-align: center;">
                        @foreach($bodega as $bod)
                            <tr>
                                <td>{{ $bod->id_bodega }}</td>
                                <td>{{ $bod->costo }}</td>
                                <td>{{ $bod->descripcion }}</td>
                                <td>{{ $bod->direccion }}</td>
                                <td>
                                  @if($bod->sw_estado == '1')
                                        Activo
                                  @else
                                        Inactivo
                                  @endif
                                </td>
                                <td>
                                    <button onclick="actualizar('{{ $bod->id_bodega }}')" class="btn btn-info" data-toggle="modal" data-target="#actModal">Actualizar</button>
                                </td>
                                <td>
                                    <button onclick="agregar('{{ $bod->id_bodega }}')" class="btn btn-info" data-toggle="modal" data-target="#proModal">Agregar Producto</button>
                                </td>
                                <!-- <td>
                                  <a href="{{action('BodegaController@destroy', $bod->id_bodega)}}" class="btn btn-warning">Eliminar</a>         
                                </td> -->
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div id="actModal" class="modal fade">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="background:;">
            <div class="modal-body" style="font-size: 14px">
                <div style="text-align: center;">
                    <form role="form" action="#" method="post" id="form">
                    @method('PATCH')
                    @csrf
                        <h2>ACTUALIZAR</h2>
                        <h3>BODEGA</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_bodega" id="cod" placeholder="Id Bodega" readonly="readonly" class="form-control input-lg" tabindex="1" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="cos" data-style="btn-info" tabindex="2" name="id_centro_costo" required="required">
                                    <option value="">Centro Costo</option>
                                    @foreach($costo as $cos)
                                    <option value="{{ $cos->id_centro_costo }}">{{ $cos->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="descripcion" id="des" placeholder="Descripcion" class="form-control input-lg" tabindex="3" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="direccion" placeholder="Direccion" id="dir" class="form-control input-lg" tabindex="4" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="est" data-style="btn-info" tabindex="5" name="sw_estado" required="required">
                                    <option value="">Estado</option>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                
                            </div>
                        </div>
                    </div>
      
                    <hr>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="6" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <!-- <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="7"> -->
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="proModal" class="modal fade">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content" style="background:;">
            <div class="modal-body" style="font-size: 14px">
                <div style="text-align: center;">
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="1" id="id_bodega" name="id_bodega" readOnly="readOnly" required="required">
                                    <option value="">Bodega</option>
                                    @foreach($bodega as $bod)
                                    <option value="{{ $bod->id_bodega }}">{{ $bod->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" id="producto" placeholder="Producto" class="form-control input-lg" tabindex="2" required="required" style="width: 80%; float: left;" title="Busqueda de la existencia del producto por medio del: Id del producto ó el codigo de barra del producto.">
                                <a href="#" class="btn btn-info" style="float: right;" onclick="validarProducto();"><span class="fa fa-search"></span></a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" style="background: #f9f9f9;">
                        <table id="table_doc_reg" class="cell-border compact stripe" style="background: #f9f9f9; font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>ID PRODUCTO</th>
                                    <th>COD BARRAS</th>
                                    <th>DESCRIPCION</th>
                                    <th>GRUPO</th>
                                    <th>CLASE</th>
                                    <th>SUBCLASE</th>
                                    <th>ACCION</th>
                                </tr>
                            </thead>
                
                            <!-- cargue de todos los productos que estan activos y no estan insertados en existencia bodega -->
                            <tbody style="text-align: center;">
                                @foreach($existencia as $exis)
                                    <tr>
                                        <td>{{ $exis->id_producto }}</td>
                                        <td>{{ $exis->cod_barras }}</td>
                                        <td>{{ $exis->producto }}</td>
                                        <td>{{ $exis->grupo }}</td>
                                        <td>{{ $exis->clase }}</td>
                                        <td>{{ $exis->subclase }}</td>
                                        <td><button onclick="agregarProducto('{{ $exis->id_producto }}');" class="btn btn-info" data-toggle="modal" data-target="#newProModal">Añadir</button></td>      
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="newProModal" class="modal fade">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="background:;">
            <div class="modal-body" style="font-size: 14px">
                <div style="text-align: center;">
                    <form role="form" action="{{ url('regexisbod') }}" method="post">
                    @csrf
                        <h2>AGREGAR</h2>
                        <h3>EXISTENCIA - BODEGA</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="1" id="bodega" name="id_bodega" readOnly="readOnly" required="required">
                                    <option value="">Bodega</option>
                                    @foreach($bodega as $bod)
                                    <option value="{{ $bod->id_bodega }}">{{ $bod->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_producto" id="id_producto" placeholder="Producto" class="form-control input-lg" tabindex="2" required="required" style="width: 80%; float: left;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="existencia_minima" placeholder="Existencia Minima" class="form-control input-lg" tabindex="3" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="existencia_maxima" placeholder="Existencia Maxima" class="form-control input-lg" tabindex="4" required="required">
                            </div>
                        </div>
                    </div>
      
                    <hr>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="5" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <!-- <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="6"> -->
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="newModal" class="modal fade">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="background:;">
            <div class="modal-body" style="font-size: 14px">
                <div style="text-align: center;">
                    <form role="form" action="{{ url('regbod') }}" method="post">
                    @csrf
                        <h2>AGREGAR</h2>
                        <h3>BODEGA</h3>
                        <hr>
      
                        <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="descripcion" placeholder="Descripcion" class="form-control input-lg" tabindex="1" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="2" name="id_centro_costo" required="required">
                                    <option value="">Centro Costo</option>
                                    @foreach($costo as $cos)
                                    <option value="{{ $cos->id_centro_costo }}">{{ $cos->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="direccion" placeholder="Direccion" class="form-control input-lg" tabindex="3" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                            </div>
                        </div>
                    </div>
      
                    <hr>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="5" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <!-- <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="6"> -->
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.inputBox{
	position: relative;
	box-sizing: border-box;
	margin-bottom: 50px;
}
.inputBox .inputText{
	position: absolute;
    font-size: 20px;
    line-height: 50px;
    transition: .5s;
    opacity: .5;
}
.inputBox .input{
	position: relative;
	width: 100%;
	height: 50px;
	background: transparent;
	border: none;
    outline: none;
    font-size: 20px;
    border-bottom: 1px solid rgba(0,0,0,.5);

}
.focus .inputText{
	transform: translateY(-30px);
	font-size: 18px;
	opacity: 1;
	color: #00bcd4;

}
.button{
	width: 100%;
    height: 50px;
    border: none;
    outline: none;
    background: #03A9F4;
    color: #fff;
}
</style>
<!-- <script src="https://code.jquery.ope/jquery-3.2.1.js"></script> -->
	<script type="text/javascript">
	 	$(".input").focus(function() {
	 		$(this).parent().addClass("focus");
	 	})
	</script>
@endsection
