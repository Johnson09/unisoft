@extends('layouts.app')

@section('content')
<script type="text/javascript">
// function pad(input, length, padding) { 
//   var str = input + "";
//   return (length <= str.length) ? str : pad(padding+str, length, padding);
// }
$(document).ready(function() {
	// $('#table_doc').bootstrapTable();
    // $('#table_doc').DataTable({
    //     "lengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "Todos"]],
    //     language: {
    //       processing: "Procesando...",
    //       search: "Buscar:",
    //       lengthMenu:    "Mostrar _MENU_ elementos",
    //       info:           "Mostrando _START_ a _END_ de _TOTAL_ elementos",
    //       infoEmpty:      "Mostrando 0 a 0 de 0 elementos",
    //       infoFiltered:   "(filtrado de _MAX_ elementos totales)",
    //       infoPostFix:    "",
    //       loadingRecords: "Cargando...",
    //       zeroRecords:    "No se encontraron registros coincidentes",
    //       emptyTable:     "No hay registros almacenados ",
    //       paginate: {
    //           first:      "Anterior",
    //           previous:   "Anterior",
    //           next:       "Siguiente",
    //           last:       "Siguiente"
    //       },
    //     }
    // });
});

function validarProducto(){
    // document.getElementById("form").action = "regprod/"+id;

    var id = $('#producto').val();
    var bod = $('#bodega').val();

    if (bod == '') {

        swal({
            title: 'Condicion!',
            text: 'Por favor selecciona una bodega.',
            icon: "info",
            buttons: "Aceptar!",
        });
        $('#bodega').focus();

    }else if(id == ''){

        swal({
            title: 'Condicion!',
            text: 'Por favor digita un producto.',
            icon: "info",
            buttons: "Aceptar!",
        });
        $('#producto').focus();

    }else{

        $.get('{{ action('DocumentoEgresoInventarioController@getProducto') }}?id='+id+'&bod='+bod, function(data) {
            
            if (data == "") {
                swal({
                    title: 'Informacion!',
                    text: 'El producto ingresado no se encuentra en bodega.',
                    icon: "info",
                    buttons: "Aceptar!",
                });
            }else {
                $.each(data, function(index, EmpObj){
                    $('#id_producto').val(EmpObj.id_producto);
                    $('#costo').val(EmpObj.costo);
                    $('#cant_exis').val(EmpObj.existencia_actual);
                    if (EmpObj.sw_fv_lote == 1) {
                        document.getElementById("f").style.display = 'block';
                        document.getElementById("l").style.display = 'block';
                    }else {
                        document.getElementById("f").style.display = 'none';
                        document.getElementById("l").style.display = 'none';
                        document.getElementById("fecha_vto").value = '';
                        document.getElementById("lote").value = '';
                    }
                })
            }
            
        });
        
    }
}

function setCantidad(){

    var cantidad = $('#cantidad').val();
    var existente = $('#cant_exis').val();

    if (existente <= cantidad) {
        $('#cantidad').val("");

        swal({
            title: 'Informacion!',
            text: 'La cantidad ingresada no puede ser mayor a la existente en bodega.',
            icon: "info",
            buttons: "Aceptar!",
        });

    }else{

    }
    
}

var i = 1;

