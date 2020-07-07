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

function getProveedor1(){

    var nombre = document.getElementById("nom_proveedor").value;
    $.get('{{ action('DocumentoCompraDirectaController@getProveedor1') }}?nom='+nombre, function(data) {
        //console.log(data);
        $('#proveedor').val("");

        $.each(data, function(index, prObj){
            $('#proveedor').val(prObj.id_tercero);
        })
    });
    
}

function getProveedor2(){

    var proveedor = document.getElementById("proveedor").value;
    $.get('{{ action('DocumentoCompraDirectaController@getProveedor0') }}?id='+proveedor, function(data) {
        //console.log(data);
        $('#nom_proveedor').val("");

        $.each(data, function(index, prObj){
            $('#nom_proveedor').val(prObj.nombre);
        })
    });
    
}

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

        $.get('{{ action('DocumentoCompraDirectaController@getProducto') }}?id='+id+'&bod='+bod, function(data) {
            
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
                    $('#costo_a').val(EmpObj.costo);
                    if (EmpObj.sw_fv_lote == 1) {
                        document.getElementById("f").style.display = 'block';
                        document.getElementById("l").style.display = 'block';
                        $('#lote').val(EmpObj.lote);
                        $('#fecha_vto').val(EmpObj.fecha_vto);
                    }else {
                        document.getElementById("f").style.display = 'none';
                        document.getElementById("l").style.display = 'none';
                        $('#lote').val('');
                        $('#fecha_vto').val('');
                    }
                })
            }
            
        });
        
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

    $.get('{{ action('DocumentoCompraDirectaController@searchDetail') }}?id='+nume+'&pro='+prod+'&lote='+lote, function(data) {
        
        if (data == "") {
            $.get('{{ action('DocumentoCompraDirectaController@saveDetail') }}?id='+nume+'&bod='+bode+'&pro='+prod+'&can='+cant+'&cos='+cost+'&lot='+lote+'&vto='+vto, function(data) {
                    $.each(data, function(index, RegObj){
                        $('#'+RegObj.id).closest('tr').remove();
                        $("#tableBody").append("<tr id='"+RegObj.id+"'><td>"+RegObj.id_producto+"</td><td>"+RegObj.producto+"</td><td>"+RegObj.cantidad+"</td><td>"+Intl.NumberFormat("en-en").format(RegObj.costo_actual)+"</td><td>"+Intl.NumberFormat("en-en").format(RegObj.precio_venta)+"</td><td>"+RegObj.iva+"</td><td>"+RegObj.lote+"</td><td>"+RegObj.fecha_vto+"</td><td>"+Intl.NumberFormat("en-en").format(RegObj.costo_compra)+"</td><td><a href='#tableBody' class='btn btn-info' onclick='eliminarInventario("+RegObj.id+")'>Eliminar</a></td></tr>");
                        
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
                    return fetch(`{{ action('DocumentoCompraDirectaController@updateDetail') }}?id=`+registro+'&can='+cant+'&lot='+lote);
                }else {
                    
                }
            })
            .then(result => result.json())
            .then(json => {
                $('#'+json[0].id).closest('tr').remove();
                $("#tableBody").append("<tr id='"+json[0].id+"'><td>"+json[0].id_producto+"</td><td>"+json[0].producto+"</td><td>"+json[0].cantidad+"</td><td>"+Intl.NumberFormat("en-en").format(json[0].costo_actual)+"</td><td>"+Intl.NumberFormat("en-en").format(json[0].precio_venta)+"</td><td>"+json[0].iva+"</td><td>"+json[0].lote+"</td><td>"+json[0].fecha_vto+"</td><td>"+Intl.NumberFormat("en-en").format(json[0].costo_compra)+"</td><td><a href='#tableBody' class='btn btn-info' onclick='eliminarInventario("+json[0].id+")'>Eliminar</a></td></tr>");
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

    $('#id_producto').val('');
    $('#cantidad').val('');
    $('#costo').val('');
    $('#lote').val('');
    $('#fecha_vto').val('');

    i += 1;
}

function eliminarInventario(id_registro){

    $.get('{{ action('DocumentoCompraDirectaController@deleteDetail') }}?id='+id_registro, function(data) {});

    $('#'+id_registro).closest('tr').remove();
    
}

function searchDoc(){

    var doc = $('#doc').val();
    var bod = $('#bod').val();
    var ini = $('#fini').val();
    var fin = $('#ffin').val();

    $("#exampleBody").empty();

    $.get('{{ action('DocumentoCompraDirectaController@getDocumentosInventario') }}?doc='+doc+'&bod='+bod+'&ini='+ini+'&fin='+ini, function(data) {
        
        if (data == "") {
            swal({
                title: 'Informacion!',
                text: 'No se encontraron registros.',
                icon: "info",
                buttons: "Aceptar!",
            });
        }else {
            $.each(data, function(index, RegObj){
                $("#exampleBody").append("<tr><td>"+RegObj.numero+"</td><td>"+RegObj.created_at+"</td><td>"+RegObj.observaciones+"</td><td>"+RegObj.total+"</td><td><a href='showDocCompraPdf/"+RegObj.numero+"' target='_blank' class='btn btn-success'>Consultar</a></td><td><a href='getDocCompraPdf/"+RegObj.numero+"' target='_blank' class='btn btn-info'>Pdf</a></td></tr>");
                    
            })
        }
            
    });
}

function generarDoc(){

    document.getElementById("encabezado").style.display = 'block';
    document.getElementById("detalle").style.display = 'none';

    $("#tableBody").empty();

    $('#generarModal').modal('show');
    
}

function actualizarDoc(numero){

    document.getElementById("encabezado").style.display = 'block';
    document.getElementById("detalle").style.display = 'none';

    // console.log(respuesta);
    $('#numero').val(numero);

    $.get('{{ action('DocumentoCompraDirectaController@getData') }}?id='+numero, function(data) {
        
        if (data == "") {
            swal({
                title: 'Informacion!',
                text: 'No se pudo insertar el registro.',
                icon: "info",
                buttons: "Aceptar!",
            });
        }else {
            $.each(data[0], function(index, HeadObj){
                $('#bodega').val(HeadObj.id_bodega);
                $('#tipo_p').val(HeadObj.tipo_id_proveedor);
                $('#proveedor').val(HeadObj.id_proveedor);
                $('#nom_proveedor').val(HeadObj.nombre);
                $('#prefijo').val(HeadObj.prefijo_factura);
                $('#factura').val(HeadObj.nro_factura);
                $('#fecha_r').val(HeadObj.fecha_factura);
                $('#observaciones').val(HeadObj.observaciones);
                
            })

            $.each(data[1], function(index, RegObj){
                $('#bodega').val(RegObj.id_bodega);
                $('#'+RegObj.id).closest('tr').remove();
                $("#tableBody").append("<tr id='"+RegObj.id+"'><td>"+RegObj.id_producto+"</td><td>"+RegObj.producto+"</td><td>"+RegObj.cantidad+"</td><td>"+Intl.NumberFormat("en-en").format(RegObj.costo_actual)+"</td><td>"+Intl.NumberFormat("en-en").format(RegObj.precio_venta)+"</td><td>"+RegObj.iva+"</td><td>"+RegObj.lote+"</td><td>"+RegObj.fecha_vto+"</td><td>"+Intl.NumberFormat("en-en").format(RegObj.costo_compra)+"</td><td><a href='#tableBody' class='btn btn-info' onclick='eliminarInventario("+RegObj.id+")'>Eliminar</a></td></tr>");
                
            })
        }
        
    });

    $('#generarModal').modal('show');
    
}

function avaibleDetail(){

    var num = $('#numero').val();
    var bod = $('#bodega').val();
    var tip = $('#tipo_p').val();
    var pro = $('#proveedor').val();
    var pre = $('#prefijo').val();
    var fac = $('#factura').val();
    var fec = $('#fecha_r').val();
    var obs = $('#observaciones').val();

    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth()+1;
    var yyyy = hoy.getFullYear();
    var fecha_actual = '';

    if (mm < 10) {
        fecha_actual = yyyy+'-0'+mm+'-'+dd;
    }else if(dd < 10) {
        fecha_actual = yyyy+'-'+mm+'-0'+dd;
    }else if(mm < 10 && dd < 10) {
        fecha_actual = yyyy+'-0'+mm+'-0'+dd;
    }else{
        fecha_actual = yyyy+'-'+mm+'-'+dd;
    }
    // console.log(fec, fecha_actual);

    if (fec < fecha_actual) {
        swal({
            title: 'Parametros invalidos!',
            text: 'La fecha de la factura no puede ser anterior a la fecha actual.',
            showConfirmButton: true,
            icon: 'error',
            // timer: 2200
        });
    }else{
        $.ajax({
            url: '/Unisoft/createDocCompra',
            type: 'GET',
            data: {num: num, bod: bod, tip: tip, pro: pro, pre: pre, fac: fac, fec: fec, obs: obs},
            dataType: 'JSON',
            beforeSend: function() {
            },
            error: function() {
                swal({
                    title: 'Ha surgido un error!',
                    text: 'No se pudo generar un Nuevo Documento.',
                    showConfirmButton: false,
                    icon: 'error',
                    // timer: 2200
                });
            },
            success: function(respuesta) {

                document.getElementById("encabezado").style.display = 'none';
                document.getElementById("detalle").style.display = 'block';
                $('#numero').val(respuesta);
            }
        });
    }

}

function cancel(){

    $('#generarModal').modal('hide');
    
}

function backHead(){

    document.getElementById("encabezado").style.display = 'block';
    document.getElementById("detalle").style.display = 'none';
    
}
</script>
<div class="container-fluid">
    <h3 style="text-align: center;">INGRESO COMPRA DIRECTA</h3>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="$('#searchModal').modal('show');"><i class="fas fa-search fa-sm text-white-50"></i> CONSULTAR DOCUMENTO COMPRA</a>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="generarDoc();"><i class="fas fa-download fa-sm text-white-50"></i> GENERAR DOCUMENTO COMPRA</a>
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
                                <a href="{{action('DocumentoCompraDirectaController@destroy', $doc->numero)}}" class="btn btn-warning">
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
            <h4 style="color: #000000; font-size: 1.5em;">DOCUMENTOS DE INGRESO POR COMPRA</h4>
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
                <h2 style="color: #2c53c5;"><b>INGRESO POR COMPRA DIRECTA</b></h2>
            </div>
        <!-- <hr> -->

        <div class="modal-body" style="font-size: 14px">
            <div style="text-align: center;">
                <form role="form" action="{{ url('regdocicd') }}" method="post" id="form">
                @csrf
                <hr>
      
                <input type="hidden" name="numero" id="numero" required="required">
                <div id="encabezado">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
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
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Tipo Proveedor</b></div>
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="1" id="tipo_p" name="tipo_id_proveedor" required="required">
                                    <option value="">Tipo Proveedor</option>
                                    @foreach($tipo as $tip)
                                        <option value="{{ $tip->tipo_id_tercero }}">{{ $tip->tipo_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b># Proveedor</b></div>
                                <input type="text" name="id_proveedor" id="proveedor" placeholder="# Proveedor" class="form-control input-lg" tabindex="5" onkeyup="getProveedor2();">                     
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Proveedor</b></div>
                                <input type="text" name="nom_proveedor" id="nom_proveedor" placeholder="Nombre Proveedor" class="form-control input-lg" tabindex="5" onkeyup="getProveedor1();">                    
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Prefijo Factura</b></div>
                                <input type="text" name="prefijo" id="prefijo" placeholder="Prefijo Factura" class="form-control input-lg" tabindex="5">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>N° Factura</b></div>
                                <input type="number" id="factura" name="factura" placeholder="N° Factura" class="form-control input-lg" tabindex="4">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Fecha Factura</b></div>
                                <input type="date" name="fecha_r" id="fecha_r" class="form-control input-lg" tabindex="5">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-8 col-sm-8 col-md-8">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Observaciones</b></div>
                                <textarea name="observaciones" id="observaciones" cols="20" class="form-control input-lg" rows="5" placeholder="Observaciones" tabindex="2"></textarea>
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <a href="#" class="btn btn-info btn-block btn-lg" onclick="avaibleDetail()">Siguiente</a>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="10" onclick="cancel()">
                        </div>
                    </div>
                <hr>
                </div>

                <div id="detalle" style="display: none;">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Producto</b></div>
                                <input type="text" id="producto" placeholder="Producto" class="form-control input-lg" tabindex="3" style="width: 85%; float: left;" title="Busqueda de la existencia del producto por medio del: Id del producto ó el codigo de barra del producto.">
                                <a href="#" class="btn btn-info" style="float: right;" onclick="validarProducto();"><span class="fa fa-search"></span></a>
                                <input type="hidden" name="id_producto" id="id_producto">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <a href="#tableBody" class="btn btn-success" id="agregar" title="Añadir" tabindex="6" onclick="agregarInventario();">Agregar Registro</a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Cantidad</b></div>
                                <input type="number" id="cantidad" name="cantidad" placeholder="Cantidad" class="form-control input-lg" tabindex="4">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Costo Actual</b></div>
                                <input type="number" name="costo_actual" id="costo_a" placeholder="Costo Actual" class="form-control input-lg" tabindex="5" readOnly="readOnly">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Costo Compra</b></div>
                                <input type="number" name="costo_compra" id="costo" placeholder="Costo Compra" class="form-control input-lg" tabindex="5">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4" id="f" style="display: none;">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Fecha Vencimiento</b></div>
                                <input type="date" id="fecha_vto" name="fecha_vto" placeholder="Fecha Vencimiento" class="form-control input-lg" tabindex="6">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4" id="l" style="display: none;">
                            <div class="form-group">
                            <div class="input-group-addon" style="margin-right: 4em; color: #ffcc00;"><b>Lote</b></div>
                                <input type="text" id="lote" name="lote" placeholder="Lote" class="form-control input-lg" tabindex="7">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" style="background: #f9f9f9;">
                        <table id="table_docum" class="table table-hover" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>CODIGO</th>
                                    <th>DESCRIPCION</th>
                                    <th>CANTIDAD</th>
                                    <th>COSTO ACT.</th>
                                    <th>PRECIO VTA.</th>
                                    <th>IVA</th>
                                    <th>LOTE</th>
                                    <th>FECHA VTO.</th>
                                    <th>COSTO VTA.</th>
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
                            <a href="#" class="btn btn-warning btn-block btn-lg" onclick="backHead()">Volver</a>
                        </div>
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
