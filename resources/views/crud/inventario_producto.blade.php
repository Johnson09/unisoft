@extends('layouts.app')

@section('content')
<script>
$(document).ready(function() {
    $('#id_empresa').val('01');
    $('#cod_empresa').val('01');
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

function agregarInt(id){

    $('#newModal').modal('hide');
    $('#pro').val(id);

    $.get('{{ action('Inventario_ProductoController@getProducto') }}?id='+id, function(data) {

        $.each(data, function(index, EmpObj){
            $('#gru').val(EmpObj.id_grupo);
            var grupo = EmpObj.id_grupo;
            $.get('{{ action('ClaseController@getClases') }}?id=' + grupo, function(data) {
                        // console.log(data);
                $('#cla').empty();

                if (data == "") {
                    $('#cla').append("<option value=''>Clase</option>");
                }else{
                    $('#cla').append("<option value=''>Clase</option>");
                    $.each(data, function(index, ClassObj){
                        $('#cla').append("<option value='"+ClassObj.id_clase+"'>"+ClassObj.descripcion+"</option>");
                        // $('#ciudad').selectpicker("refresh");
                    });

                    $('#cla').val(EmpObj.id_clase);
                }
            });
            var clase = EmpObj.id_clase;
            $.get('{{ action('SubclaseController@getSubclases') }}?id=' + clase, function(data) {
                        // console.log(data);
                $('#sub').empty();

                if (data == "") {
                    $('#sub').append("<option value=''>Subclase</option>");
                }else{
                    $('#sub').append("<option value=''>Subclase</option>");
                    $.each(data, function(index, ClassObj){
                        $('#sub').append("<option value='"+ClassObj.id_subclase+"'>"+ClassObj.descripcion+"</option>");
                        // $('#ciudad').selectpicker("refresh");
                    });
                            
                    $('#sub').val(EmpObj.id_subclase);
                }
            });
        })
    })

}
</script>
<div class="container-fluid">
    <div class="row justify-content-center text-center">
        <div class="container-fluid text-center">
            <h1 style="color: #2c53c5; margin-top: -0.8em;"><b>REGISTRO DE INVENTARIO - PRODUCTO</b></h1>
        </div>
        <button class="btn btn-info" data-toggle="modal" data-target="#newModal">Añadir Producto a Inventario</button>
        <hr>

        <div class="table-responsive" style="background: #f9f9f9;">
            <table id="table_doc" class="cell-border compact stripe" style="background: #f9f9f9; font-size: 13px;">
                <thead>
                    <tr>
                        <th>EMPRESA</th>
                        <th>PRODUCTO</th>
                        <th>GRUPO</th>
                        <th>CLASE</th>
                        <th>SUBCLASE</th>
                        <th>COSTO ANTERIOR</th>
                        <th>COSTO</th>
                        <th>PRECIO VENTA</th>
                        <th>IVA</th>
                        <th>LOTE</th>
                        
                        <!-- <th></th> -->
                    </tr>
                </thead>
                
                <!-- datos obtenidos mediante consulta - mostrados en la vista de la pagina -->
                    <tbody style="text-align: center;">
                        @foreach($inventario as $inv)
                            <tr>
                                <td>{{ $inv->empresa }}</td>
                                <td>{{ $inv->producto }}</td>
                                <td>{{ $inv->grupo }}</td>
                                <td>{{ $inv->clase }}</td>
                                <td>{{ $inv->subclase }}</td>
                                <td>{{ $inv->costo_anterior }}</td>
                                <td>{{ $inv->costo }}</td>
                                <td>{{ $inv->precio_venta }}</td>
                                <td>{{ $inv->iva }}</td>
                                <td>
                                  @if($inv->sw_fv_lote == '1')
                                        Si
                                  @else
                                        No
                                  @endif
                                </td>
                                <!-- <td>
                                    <button onclick="actualizar('{{ $inv->id_clase }}')" class="btn btn-info" data-toggle="modal" data-target="#actModal">Actualizar</button>
                                </td> -->
                                <!-- <td>
                                  <a href="{{action('ClaseController@destroy', $inv->id_clase)}}" class="btn btn-warning">Eliminar</a>         
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
                    <form role="form" action="{{ url('reginvpro') }}" method="post">
                    @csrf
                        <h2>AGREGAR</h2>
                        <h3>INVENTARIO - PRODUCTO</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="1" name="id_empresa" required="required" id="cod_empresa">
                                    <option value="">Empresa</option>
                                    @foreach($empresa as $emp)
                                    <option value="{{ $emp->id_empresa }}">{{ $emp->representante_legal }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="pro" tabindex="2" name="id_producto" required="required">
                                    <option value="">Producto</option>
                                    @foreach($producto as $pro)
                                    <option value="{{ $pro->id_producto }}">{{ $pro->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="gru" data-style="btn-info" tabindex="3" name="id_grupo" required="required">
                                    <option value="">Grupo</option>
                                    @foreach($grupo as $gru)
                                    <option value="{{ $gru->id_grupo }}">{{ $gru->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="cla" data-style="btn-info" tabindex="4" name="id_clase" required="required">
                                    <option value="">Clase</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="sub" data-style="btn-info" tabindex="5" name="id_subclase" required="required">
                                    <option value="">Subclase</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="costo" placeholder="Costo" class="form-control input-lg" tabindex="7" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="precio_venta" placeholder="Precio Venta" class="form-control input-lg" tabindex="8" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="iva" placeholder="Iva" class="form-control input-lg" tabindex="9" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="10" name="sw_fv_lote" required="required">
                                    <option value="">Posee Lote?</option>
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                    </div>
      
                    <hr>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="11" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <!-- <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="12"> -->
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="newModal" class="modal fade">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content" style="background:;">
            <div class="modal-body" style="font-size: 14px">
                <div style="text-align: center;">
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="1" name="id_empresa" required="required" id="id_empresa">
                                    <option value="">Empresa</option>
                                    @foreach($empresa as $emp)
                                    <option value="{{ $emp->id_empresa }}">{{ $emp->representante_legal }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" id="producto" placeholder="Producto" class="form-control input-lg" tabindex="2" required="required" style="width: 80%; float: left;" title="Busqueda de la existencia del producto por medio del: Id del producto ó el codigo de barra del producto.">
                                <a href="#" class="btn btn-info" style="float: right;" id="consulta"><span class="fa fa-search"></span></a>
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
                
                            <!-- cargue de todos los productos que estan activos y no estan insertados en esa empresa -->
                            <tbody style="text-align: center;">
                                @foreach($existencia as $exis)
                                    <tr>
                                        <td>{{ $exis->id_producto }}</td>
                                        <td>{{ $exis->cod_barras }}</td>
                                        <td>{{ $exis->producto }}</td>
                                        <td>{{ $exis->grupo }}</td>
                                        <td>{{ $exis->clase }}</td>
                                        <td>{{ $exis->subclase }}</td>
                                        <td><button onclick="agregarInt('{{ $exis->id_producto }}');" class="btn btn-info" data-toggle="modal" data-target="#actModal" data-dismiss="#newModal">Añadir</button></td>      
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
<!-- <script src="https://code.jquery.cla/jquery-3.2.1.js"></script> -->
	<script type="text/javascript">
	 	$(".input").focus(function() {
	 		$(this).parent().addClass("focus");
	 	})
	</script>
@endsection