function agregarInventario(){

    var nume = $('#numero').val();
    var bode = $('#bodega').val();
    var prod = $('#id_producto').val();
    var cant = $('#cantidad').val();
    var cost = $('#costo').val();
    var lote = $('#lote').val();
    var vto = $('#fecha_vto').val();

    $.get('{{ action('DocumentoEgresoInventarioController@searchDetail') }}?id='+nume+'&pro='+prod+'&lote='+lote, function(data) {
        
        if (data == "") {
            $.get('{{ action('DocumentoEgresoInventarioController@saveDetail') }}?id='+nume+'&bod='+bode+'&pro='+prod+'&can='+cant+'&cos='+cost+'&lot='+lote+'&vto='+vto, function(data) {
                $.each(data, function(index, RegObj){
                    $('#'+RegObj.id).closest('tr').remove();
                    $("#tableBody").append("<tr id='"+RegObj.id+"'><td>"+RegObj.id_producto+"</td><td>"+RegObj.producto+"</td><td>"+RegObj.cantidad+"</td><td>"+Intl.NumberFormat("en-en").format(RegObj.costo_und)+"</td><td>"+Intl.NumberFormat("en-en").format(RegObj.precio_venta)+"</td><td>"+RegObj.iva+"</td><td>"+RegObj.lote+"</td><td>"+RegObj.fecha_vto+"</td><td>"+Intl.NumberFormat("en-en").format(RegObj.costo_total)+"</td><td><a href='#tableBody' class='btn btn-info' onclick='eliminarInventario("+RegObj.id+")'>Eliminar</a></td></tr>");
                    
                })
            });
        }else {
            // console.log(data[0].id);
            var registro = data[0].id;
            swal({
                title: "Confirmacion!",
                text: "La anterior referencia ya se encuentra registrada. ¿Esta usted seguro que quiere adicionar esta cantidad en el registro?",
                icon: "info",
                buttons: ["Cancelar", "Aceptar"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    return fetch(`{{ action('DocumentoEgresoInventarioController@updateDetail') }}?id=`+registro+'&can='+cant+'&lot='+lote);
                }else {
                    
                }
            })
            .then(result => result.json())
            .then(json => {
                $('#'+json[0].id).closest('tr').remove();
                $("#tableBody").append("<tr id='"+json[0].id+"'><td>"+json[0].id_producto+"</td><td>"+json[0].producto+"</td><td>"+json[0].cantidad+"</td><td>"+Intl.NumberFormat("en-en").format(json[0].costo_und)+"</td><td>"+Intl.NumberFormat("en-en").format(json[0].precio_venta)+"</td><td>"+json[0].iva+"</td><td>"+json[0].lote+"</td><td>"+json[0].fecha_vto+"</td><td>"+Intl.NumberFormat("en-en").format(json[0].costo_total)+"</td><td><a href='#tableBody' class='btn btn-info' onclick='eliminarInventario("+json[0].id+")'>Eliminar</a></td></tr>");
            })
            .catch(err => {
                if (err) {
                    swal("Hubo un error!", "error");
                } else {
                    swal.stopLoading();
                    swal.close();
                }
            });
        }
        
    });

    i += 1;
}

function eliminarInventario(id_registro){

    $.get('{{ action('DocumentoEgresoInventarioController@deleteDetail') }}?id='+id_registro, function(data) {});

    $('#'+id_registro).closest('tr').remove();
    
}

function searchDoc(){
    var doc = $('#doc').val();
    var bod = $('#bod').val();
    var ini = $('#fini').val();
    var fin = $('#ffin').val();

    $("#exampleBody").empty();

    $.get('{{ action('DocumentoEgresoInventarioController@getDocumentosInventario') }}?doc='+doc+'&bod='+bod+'&ini='+ini+'&fin='+ini, function(data) {
        
        if (data == "") {
            swal({
                title: 'Informacion!',
                text: 'No se encontraron registros.',
                icon: "info",
                buttons: "Aceptar!",
            });
        }else {
            $.each(data, function(index, RegObj){
                $("#exampleBody").append("<tr><td>"+RegObj.numero+"</td><td>"+RegObj.created_at+"</td><td>"+RegObj.observaciones+"</td><td>"+RegObj.total+"</td><td><a href='showDocPdf/"+RegObj.numero+"' target='_blank' class='btn btn-success'>Consultar</a></td><td><a href='getDocPdf/"+RegObj.numero+"' target='_blank' class='btn btn-info'>Pdf</a></td></tr>");
                    
            })
        }
            
    });
}

