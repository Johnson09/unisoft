@extends('layouts.app')

@section('content')
<script>
function actualizar(id){
    document.getElementById("form").action = "regco/"+id;

    $.get('{{ action('COController@getDetails') }}?id='+id, function(data) {
        $('#cod').empty();
        $('#des').empty();
	    $('#dir').empty();
        $('#tel').empty();

        $.each(data, function(index, EmpObj){

            $('#cod').val(EmpObj.id_centro_operacion);
            $('#emp').val(EmpObj.id_empresa);
            $('#des').val(EmpObj.descripcion);
            $('#ciu').val(EmpObj.ciudad_id);
            $('#dir').val(EmpObj.direccion);
            $('#tel').val(EmpObj.telefono);
	    
        })
    })
}
</script>
<div class="container-fluid">
    <div class="row justify-content-center text-center">
        <div class="container-fluid text-center">
            <h1 style="color: #2c53c5; margin-top: -0.8em;"><b>REGISTRO DE CENTRO OPERACION</b></h1>
        </div>
        <button class="btn btn-info" data-toggle="modal" data-target="#newModal">Añadir Centro Operacion</button>
        <hr>

        <div class="table-responsive" style="background: #f9f9f9;">
            <table id="table_doc" class="cell-border compact stripe" style="background: #f9f9f9; font-size: 13px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>EMPRESA</th>
                        <th>DESCRIPCION</th>
                        <th>CIUDAD</th>
                        <th>DIRECCION</th>
                        <th>TELEFONO</th>
                        <th></th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                
                <!-- datos obtenidos mediante consulta - mostrados en la vista de la pagina -->
                    <tbody style="text-align: center;">
                        @foreach($operaciones as $ope)
                            <tr>
                                <td>{{ $ope->id_centro_operacion }}</td>
                                <td>{{ $ope->representante_legal }}</td>
                                <td>{{ $ope->descripcion }}</td>
                                <td>{{ $ope->nombre }}</td>
                                <td>{{ $ope->direccion }}</td>
                                <td>{{ $ope->telefono }}</td>
                                <td>
                                    <button onclick="actualizar('{{ $ope->id_centro_operacion }}')" class="btn btn-info" data-toggle="modal" data-target="#actModal">Actualizar</button>
                                </td>
                                <!-- <td>
                                  <a href="{{action('COController@destroy', $ope->id_centro_operacion)}}" class="btn btn-warning">Eliminar</a>         
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
        <!-- Modal content-->
        <div class="modal-content" style="background:;">
            <div class="modal-body" style="font-size: 14px">
                <div style="text-align: center;">
                    <form role="form" action="#" method="post" id="form">
                    @method('PATCH')
                    @csrf
                        <h2>ACTUALIZAR</h2>
                        <h3>CENTRO OPERACION</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_centro_operacion" id="cod" placeholder="Id Centro Operacion" readonly="readonly" class="form-control input-lg" tabindex="1" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="emp" data-style="btn-info" tabindex="2" name="id_empresa" required="required">
                                    <option value="">Empresa</option>
                                    @foreach($company as $com)
                                    <option value="{{ $com->id_empresa }}">{{ $com->representante_legal }}</option>
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
                                <select class="selectpicker form-control input-lg" id="ciu" data-style="btn-info" tabindex="4" name="ciudad_id" required="required">
                                    <option value="">Ciudad</option>
                                    @foreach($ciudad as $ciu)
                                    <option value="{{ $ciu->ciudad_id }}">{{ $ciu->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="direccion" placeholder="Direccion" id="dir" class="form-control input-lg" tabindex="5" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6" style="margin-bottom: -2em;" id="ter2">
                            <div class="form-group">
                                <input type="tel" name="telefono" placeholder="Telefono" id="tel" class="form-control input-lg" tabindex="6" required="required">
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
                    <form role="form" action="{{ url('regco') }}" method="post">
                    @csrf
                        <h2>AGREGAR</h2>
                        <h3>CENTRO OPERACION</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_centro_operacion" placeholder="Id Centro Operacion" class="form-control input-lg" tabindex="1" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="2" name="id_empresa" required="required">
                                    <option value="">Empresa</option>
                                    @foreach($company as $com)
                                    <option value="{{ $com->id_empresa }}">{{ $com->representante_legal }}</option>
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
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="4" name="ciudad_id" required="required">
                                    <option value="">Ciudad</option>
                                    @foreach($ciudad as $ciu)
                                    <option value="{{ $ciu->ciudad_id }}">{{ $ciu->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="direccion" placeholder="Direccion" class="form-control input-lg" tabindex="5" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6" style="margin-bottom: -2em;" id="ter2">
                            <div class="form-group">
                                <input type="tel" name="telefono" placeholder="Telefono" class="form-control input-lg" tabindex="6" required="required">
                            </div>
                        </div>
                    </div>
      
                    <hr>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="7" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="8">
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
<script src="https://code.jquery.ope/jquery-3.2.1.js"></script>
	<script type="text/javascript">
	 	$(".input").focus(function() {
	 		$(this).parent().addClass("focus");
	 	})
	</script>
@endsection
