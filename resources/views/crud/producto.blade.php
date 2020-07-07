@extends('layouts.app')

@section('content')
<script type="text/javascript">
function pad(input, length, padding) { 
  var str = input + "";
  return (length <= str.length) ? str : pad(padding+str, length, padding);
}
function actualizar(id){
    document.getElementById("form").action = "regprod/"+id;

    $.get('{{ action('ProductController@getDetails') }}?id='+id, function(data) {
        $('#cod').empty();
        $('#bar').empty();
        $('#des').empty();
        $('#cuv').empty();

        $.each(data, function(index, EmpObj){

            $('#cod').val(EmpObj.id_producto);
            $('#bar').val(EmpObj.cod_barras);
            $('#gru').val(EmpObj.id_grupo);

            $.get('{{ action('ClaseController@getClases') }}?id=' + EmpObj.id_grupo, function(data) {
                    // console.log(data);
                $('#cla').empty();

                if (data == "") {
                    $('#cla').append("<option value=''>Clase</option>");
                }else{
                    $('#cla').append("<option value=''>Clase</option>");
                    $.each(data, function(index, ClassObj){
                        $('#cla').append("<option value='"+ClassObj.id_clase+"'>"+ClassObj.descripcion+"</option>");
                            // $('#ciudad').selectpicker("refresh");
                    })
                }
                $('#cla').val(EmpObj.id_clase);
            });

            $.get('{{ action('SubclaseController@getSubclases') }}?id=' + EmpObj.id_clase, function(data) {
                    // console.log(data);
                $('#sub').empty();

                if (data == "") {
                    $('#sub').append("<option value=''>Subclase</option>");
                }else{
                    $('#sub').append("<option value=''>Subclase</option>");
                    $.each(data, function(index, ClassObj){
                        $('#sub').append("<option value='"+ClassObj.id_subclase+"'>"+ClassObj.descripcion+"</option>");
                            // $('#ciudad').selectpicker("refresh");
                    })
                }
                $('#sub').val(EmpObj.id_subclase);
            });

            $('#des').val(EmpObj.descripcion);
            $('#und').val(EmpObj.id_und_med);
            $('#pro').val(EmpObj.id_proveedor);
            $('#mar').val(EmpObj.id_marca);
            $('#iva').val(EmpObj.sw_iva);
            $('#est').val(EmpObj.sw_estado);
            $('#ven').val(EmpObj.und_venta);
            $('#cuv').val(EmpObj.cant_und_venta);
        
        })
    })
}
</script>
<div class="container-fluid">
    <div class="row justify-content-center text-center">
        <div class="container-fluid text-center">
            <h1 style="color: #2c53c5; margin-top: -0.8em;"><b>REGISTRO DE PRODUCTO</b></h1>
        </div>
        <button class="btn btn-info" data-toggle="modal" data-target="#newModal">Añadir Producto</button>
        <hr>

        <div class="table-responsive" style="background: #f9f9f9;">
            <table id="table_doc" class="cell-border compact stripe" style="background: #f9f9f9; font-size: 12px;">
                <thead>
                    <tr>
                        <th>SERIAL</th>
                        <th>COD. BARRA</th>
                        <th>GRUPO</th>
                        <th>CLASE</th>
                        <th>SUBCLASE</th>
                        <th>DESCRIPCION</th>
                        <th>UND. MEDIDA</th>
                        <th>PROVEEDOR</th>
                        <th>MARCA</th>
                        <th>IVA</th>
                        <th>ACTIVO</th>
                        <th>UND. VENTA</th>
                        <th>CANT. VENTA</th>
                        <th></th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                
                <!-- datos obtenidos mediante consulta - mostrados en la vista de la pagina -->
                    <tbody style="text-align: center;">
                        @foreach($product as $pro)
                            <tr>
                                <td>{{ $pro->id_producto }}</td>
                                <td>{{ $pro->cod_barras }}</td>
                                <td>{{ $pro->grupo }}</td>
                                <td>{{ $pro->clase }}</td>
                                <td>{{ $pro->subclase }}</td>
                                <td>{{ $pro->descripcion }}</td>
                                <td>{{ $pro->medida }}</td>
                                <td>{{ $pro->proveedor }}</td>
                                <td>{{ $pro->marca }}</td>
                                <td>
                                  @if($pro->sw_iva == '1')
                                      <input type="checkbox" checked="checked" disabled="disabled">
                                  @else
                                    <input type="checkbox" disabled="disabled">
                                  @endif
                                </td>
                                <td>
                                  @if($pro->sw_estado == '1')
                                      <input type="checkbox" checked="checked" disabled="disabled">
                                  @else
                                    <input type="checkbox" disabled="disabled">
                                  @endif
                                </td>
                                <td>{{ $pro->unidad_venta }}</td>
                                <td>{{ $pro->cant_und_venta }}</td>
                                <td>
                                    <button onclick="actualizar('{{ $pro->id_producto }}')" class="btn btn-info" data-toggle="modal" data-target="#actModal">Actualizar</button>
                                </td>
                                <!-- <td>
                                  <a href="{{action('ProductController@destroy', $pro->id_producto)}}" class="btn btn-warning">Eliminar</a>         
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
                        <h3>PRODUCTO</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_producto" id="cod" placeholder="Id Producto" class="form-control input-lg" tabindex="1" required="required" readonly="readonly">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="cod_barras" id="bar" placeholder="Codigo Barra" class="form-control input-lg" tabindex="2" required="required">
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
                            <div class="form-group">
                                <input type="text" name="descripcion" id="des" placeholder="Descripcion" class="form-control input-lg" tabindex="6" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="und" data-style="btn-info" tabindex="7" name="id_und_med" required="required">
                                    <option value="">Unidad Medida</option>
                                    @foreach($unidad as $und)
                                    <option value="{{ $und->id_und_med }}">{{ $und->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="pro" data-style="btn-info" tabindex="8" name="id_proveedor" required="required">
                                    <option value="">Proveedor</option>
                                    @foreach($proveedor as $pro)
                                    <option value="{{ $pro->id_proveedor }}">{{ $pro->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="mar" data-style="btn-info" tabindex="9" name="id_marca" required="required">
                                    <option value="">Marca</option>
                                    @foreach($marca as $mar)
                                    <option value="{{ $mar->id_marca }}">{{ $mar->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="iva" data-style="btn-info" tabindex="10" name="sw_iva" required="required">
                                    <option value="">Iva</option>
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="est" data-style="btn-info" tabindex="11" name="sw_estado" required="required">
                                    <option value="">Estado</option>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="12" name="und_venta" required="required" id="ven">
                                    <option value="">Unidad Venta</option>
                                    @foreach($venta as $vent)
                                    <option value="{{ $vent->uid_und_venta }}">{{ $vent->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="cant_und_venta" placeholder="Cant. Und. Venta" id="cuv" class="form-control input-lg" tabindex="13" required="required">
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
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="14" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <!-- <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="15"> -->
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
                    <form role="form" action="{{ url('regprod') }}" method="post">
                    @csrf
                        <h2>AGREGAR</h2>
                        <h3>PRODUCTO</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_producto" id="producto" placeholder="Id Producto" class="form-control input-lg" tabindex="1" readonly="readonly" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="cod_barras" placeholder="Codigo Barra" class="form-control input-lg" tabindex="2" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="3" id="grupo" name="id_grupo" required="required">
                                    <option value="">Grupo</option>
                                    @foreach($grupo as $gru)
                                    <option value="{{ $gru->id_grupo }}">{{ $gru->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="4" id="clase" name="id_clase" required="required">
                                    <option value="">Clase</option>
                                </select>
                                <script type="text/javascript">
                                    $('#grupo').on('change', function(e){
                                        var grupo = e.target.value;
                                        $.get('{{ action('ClaseController@getClases') }}?id=' + grupo, function(data) {
                                            // console.log(data);
                                            $('#clase').empty();

                                            if (data == "") {
                                                $('#clase').append("<option value=''>Clase</option>");
                                            }else{
                                                $('#clase').append("<option value=''>Clase</option>");
                                                $.each(data, function(index, ClassObj){
                                                    $('#clase').append("<option value='"+ClassObj.id_clase+"'>"+ClassObj.descripcion+"</option>");
                                                    // $('#ciudad').selectpicker("refresh");
                                                })
                                            }
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="5" id="subclase" name="id_subclase" required="required">
                                    <option value="">Subclase</option>
                                </select>
                                <script type="text/javascript">
                                    $('#clase').on('change', function(e){
                                        var clase = e.target.value;
                                        $.get('{{ action('SubclaseController@getSubclases') }}?id=' + clase, function(data) {
                                            // console.log(data);
                                            $('#subclase').empty();

                                            if (data == "") {
                                                $('#subclase').append("<option value=''>Subclase</option>");
                                            }else{
                                                $('#subclase').append("<option value=''>Subclase</option>");
                                                $.each(data, function(index, ClassObj){
                                                    $('#subclase').append("<option value='"+ClassObj.id_subclase+"'>"+ClassObj.descripcion+"</option>");
                                                    // $('#ciudad').selectpicker("refresh");
                                                })
                                            }
                                        });
                                    });

                                    $('#subclase').on('change', function(e){
                                        var grupo = $('#grupo').val();
                                        var clase = $('#clase').val();
                                        var subclase = e.target.value;
                                        var concat = grupo+clase+subclase;
                                        $.get('{{ action('ProductController@getId') }}?id=' + concat, function(data) {
                                        // console.log(concat);
                                            $('#producto').val();

                                            var cod = 0;
                                            if (data == "") {
                                                cod = 1;
                                            }else{
                                                $.each(data, function(index, ClassObj){
                                                    var id = ClassObj.id_producto;
                                                    cod = parseInt(id.substr(6, 4)) + 1;
                                                })
                                            }
                                            // console.log(cod);
                                            var result = pad(cod, 4, 0);
                                            // console.log(result);
                                            $('#producto').val(concat+result);
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="descripcion" placeholder="Descripcion" class="form-control input-lg" tabindex="6" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="7" name="id_und_med" required="required">
                                    <option value="">Unidad Medida</option>
                                    @foreach($unidad as $und)
                                    <option value="{{ $und->id_und_med }}">{{ $und->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="8" name="id_proveedor" required="required">
                                    <option value="">Proveedor</option>
                                    @foreach($proveedor as $pro)
                                    <option value="{{ $pro->id_proveedor }}">{{ $pro->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="9" name="id_marca" required="required">
                                    <option value="">Marca</option>
                                    @foreach($marca as $mar)
                                    <option value="{{ $mar->id_marca }}">{{ $mar->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="10" name="sw_iva" required="required">
                                    <option value="">Iva</option>
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="11" name="und_venta" required="required">
                                    <option value="">Unidad Venta</option>
                                    @foreach($venta as $vent)
                                    <option value="{{ $vent->uid_und_venta }}">{{ $vent->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="cant_und_venta" placeholder="Cant. Und. Venta" class="form-control input-lg" tabindex="12" required="required">
                            </div>
                        </div>
                    </div>
      
                    <hr>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="13" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <!-- <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="14"> -->
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
<!-- <script src="https://code.jquery.pro/jquery-3.2.1.js"></script> -->
	<script type="text/javascript">
	 	$(".input").focus(function() {
	 		$(this).parent().addClass("focus");
	 	})
	</script>
@endsection
