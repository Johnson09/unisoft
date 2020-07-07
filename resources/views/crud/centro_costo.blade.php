@extends('layouts.app')

@section('content')
<script>
function actualizar(id){
    document.getElementById("form").action = "regcc/"+id;

    $.get('{{ action('CCController@getDetails') }}?id='+id, function(data) {
        $('#cod').empty();
	    $('#des').empty();
	    $('#cue').empty();

        $.each(data, function(index, EmpObj){

            $('#cod').val(EmpObj.id_centro_costo);
            $('#co').val(EmpObj.id_centro_operacion);
            $('#des').val(EmpObj.descripcion);
            $('#cue').val(EmpObj.cuenta);
            $('#est').val(EmpObj.estado);
	    
        })
    })
}
</script>
<div class="container-fluid">
    <div class="row justify-content-center text-center">
        <div class="container-fluid text-center">
            <h1 style="color: #2c53c5; margin-top: -0.8em;"><b>REGISTRO DE CENTRO COSTO</b></h1>
        </div>
        <button class="btn btn-info" data-toggle="modal" data-target="#newModal">Añadir Centro Costo</button>
        <hr>

        <div class="table-responsive" style="background: #f9f9f9;">
            <table id="table_doc" class="cell-border compact stripe" style="background: #f9f9f9; font-size: 13px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>DESCRIPCION</th>
                        <th>CENTRO OPERACION</th>
                        <th>CUENTA</th>
                        <th>ESTADO</th>
                        <th></th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                
                <!-- datos obtenidos mediante consulta - mostrados en la vista de la pagina -->
                    <tbody style="text-align: center;">
                        @foreach($costos as $cost)
                            <tr>
                                <td>{{ $cost->id_centro_costo }}</td>
                                <td>{{ $cost->descripcion }}</td>
                                <td>{{ $cost->centro_operacion }}</td>
                                <td>{{ $cost->cuenta }}</td>
                                <td>
                                  @if($cost->estado == '1')
                                        Activo
                                  @else
                                        Inactivo
                                  @endif
                                </td>
                                <td>
                                    <button onclick="actualizar('{{ $cost->id_centro_costo }}')" class="btn btn-info" data-toggle="modal" data-target="#actModal">Actualizar</button>
                                </td>
                                <!-- <td>
                                  <a href="{{action('CCController@destroy', $cost->id_centro_costo)}}" class="btn btn-warning">Eliminar</a>         
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
                        <h3>CENTRO COSTO</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_centro_costo" id="cod" placeholder="Id Centro Costo" class="form-control input-lg" tabindex="1" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="co" data-style="btn-info" tabindex="2" name="id_centro_operacion" required="required">
                                    <option value="">Centro Operacion</option>
                                    @foreach($operacion as $ope)
                                    <option value="{{ $ope->id_centro_operacion }}">{{ $ope->descripcion }}</option>
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
                                <input type="number" name="cuenta" placeholder="Cuenta" id="cue" class="form-control input-lg" tabindex="4" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="est" data-style="btn-info" tabindex="5" name="estado" required="required">
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
                        <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="7">
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
                    <form role="form" action="{{ url('regcc') }}" method="post">
                    @csrf
                        <h2>AGREGAR</h2>
                        <h3>CENTRO COSTO</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_centro_costo" placeholder="Id Centro Costo" class="form-control input-lg" tabindex="1" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="2" name="id_centro_operacion" required="required">
                                    <option value="">Centro Operacion</option>
                                    @foreach($operacion as $ope)
                                    <option value="{{ $ope->id_centro_operacion }}">{{ $ope->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="descripcion" placeholder="Descripcion" class="form-control input-lg" tabindex="3" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="cuenta" placeholder="Cuenta" class="form-control input-lg" tabindex="4" required="required">
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
<script src="https://code.jquery.cost/jquery-3.2.1.js"></script>
	<script type="text/javascript">
	 	$(".input").focus(function() {
	 		$(this).parent().addClass("focus");
	 	})
	</script>
@endsection
