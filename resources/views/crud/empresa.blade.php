@extends('layouts.app')

@section('content')
<script>
function actualizar(id){
    document.getElementById("form").action = "regemp/"+id;

    $.get('{{ action('CompanyController@getDetails') }}?id='+id, function(data) {
        $('#cod').empty();
        $('#num').empty();
	    $('#rep').empty();
	    $('#dir').empty();
        $('#tel').empty();
	    $('#ema').empty();

        $.each(data, function(index, EmpObj){

            $('#cod').val(EmpObj.id_empresa);
            $('#tip').val(EmpObj.tipo_id);
            $('#num').val(EmpObj.numero_id);
            $('#rep').val(EmpObj.representante_legal);
            $('#ciu').val(EmpObj.ciudad_id);
            $('#dir').val(EmpObj.direccion);
            $('#tel').val(EmpObj.telefono);
            $('#ema').val(EmpObj.email);
            $('#est').val(EmpObj.estado);
	    
        })
    })
}
</script>
<div class="container-fluid">
    <div class="row justify-content-center text-center">
        <div class="container-fluid text-center">
            <h1 style="color: #2c53c5; margin-top: -0.8em;"><b>REGISTRO DE EMPRESA</b></h1>
        </div>
        <button class="btn btn-info" data-toggle="modal" data-target="#newModal">Añadir Empresa</button>
        <hr>

        <div class="table-responsive" style="background: #f9f9f9;">
            <table id="table_doc" class="cell-border compact stripe" style="background: #f9f9f9; font-size: 13px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>TIPO ID</th>
                        <th># EMPRESA</th>
                        <th>REPRESENTANTE LEGAL</th>
                        <th>CIUDAD</th>
                        <th>DIRECCION</th>
                        <th>TELEFONO</th>
                        <th>EMAIL</th>
                        <th>ESTADO</th>
                        <th></th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                
                <!-- datos obtenidos mediante consulta - mostrados en la vista de la pagina -->
                    <tbody style="text-align: center;">
                        @foreach($company as $com)
                            <tr>
                                <td>{{ $com->id_empresa }}</td>
                                <td>{{ $com->tipo }}</td>
                                <td>{{ $com->numero_id }}</td>
                                <td>{{ $com->representante_legal }}</td>
                                <td>{{ $com->nombre }}</td>
                                <td>{{ $com->direccion }}</td>
                                <td>{{ $com->telefono }}</td>
                                <td>{{ $com->email }}</td>
                                <td>
                                  @if($com->estado == '1')
                                        Activo
                                  @else
                                        Inactivo
                                  @endif
                                </td>
                                <td>
                                    <button onclick="actualizar('{{ $com->id_empresa }}')" class="btn btn-info" data-toggle="modal" data-target="#actModal">Actualizar</button>
                                </td>
                                <!-- <td>
                                  <a href="{{action('CompanyController@destroy', $com->id_empresa)}}" class="btn btn-warning">Eliminar</a>         
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
                        <h2>ACTUALIZAR EMPRESA</h2>
                        <h3>EMPRESA</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_empresa" id="cod" placeholder="Id Empresa" class="form-control input-lg" tabindex="1" readonly="readonly" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="tip" data-style="btn-info" tabindex="2" name="tipo_id" required="required">
                                    <option value="">Tipo Documento</option>
                                    @foreach($tipo as $tipos)
                                    <option value="{{ $tipos->id_tipo_id }}">{{ $tipos->tipo_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="numero_id" id="num" placeholder="# Empresa" class="form-control input-lg" tabindex="3" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="representante_legal" id="rep" placeholder="Representante Legal" class="form-control input-lg" tabindex="4" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="ciu" data-style="btn-info" tabindex="5" name="ciudad_id" required="required"  data-live-search="true">
                                    <option value="">Ciudad</option>
                                    @foreach($ciudad as $ciu)
                                    <option value="{{ $ciu->ciudad_id }}">{{ $ciu->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="direccion" id="dir" placeholder="Direccion" class="form-control input-lg" tabindex="6" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="tel" name="telefono" id="tel" placeholder="Telefono" class="form-control input-lg" tabindex="7" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6" style="margin-bottom: -2em;" id="ter2">
                            <div class="form-group">
                                <input type="email" name="email" id="ema" placeholder="Correo" class="form-control input-lg" tabindex="8" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="est" data-style="btn-info" tabindex="9" name="estado" required="required">
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
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="10" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <!-- <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="11"> -->
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
                    <form role="form" action="{{ url('regemp') }}" method="post">
                    @csrf
                        <h2>AGREGAR</h2>
                        <h3>EMPRESA</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_empresa" placeholder="Id Empresa" class="form-control input-lg" tabindex="1" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="2" name="tipo_id" required="required">
                                    <option value="">Tipo Documento</option>
                                    @foreach($tipo as $tipos)
                                    <option value="{{ $tipos->id_tipo_id }}">{{ $tipos->tipo_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="numero_id" placeholder="# Empresa" class="form-control input-lg" tabindex="3" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="representante_legal" placeholder="Representante Legal" class="form-control input-lg" tabindex="4" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="5" name="ciudad_id" required="required"  data-live-search="true">
                                    <option value="">Ciudad</option>
                                    @foreach($ciudad as $ciu)
                                    <option value="{{ $ciu->ciudad_id }}">{{ $ciu->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="direccion" placeholder="Direccion" class="form-control input-lg" tabindex="6" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="tel" name="telefono" placeholder="Telefono" class="form-control input-lg" tabindex="7" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6" style="margin-bottom: -2em;" id="ter2">
                            <div class="form-group">
                                <input type="email" name="email" placeholder="Correo" class="form-control input-lg" tabindex="8" required="required">
                            </div>
                        </div>
                    </div>
      
                    <hr>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="9" value="Enviar">
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
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
	<script type="text/javascript">
	 	$(".input").focus(function() {
	 		$(this).parent().addClass("focus");
	 	})
	</script>
@endsection