function generarDoc(){

    $.ajax({
        url: '/Unisoft/createDocEgre',
        type: 'GET',
        data: {},
        dataType: 'JSON',
        beforeSend: function() {
        },
        error: function() {
            swal({
                title: 'Ha surgido un error!',
                text: 'No se pudo generar un Nuevo Documento.',
                showConfirmButton: false,
                type: 'error',
                timer: 2200
            });
        },
        success: function(respuesta) {
            // console.log(respuesta);
            $('#numero').val(respuesta);
        }
    });

    $("#tableBody").empty();

    $('#generarModal').modal('show');
    
}

function actualizarDoc(numero){

    // console.log(respuesta);
    $('#numero').val(numero);

    $.get('{{ action('DocumentoEgresoInventarioController@getData') }}?id='+numero, function(data) {
        
        if (data == "") {
            swal({
                title: 'Informacion!',
                text: 'No se pudo insertar el registro.',
                icon: "info",
                buttons: "Aceptar!",
            });
        }else {
            $.each(data, function(index, RegObj){
                $('#bodega').val(RegObj.id_bodega);
                $('#'+RegObj.id).closest('tr').remove();
                $("#tableBody").append("<tr id='"+RegObj.id+"'><td>"+RegObj.id_producto+"</td><td>"+RegObj.producto+"</td><td>"+RegObj.cantidad+"</td><td>"+Intl.NumberFormat("en-en").format(RegObj.costo_und)+"</td><td>"+Intl.NumberFormat("en-en").format(RegObj.precio_venta)+"</td><td>"+RegObj.iva+"</td><td>"+RegObj.lote+"</td><td>"+RegObj.fecha_vto+"</td><td>"+Intl.NumberFormat("en-en").format(RegObj.costo_total)+"</td><td><a href='#tableBody' class='btn btn-info' onclick='eliminarInventario("+RegObj.id+")'>Eliminar</a></td></tr>");
                
            })
        }
        
    });

    $('#generarModal').modal('show');
    
}
</script>
<div class="container-fluid">
    <h3 style="text-align: center;">EGRESO AJUSTE INVENTARIO</h3>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="$('#searchModal').modal('show');"><i class="fas fa-search fa-sm text-white-50"></i> CONSULTAR DOCUMENTO</a>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="generarDoc();"><i class="fas fa-download fa-sm text-white-50"></i> GENERAR DOCUMENTO</a>
    </div>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="table-responsive">
            <table id="table_doc" class="cell-border compact stripe" style="font-size: 13px;">
                <thead>
                    <tr>
                        <th>DOCUMENTO</th>
                        <th>FECHA</th>
                        <th>DESCRIPCION</th>
                        <th>VALOR</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documento as $doc)
                        <tr>
                            <td>{{ $doc->numero }}</td>
                            <td>{{ $doc->created_at }}</td>
                            <td>{{ $doc->observaciones }}</td>
                            <td>{{ $doc->total }}</td>
                            <td>
                                <button class="btn btn-info" onclick="actualizarDoc({{ $doc->numero }});">Actualizar</button>
                            </td>
                            <td>
                                <a href="{{action('DocumentoEgresoInventarioController@destroy', $doc->numero)}}" class="btn btn-warning">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="searchModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">

        <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 style="color: #000000; font-size: 1.5em;">DOCUMENTOS DE EGRESO</h4>
        </div>
        <div class="modal-body" style="font-size: 11px;">
            <div class="row">
                <div class="col-md-3 pull-center">
                    <div class="input-group">
                        <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Documento</b></div>
                        <div class="select1">
                            <input type="text" id="doc" class="form-control" placeholder="# Documento">
                        </div>
                    </div>
                </div>
                <div class="col-md-2 pull-center">
                    <div class="input-group">
                        <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Bodega</b></div>
                        <div class="select1">
                            <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="1" id="bod">
                                <option value="">Bodega</option>
                                @foreach($bodega as $bod)
                                    <option value="{{ $bod->id_bodega }}">{{ $bod->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 pull-center">
                    <div class="input-group">
                        <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Fecha Ini.</b></div>
                        <div class="select1">
                            <input type="date" id="fini" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-3 pull-center">
                    <div class="input-group">
                        <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Fecha Fin.</b></div>
                        <div class="select1">
                            <input type="date" id="ffin" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <a href="#example1" onclick="searchDoc();" class="btn btn-primary" style="margin-bottom: 0.5em; margin-top: 0.5em;"><span class="fa fa-search"></span></a>

            <table id="example1" class="table table-hover" style="background: #f9f9f9;">
                <thead>
                    <tr>
                        <th>DOCUMENTO</th>
                        <th>FECHA</th>
                        <th>DESCRIPCION</th>
                        <th>VALOR</th>
                        <th colspan="2">OPCIONES</th>
                    </tr>
                </thead>
                <tbody id="exampleBody">
                            
                </tbody>
            </table>
        </div>
    </div>
  </div>
</div>
<div id="generarModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content text-center">
        <div class="container-fluid text-center">
            <h2 style="color: #2c53c5;"><b>EGRESO POR AJUSTE FISICO DE INVENTARIO</b></h2>
        </div>
        <hr>

        <div class="modal-body" style="font-size: 14px">
            <div style="text-align: center;">
                <form role="form" action="{{ url('regdoceai') }}" method="post" id="form">
                @csrf
                <hr>
      
                <input type="hidden" name="numero" id="numero" required="required">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                        <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Bodega</b></div>
                            <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="1" id="bodega" name="id_bodega" required="required">
                                <option value="">Bodega</option>
                                @foreach($bodega as $bod)
                                    <option value="{{ $bod->id_bodega }}">{{ $bod->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                        <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Producto</b></div>
                            <input type="text" id="producto" placeholder="Producto" class="form-control input-lg" tabindex="3" style="width: 85%; float: left;" title="Busqueda de la existencia del producto por medio del: Id del producto ó el codigo de barra del producto.">
                            <a href="#" class="btn btn-info" style="float: right;" onclick="validarProducto();"><span class="fa fa-search"></span></a>
                            <input type="hidden" name="id_producto" id="id_producto">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                        <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Cantidad</b></div>
                            <input type="number" id="cantidad" name="cantidad" placeholder="Cantidad" class="form-control input-lg" tabindex="4" onkeypress="setCantidad();">
                            <input type="hidden" id="cant_exis">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                        <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Costo Unitario</b></div>
                            <input type="number" name="costo_und" id="costo" placeholder="Costo Unitario" class="form-control input-lg" tabindex="5" readOnly="readOnly">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6" id="f" style="display: none;">
                        <div class="form-group">
                        <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Fecha Vencimiento</b></div>
                            <input type="date" id="fecha_vto" name="fecha_vto" placeholder="Fecha Vencimiento" class="form-control input-lg" tabindex="6">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6" id="l" style="display: none;">
                        <div class="form-group">
                        <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Lote</b></div>
                            <input type="text" id="lote" name="lote" placeholder="Lote" class="form-control input-lg" tabindex="7">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                        <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Observaciones</b></div>
                            <textarea name="observaciones" cols="30" class="form-control input-lg" rows="5" placeholder="Observaciones" tabindex="2"></textarea>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <a href="#tableBody" class="btn btn-success" id="agregar" title="Añadir" tabindex="6" onclick="agregarInventario();">Agregar Registro</a>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="table-responsive" style="background: #f9f9f9;">
                    <table id="table_docum" class="table table-hover" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>CODIGO</th>
                                <th>DESCRIPCION</th>
                                <th>CANTIDAD</th>
                                <th>COSTO UNI.</th>
                                <th>PRECIO VENTA</th>
                                <th>IVA</th>
                                <th>LOTE</th>
                                <th>FECHA VTO.</th>
                                <th>COSTO TOT.</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            
                        </tbody>
                    </table>
                </div>

                <hr>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="9" value="Generar Documento">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="10">
                        </div>
                    </div>
                </form>
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
<!-- <script src="https://code.jquery.pro/jquery-3.2.1.js"></script> -->
	<script type="text/javascript">
	 	$(".input").focus(function() {
	 		$(this).parent().addClass("focus");
	 	})
	</script>
@endsection
