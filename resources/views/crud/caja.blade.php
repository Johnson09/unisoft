@extends('layouts.app')

@section('content')
<script>
function actualizar(id){
    document.getElementById("form").action = "regcaj/"+id;

    $.get('{{ action('CajaController@getDetails') }}?id='+id, function(data) {
        $('#cod').empty();
	    $('#des').empty();

        $.each(data, function(index, EmpObj){
            
            $('#se').val(EmpObj.id_sede);

            $.get('{{ action('CajaController@getCosto') }}?id=' + EmpObj.id_sede, function(data) {
                // console.log(data);
                $('#co').empty();

                if (data == "") {
                    $('#co').append("<option value=''>Centro Costo</option>");
                }else{
                    $('#co').append("<option value=''>Centro Costo</option>");
                    $.each(data, function(index, ClassObj){
                        $('#co').append("<option value='"+ClassObj.id_centro_costo+"'>"+ClassObj.descripcion+"</option>");
                    })
                }
                $('#co').val(EmpObj.id_centro_costo);
            });

            $('#cod').val(EmpObj.id_caja);
            $('#des').val(EmpObj.descripcion);
            $('#tip').val(EmpObj.tipo_venta);
            $('#est').val(EmpObj.estado);
	    
        })
    })
}
</script>
<div class="container-fluid">
    <div class="row justify-content-center text-center">
        <div class="container-fluid text-center">
            <h1 style="color: #2c53c5; margin-top: -0.8em;"><b>REGISTRO DE CAJAS</b></h1>
        </div>
        <button class="btn btn-info" data-toggle="modal" data-target="#newModal">Añadir Caja</button>
        <hr>

        <div class="table-responsive" style="background: #f9f9f9;">
            <table id="table_doc" class="cell-border compact stripe" style="background: #f9f9f9; font-size: 13px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>DESCRIPCION</th>
                        <th>SEDE</th>
                        <th>CENTRO COSTO</th>
                        <th>TIPO_VENTA</th>
                        <th>ESTADO</th>
                        <th></th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                
                <!-- datos obtenidos mediante consulta - mostrados en la vista de la pagina -->
                    <tbody style="text-align: center;">
                        @foreach($caja as $ca)
                            <tr>
                                <td>{{ $ca->id_caja }}</td>
                                <td>{{ $ca->descripcion }}</td>
                                <td>{{ $ca->sede }}</td>
                                <td>{{ $ca->centro_costo }}</td>
                                <td>
                                  @if($ca->tipo_venta == '1')
                                        Contado
                                  @elseif($ca->tipo_venta == '2')
                                        Credito
                                  @else
                                        Todas
                                  @endif
                                </td>
                                <td>
                                  @if($ca->estado == '1')
                                        Activo
                                  @else
                                        Inactivo
                                  @endif
                                </td>
                                <td>
                                    <button onclick="actualizar('{{ $ca->id_caja }}')" class="btn btn-info" data-toggle="modal" data-target="#actModal">Actualizar</button>
                                </td>
                                <!-- <td>
                                  <a href="{{action('CajaController@destroy', $ca->id_caja)}}" class="btn btn-warning">Eliminar</a>         
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
                        <h3>CAJA</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_caja" id="cod" placeholder="Id Caja" class="form-control input-lg" tabindex="1" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="descripcion" id="des" placeholder="Descripcion" class="form-control input-lg" tabindex="2" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="se" data-style="btn-info" tabindex="3" name="id_sede" required="required">
                                    <option value="">Sede</option>
                                    @foreach($sede as $se)
                                        <option value="{{ $se->id_sede }}">{{ $se->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="co" data-style="btn-info" tabindex="4" name="id_centro_costo" required="required">
                                    <option value="">Centro Costo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="5" id="tip" name="tipo_venta" required="required"  data-live-search="true">
                                    <option value="">Tipo Venta</option>
                                    <option value="0">Todas</option>
                                    <option value="1">Contado</option>
                                    <option value="2">Credito</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="est" data-style="btn-info" tabindex="6" name="estado" required="required">
                                    <option value="">Estado</option>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
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
                        <h3>CAJA</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="descripcion" placeholder="Descripcion" class="form-control input-lg" tabindex="1" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="sede" tabindex="2" name="id_sede" required="required">
                                    <option value="">Sede</option>
                                    @foreach($sede as $se)
                                        <option value="{{ $se->id_sede }}">{{ $se->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="costo" tabindex="3" name="id_centro_costo" required="required">
                                    <option value="">Centro Costo</option>
                                    <script type="text/javascript">
                                        $('#sede').on('change', function(e){
                                            var sede = e.target.value;
                                            $.get('{{ action('CajaController@getCosto') }}?id=' + sede, function(data) {
                                                // console.log(data);
                                                $('#costo').empty();

                                                if (data == "") {
                                                    $('#costo').append("<option value=''>Centro Costo</option>");
                                                }else{
                                                    $('#costo').append("<option value=''>Centro Costo</option>");
                                                    $.each(data, function(index, ClassObj){
                                                        $('#costo').append("<option value='"+ClassObj.id_centro_costo+"'>"+ClassObj.descripcion+"</option>");
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
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="4" name="tipo_venta" required="required"  data-live-search="true">
                                    <option value="">Tipo Venta</option>
                                    <option value="0">Todas</option>
                                    <option value="1">Contado</option>
                                    <option value="2">Credito</option>
                                </select>
                            </div>
                        </div>
                    </div>
      
                    <hr>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="5" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="6">
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
