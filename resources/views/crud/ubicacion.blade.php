@extends('layouts.app')

@section('content')
<script>
function actualizar(id){
    document.getElementById("form").action = "regubi/"+id;

    $.get('{{ action('UbicacionController@getDetails') }}?id='+id, function(data) {
        $('#cod').empty();
        $('#des').empty();
	    // $('#tip').empty();

        $.each(data, function(index, EmpObj){

            $('#cod').val(EmpObj.id_ubicacion);
            $('#des').val(EmpObj.descripcion);
            $('#tip').val(EmpObj.tipo_ubicacion);
	    
        })
    })
}
</script>
<div class="container-fluid">
    <div class="row justify-content-center text-center">
        <div class="container-fluid text-center">
            <h1 style="color: #2c53c5; margin-top: -0.8em;"><b>REGISTRO DE UBICACIONES</b></h1>
        </div>
        <button class="btn btn-info" data-toggle="modal" data-target="#newModal">Añadir Ubicacion</button>
        <hr>

        <div class="table-responsive" style="background: #f9f9f9;">
            <table id="table_doc" class="cell-border compact stripe" style="background: #f9f9f9; font-size: 13px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>DESCRIPCION</th>
                        <th>TIPO UBICACION</th>
                        <th></th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                
                <!-- datos obtenidos mediante consulta - mostrados en la vista de la pagina -->
                    <tbody style="text-align: center;">
                        @foreach($ubicacion as $ubi)
                            <tr>
                                <td>{{ $ubi->id_ubicacion }}</td>
                                <td>{{ $ubi->descripcion }}</td>
                                <td>{{ $ubi->tipo }}</td>
                                <td>
                                    <button onclick="actualizar('{{ $ubi->id_ubicacion }}')" class="btn btn-info" data-toggle="modal" data-target="#actModal">Actualizar</button>
                                </td>
                                <!-- <td>
                                  <a href="{{action('UbicacionController@destroy', $ubi->id_ubicacion)}}" class="btn btn-warning">Eliminar</a>         
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
                        <h3>UBICACION</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="id_ubicacion" id="cod" placeholder="Id Ubicacion" readonly="readonly" class="form-control input-lg" tabindex="1" required="required">
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
                                <select class="selectpicker form-control input-lg" data-style="btn-info" id="tip" tabindex="3" name="tipo_ubicacion" required="required">
                                    <option value="">Tipo Ubicacion</option>
                                    @foreach($tipo as $tp)
                                    <option value="{{ $tp->id_tipo_ubicacion }}">{{ $tp->descripcion }}</option>
                                    @endforeach
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
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="4" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <!-- <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="5"> -->
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
                    <form role="form" action="{{ url('regubi') }}" method="post">
                    @csrf
                        <h2>AGREGAR</h2>
                        <h3>UBICACION</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="text" name="descripcion" placeholder="Descripcion" class="form-control input-lg" tabindex="1" required="required"></div>
                            </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="2" name="tipo_ubicacion" required="required">
                                    <option value="">Tipo Ubicacion</option>
                                    @foreach($tipo as $tp)
                                    <option value="{{ $tp->id_tipo_ubicacion }}">{{ $tp->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="button" class="form-control btn btn-success" id="add_field" value="Adicionar Otra Ubicacion">
                                <script>
                                    var campos_max = 3;   //max de 10 campos
                                    var x = 0;
                                        $('#add_field').click (function(e) {
                                            e.preventDefault();     //prevenir novos clicks
                                            if (x < campos_max) {
                                                $('#ub'+x).append('<div>\
                                                    <input type="text" class="form-control" name="ubi'+x+'">\
                                                    <a href="#" class="remover_campo">Remover</a>\
                                                    </div>');
                                                x++;
                                            }
                                        });
                                        // Remover o div anterior
                                        $('#ub0').on("click",".remover_campo",function(e) {
                                            e.preventDefault();
                                            $(this).parent('div').remove();
                                            x--;
                                        });
                                </script>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group" id="ub0">
                            
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group" id="ub1">

                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group" id="ub2">
                            
                            </div>
                        </div>
                    </div>
      
                    <hr>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                        <input type="submit" class="btn btn-info btn-block btn-lg" title="Guardar" tabindex="4" value="Enviar">
                        </div>
                        <div class="col-xs-6 col-md-6">
                        <!-- <input type="reset" value="Restaurar" class="btn btn-warning btn-block btn-lg" tabindex="5"> -->
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
<!-- <script src="https://code.jquery.ope/jquery-3.2.1.js"></script> -->
	<script type="text/javascript">
	 	$(".input").focus(function() {
	 		$(this).parent().addClass("focus");
	 	})
	</script>
@endsection
