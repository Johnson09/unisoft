@extends('layouts.app')

@section('content')
<script>
function actualizar(id){
    document.getElementById("form").action = "regterc/"+id;

    $.get('{{ action('TerceroController@getDetails') }}?id='+id, function(data) {
        $('#cod').empty();
	    $('#nom').empty();
	    $('#dir').empty();
        $('#tel').empty();
	    $('#ema').empty();
        $('#con').empty();
        $('#dig').empty();

        $.each(data, function(index, EmpObj){

            $('#cod').val(EmpObj.id_tercero);
            $('#tip').val(EmpObj.tipo_id_tercero);
            $('#nat').val(EmpObj.id_naturaleza);
            $('#nom').val(EmpObj.nombre);
            $('#dig').val(EmpObj.digito_v);
            $('#dep').val(EmpObj.departamento_id);
            
            var dep = EmpObj.departamento_id;
                $.get('{{ action('TerceroController@getCiudades') }}?id=' + dep, function(data) {
                    // console.log(data);
                    $('#ciu').empty();

                    if (data == "") {
                        $('#ciu').append("<option value=''>Ciudad</option>");
                    }else{
                        $('#ciu').append("<option value=''>Ciudad</option>");
                            $.each(data, function(index, ClassObj){
                            $('#ciu').append("<option value='"+ClassObj.ciudad_id+"'>"+ClassObj.nombre+"</option>");
                        })
                    }
                    $('#ciu').val(EmpObj.ciudad_id);
                });
                
            $('#dir').val(EmpObj.direccion);
            $('#tel').val(EmpObj.telefono);
            $('#con').val(EmpObj.contacto);
            $('#ema').val(EmpObj.email);
            $('#stt').val(EmpObj.sw_tipo_tercero);
	    
        })
    })
}
</script>
<div class="container-fluid">
    <div class="row justify-content-center text-center">
        <div class="container-fluid text-center">
            <h1 style="color: #2c53c5; margin-top: -0.8em;"><b>REGISTRO DE TERCEROS</b></h1>
        </div>
        <button class="btn btn-info" data-toggle="modal" data-target="#newModal">Añadir Tercero</button>
        <hr>

        <div class="table-responsive" style="background: #f9f9f9;">
            <table id="table_doc" class="cell-border compact stripe" style="background: #f9f9f9; font-size: 12px;">
                <thead>
                    <tr>
                        <th>TIPO ID</th>
						<th>ID</th>
                        <th>DIG. VERIFICACION</th>
                        <th>NATURALEZA</th>
                        <th>RAZON SOCIAL</th>
                        <th>DIRECCION</th>
						<th>TELEFONO</th>
						<th>EMAIL</th>
						<th>DEPARTAMENTO</th>
                        <th>CIUDAD</th>
                        <th>CONTACTO</th>
                        <th>TIPO TERCERO</th>
                        <th></th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                
                <!-- datos obtenidos mediante consulta - mostrados en la vista de la pagina -->
                    <tbody style="text-align: center;">
                        @foreach($tercero as $ter)
                            <tr>
                                <td>{{ $ter->tipo }}</td>
								<td>{{ $ter->id_tercero }}</td>
                                <td>{{ $ter->digito_v }}</td>
                                <td>{{ $ter->naturaleza }}</td>
                                <td>{{ $ter->nombre }}</td>
                                <td>{{ $ter->direccion }}</td>
								<td>{{ $ter->telefono }}</td>
								<td>{{ $ter->email }}</td>
								<td>{{ $ter->departamento }}</td>
                                <td>{{ $ter->ciudad }}</td>
                                <td>{{ $ter->contacto }}</td>
                                <td>
                                  @if($ter->sw_tipo_tercero == '1')
                                        Proveedor
                                  @elseif($ter->sw_tipo_tercero == '2')
                                        Cliente
                                  @endif
                                </td>
                                <td>
                                    <button onclick="actualizar('{{ $ter->id_tercero }}')" class="btn btn-info" data-toggle="modal" data-target="#actModal">Actualizar</button>
                                </td>
                                <!-- <td>
                                  <a href="{{action('TerceroController@destroy', $ter->id_tercero)}}" class="btn btn-warning">Eliminar</a>         
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
                        <h3>TERCERO</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="tip" data-style="btn-info" tabindex="1" name="tipo_id_tercero" required="required">
                                    <option value="">Tipo Documento</option>
                                    @foreach($tipo as $tipos)
                                    <option value="{{ $tipos->id_tipo_id }}">{{ $tipos->tipo_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_tercero" placeholder="Id Tercero" id="cod" class="form-control input-lg" tabindex="2" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="digito_v" placeholder="Dig. Verificacion" id="dig" class="form-control input-lg" tabindex="3" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" id="nat" data-style="btn-info" tabindex="4" name="id_naturaleza" required="required">
                                    <option value="">Naturaleza</option>
                                    @foreach($naturaleza as $na)
                                    <option value="{{ $na->id_naturaleza }}">{{ $na->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="stt" tabindex="5" name="sw_tipo_tercero" required="required"  data-live-search="true">
                                    <option value="">Tipo Tercero</option>
                                    <option value="1">Proveedor</option>
                                    <option value="2">Cliente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="nombre" id="nom" placeholder="Razon Social" class="form-control input-lg" tabindex="6" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="direccion" placeholder="Direccion" id="dir" class="form-control input-lg" tabindex="7" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="tel" name="telefono" placeholder="Telefono" id="tel" class="form-control input-lg" tabindex="8" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="email" name="email" placeholder="Correo" id="ema" class="form-control input-lg" tabindex="9" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6" style="margin-bottom: -2em;" id="ter2">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="10" id="dep" name="departamento_id" required="required"  data-live-search="true">
                                    <option value="">Departamento</option>
                                    @foreach($departamento as $dep)
                                    <option value="{{ $dep->departamento_id }}">{{ $dep->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="11" id="ciu" name="ciudad_id" required="required"  data-live-search="true">
                                    <option value="">Ciudad</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6" style="margin-bottom: -2em;" id="ter2">
                            <div class="form-group">
                                <input type="text" name="contacto" placeholder="Contacto" id="con" class="form-control input-lg" tabindex="12" required="required">
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
<div id="newModal" class="modal fade">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="background:;">
            <div class="modal-body" style="font-size: 14px">
                <div style="text-align: center;">
                    <form role="form" action="{{ url('regterc') }}" method="post">
                    @csrf
                        <h2>AGREGAR</h2>
                        <h3>TERCERO</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="1" name="tipo_id_tercero" required="required">
                                    <option value="">Tipo Documento</option>
                                    @foreach($tipo as $tipos)
                                    <option value="{{ $tipos->id_tipo_id }}">{{ $tipos->tipo_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_tercero" placeholder="Id Tercero" class="form-control input-lg" tabindex="2" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="digito_v" placeholder="Dig. Verificacion" class="form-control input-lg" tabindex="3" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="4" name="id_naturaleza" required="required">
                                    <option value="">Naturaleza</option>
                                    @foreach($naturaleza as $na)
                                    <option value="{{ $na->id_naturaleza }}">{{ $na->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="5" name="sw_tipo_tercero" required="required"  data-live-search="true">
                                    <option value="">Tipo Tercero</option>
                                    <option value="1">Proveedor</option>
                                    <option value="2">Cliente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="nombre" placeholder="Razon Social" class="form-control input-lg" tabindex="6" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="direccion" placeholder="Direccion" class="form-control input-lg" tabindex="7" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="tel" name="telefono" placeholder="Telefono" class="form-control input-lg" tabindex="8" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="email" name="email" placeholder="Correo" class="form-control input-lg" tabindex="9" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6" style="margin-bottom: -2em;" id="ter2">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="10" id="dep1" name="departamento_id" required="required"  data-live-search="true">
                                    <option value="">Departamento</option>
                                    @foreach($departamento as $dep)
                                    <option value="{{ $dep->departamento_id }}">{{ $dep->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="11" id="ciu1" name="ciudad_id" required="required"  data-live-search="true">
                                    <option value="">Ciudad</option>
                                    <script type="text/javascript">
                                    $('#dep1').on('change', function(e){
                                        var dep = e.target.value;
                                        $.get('{{ action('TerceroController@getCiudades') }}?id=' + dep, function(data) {
                                            // console.log(data);
                                            $('#ciu1').empty();

                                            if (data == "") {
                                                $('#ciu1').append("<option value=''>Ciudad</option>");
                                            }else{
                                                $('#ciu1').append("<option value=''>Ciudad</option>");
                                                $.each(data, function(index, ClassObj){
                                                    $('#ciu1').append("<option value='"+ClassObj.ciudad_id+"'>"+ClassObj.nombre+"</option>");
                                                    // $('#ciudad').selectpicker("refresh");
                                                })
                                            }
                                        });
                                    });
                                </script>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6" style="margin-bottom: -2em;" id="ter2">
                            <div class="form-group">
                                <input type="text" name="contacto" placeholder="Contacto" class="form-control input-lg" tabindex="12" required="required">
                            </div>
                        </div>
                    </div>
      
                    <hr>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="13" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="14">
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
