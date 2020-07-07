@extends('layouts.app')

@section('content')
<!-- <script>
function actualizar(id){
    document.getElementById("form").action = "regbodubi/"+id;

    $.get('?id='+id, function(data) {
        $('#cod').empty();
	    $('#des').empty();

        $.each(data, function(index, EmpObj){

            $('#cod').val(EmpObj.id_caja);
            $('#des').val(EmpObj.descripcion);
            $('#se').val(EmpObj.id_sede);
            $('#co').val(EmpObj.id_centro_costo);
            $('#tip').val(EmpObj.tipo_venta);
            $('#est').val(EmpObj.estado);
	    
        })
    })
}
</script> -->
<div class="container-fluid">
    <div class="row justify-content-center text-center">
        <div class="container-fluid text-center">
            <h1 style="color: #2c53c5; margin-top: -0.8em;"><b>REGISTRO DE BODEGAS - UBICACIONES</b></h1>
        </div>
        <button class="btn btn-info" data-toggle="modal" data-target="#newModal">Añadir Bodega - Ubicacion</button>
        <hr>

        <div class="table-responsive" style="background: #f9f9f9;">
            <table id="table_doc" class="cell-border compact stripe" style="background: #f9f9f9; font-size: 13px;">
                <thead>
                    <tr>
                        <th>BODEGA</th>
                        <th>UBICACION</th>
                        <th>NIVEL 1</th>
                        <th>NIVEL 2</th>
                        <th>NIVEL 3</th>
                        <!-- <th></th> -->
                        <!-- <th></th> -->
                    </tr>
                </thead>
                
                <!-- datos obtenidos mediante consulta - mostrados en la vista de la pagina -->
                    <tbody style="text-align: center;">
                        @foreach($bodubi as $bu)
                            <tr>
                                <td>{{ $bu->bodega }}</td>
                                <td>{{ $bu->ubicacion }}</td>
                                <td>{{ $bu->nivel1 }}</td>
                                <td>{{ $bu->nivel2 }}</td>
                                <td>{{ $bu->nivel3 }}</td>
                                <!-- <td>
                                    <button onclick="actualizar('{{ $bu->id_caja }}')" class="btn btn-info" data-toggle="modal" data-target="#actModal">Actualizar</button>
                                </td> -->
                                <!-- <td>
                                  <a href="{{action('CajaController@destroy', $bu->id_caja)}}" class="btn btn-warning">Eliminar</a>         
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
                        <h3>BODEGA - UBICACION</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="1" name="id_bodega" required="required">
                                    <option value="">Bodega</option>
                                    @foreach($bodega as $bo)
                                        <option value="{{ $bo->id_bodega }}">{{ $bo->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="u1" tabindex="2" name="id_ubicacion" required="required">
                                    <option value="">Ubicacion</option>
                                    @foreach($ubicacion as $ub)
                                        <option value="{{ $ub->id_ubicacion }}">{{ $ub->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="nl1" tabindex="3" name="id_ubicacion1">
                                    <option value="">Nivel 1</option>
                                    <script type="text/javascript">
                                        $('#u1').on('change', function(e){
                                            var ubi = e.target.value;
                                            $.get('{{ action('Bodega_UbicacionController@getUbicacion1') }}?id=' + ubi, function(data) {
                                                // console.log(data);
                                                $('#nl1').empty();

                                                if (data == "") {
                                                    $('#nl1').append("<option value=''>Nivel 1</option>");
                                                }else{
                                                    $('#nl1').append("<option value=''>Nivel 1</option>");
                                                    $.each(data, function(index, ClassObj){
                                                        $('#nl1').append("<option value='"+ClassObj.id_ubicacion1+"'>"+ClassObj.descripcion+"</option>");
                                                        // $('#ciudad').selectpicker("refresh");
                                                    })
                                                }
                                            });
                                        });
                                    </script>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="nl2" tabindex="4" name="id_ubicacion2">
                                    <option value="">Nivel 2</option>
                                    <script type="text/javascript">
                                        $('#nl1').on('change', function(e){
                                            var n1 = e.target.value;
                                            $.get('{{ action('Bodega_UbicacionController@getUbicacion2') }}?id=' + n1, function(data) {
                                                // console.log(data);
                                                $('#nl2').empty();

                                                if (data == "") {
                                                    $('#nl2').append("<option value=''>Nivel 2</option>");
                                                }else{
                                                    $('#nl2').append("<option value=''>Nivel 2</option>");
                                                    $.each(data, function(index, ClassObj){
                                                        $('#nl2').append("<option value='"+ClassObj.id_ubicacion2+"'>"+ClassObj.descripcion+"</option>");
                                                        // $('#ciudad').selectpicker("refresh");
                                                    })
                                                }
                                            });
                                        });
                                    </script>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="nl3" tabindex="3" name="id_ubicacion3">
                                    <option value="">Nivel 3</option>
                                    <script type="text/javascript">
                                        $('#nl2').on('change', function(e){
                                            var n2 = e.target.value;
                                            $.get('{{ action('Bodega_UbicacionController@getUbicacion3') }}?id=' + n2, function(data) {
                                                // console.log(data);
                                                $('#nl3').empty();

                                                if (data == "") {
                                                    $('#nl3').append("<option value=''>Nivel 3</option>");
                                                }else{
                                                    $('#nl3').append("<option value=''>Nivel 3</option>");
                                                    $.each(data, function(index, ClassObj){
                                                        $('#nl3').append("<option value='"+ClassObj.id_ubicacion3+"'>"+ClassObj.descripcion+"</option>");
                                                        // $('#ciudad').selectpicker("refresh");
                                                    })
                                                }
                                            });
                                        });
                                    </script>
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
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="7" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <!-- <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="8"> -->
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
                    <form role="form" action="{{ url('regcaj') }}" method="post">
                    @csrf
                        <h2>AGREGAR</h2>
                        <h3>BODEGA - UBICACION</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="1" name="id_bodega" required="required">
                                    <option value="">Bodega</option>
                                    @foreach($bodega as $bo)
                                        <option value="{{ $bo->id_bodega }}">{{ $bo->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="u" tabindex="2" name="id_ubicacion" required="required">
                                    <option value="">Ubicacion</option>
                                    @foreach($ubicacion as $ub)
                                        <option value="{{ $ub->id_ubicacion }}">{{ $ub->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="n1" tabindex="3" name="id_ubicacion1" required="required">
                                    <option value="">Nivel 1</option>
                                    <script type="text/javascript">
                                        $('#u').on('change', function(e){
                                            var ubi = e.target.value;
                                            $.get('{{ action('Bodega_UbicacionController@getUbicacion1') }}?id=' + ubi, function(data) {
                                                // console.log(data);
                                                $('#n1').empty();

                                                if (data == "") {
                                                    $('#n1').append("<option value=''>Nivel 1</option>");
                                                }else{
                                                    $('#n1').append("<option value=''>Nivel 1</option>");
                                                    $.each(data, function(index, ClassObj){
                                                        $('#n1').append("<option value='"+ClassObj.id_ubicacion1+"'>"+ClassObj.descripcion+"</option>");
                                                        // $('#ciudad').selectpicker("refresh");
                                                    })
                                                }
                                            });
                                        });
                                    </script>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="n2" tabindex="4" name="id_ubicacion2" required="required">
                                    <option value="">Nivel 2</option>
                                    <script type="text/javascript">
                                        $('#n1').on('change', function(e){
                                            var n1 = e.target.value;
                                            $.get('{{ action('Bodega_UbicacionController@getUbicacion2') }}?id=' + n1, function(data) {
                                                // console.log(data);
                                                $('#n2').empty();

                                                if (data == "") {
                                                    $('#n2').append("<option value=''>Nivel 2</option>");
                                                }else{
                                                    $('#n2').append("<option value=''>Nivel 2</option>");
                                                    $.each(data, function(index, ClassObj){
                                                        $('#n2').append("<option value='"+ClassObj.id_ubicacion2+"'>"+ClassObj.descripcion+"</option>");
                                                        // $('#ciudad').selectpicker("refresh");
                                                    })
                                                }
                                            });
                                        });
                                    </script>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="n3" tabindex="3" name="id_ubicacion3" required="required">
                                    <option value="">Nivel 3</option>
                                    <script type="text/javascript">
                                        $('#n2').on('change', function(e){
                                            var n2 = e.target.value;
                                            $.get('{{ action('Bodega_UbicacionController@getUbicacion3') }}?id=' + n2, function(data) {
                                                // console.log(data);
                                                $('#n3').empty();

                                                if (data == "") {
                                                    $('#n3').append("<option value=''>Nivel 3</option>");
                                                }else{
                                                    $('#n3').append("<option value=''>Nivel 3</option>");
                                                    $.each(data, function(index, ClassObj){
                                                        $('#n3').append("<option value='"+ClassObj.id_ubicacion3+"'>"+ClassObj.descripcion+"</option>");
                                                        // $('#ciudad').selectpicker("refresh");
                                                    })
                                                }
                                            });
                                        });
                                    </script>
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
<!-- <script src="https://code.jquery.cost/jquery-3.2.1.js"></script> -->
	<script type="text/javascript">
	 	$(".input").focus(function() {
	 		$(this).parent().addClass("focus");
	 	})
	</script>
@endsection
