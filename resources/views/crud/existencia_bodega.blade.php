@extends('layouts.app')

@section('content')
<!-- <script>
function actualizar(id){
    document.getElementById("form").action = "regclas/"+id;

    $.get('{{ action('Existencia_BodegaController@getDetails') }}?id='+id, function(data) {
        $('#cod').empty();
        $('#des').empty();

        $.each(data, function(index, EmpObj){

            $('#cod').val(EmpObj.id_clase);
            $('#gru').val(EmpObj.id_grupo);
            $('#des').val(EmpObj.descripcion);
            $('#est').val(EmpObj.estado);
	    
        })
    })
}
</script> -->
<div class="container-fluid">
    <div class="row justify-content-center text-center">
        <div class="container-fluid text-center">
            <h1 style="color: #2c53c5; margin-top: -0.8em;"><b>REGISTRO DE EXISTENCIA - BODEGA</b></h1>
        </div>
        <button class="btn btn-info" data-toggle="modal" data-target="#newModal">Añadir Existencia Bodega</button>
        <hr>

        <div class="table-responsive" style="background: #f9f9f9;">
            <table id="table_doc" class="cell-border compact stripe" style="background: #f9f9f9; font-size: 13px;">
                <thead>
                    <tr>
                        <th>BODEGA</th>
                        <th>PRODUCTO</th>
                        <th>EXIS. INICIAL</th>
                        <th>EXIS. ACTUAL</th>
                        <th>EXIS. MINIMA</th>
                        <th>EXIS. MAXIMA</th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                
                <!-- datos obtenidos mediante consulta - mostrados en la vista de la pagina -->
                    <tbody style="text-align: center;">
                        @foreach($existencia as $exis)
                            <tr>
                                <td>{{ $exis->bodega }}</td>
                                <td>{{ $exis->producto }}</td>
                                <td>{{ $exis->existencia_inicial }}</td>
                                <td>{{ $exis->existencia_actual }}</td>
                                <td>{{ $exis->existencia_minima }}</td>
                                <td>{{ $exis->existencia_maxima }}</td>
                                <!-- <td>
                                    <button onclick="actualizar('{{ $exis->id_clase }}')" class="btn btn-info" data-toggle="modal" data-target="#actModal">Actualizar</button>
                                </td> -->
                                <!-- <td>
                                  <a href="{{action('ClaseController@destroy', $exis->id_clase)}}" class="btn btn-warning">Eliminar</a>         
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
                        <h3>EXISTENCIA - BODEGA</h3>
                        <hr>
      
                        <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="1" id="bode" name="id_bodega" required="required">
                                    <option value="">Bodega</option>
                                    @foreach($bodega as $bod)
                                    <option value="{{ $bod->id_bodega }}">{{ $bod->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="2" id="prod" name="id_producto" required="required">
                                    <option value="">Producto</option>
                                    @foreach($producto as $pro)
                                    <option value="{{ $pro->id_producto }}">{{ $pro->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="existencia_inicial" placeholder="Existencia Inicial" id="ei" class="form-control input-lg" tabindex="3" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="existencia_actual" placeholder="Existencia Actual" id="ea" class="form-control input-lg" tabindex="4" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="existencia_minima" placeholder="Existencia Minima" id="emi" class="form-control input-lg" tabindex="5" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="existencia_maxima" placeholder="Existencia Maxima" id="em" class="form-control input-lg" tabindex="6" required="required">
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
<div id="newModal" class="modal fade">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="background:;">
            <div class="modal-body" style="font-size: 14px">
                <div style="text-align: center;">
                    <form role="form" action="{{ url('regclas') }}" method="post">
                    @csrf
                        <h2>AGREGAR</h2>
                        <h3>EXISTENCIA - BODEGA</h3>
                        <hr>
      
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="1" id="bod" name="id_bodega" required="required">
                                    <option value="">Bodega</option>
                                    @foreach($bodega as $bod)
                                    <option value="{{ $bod->id_bodega }}">{{ $bod->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <select class="selectpicker form-control input-lg" data-style="btn-info" tabindex="2" id="pro" name="id_producto" required="required">
                                    <option value="">Producto</option>
                                    @foreach($producto as $pro)
                                    <option value="{{ $pro->id_producto }}">{{ $pro->descripcion }}</option>
                                    @endforeach
                                </select>
                                <script type="text/javascript">
                                    $('#pro').on('change', function(e){
                                        var pro = e.target.value;
                                        $.get('{{ action('Existencia_BodegaController@validProducto') }}?id=' + pro, function(data) {
                                            // console.log(data);

                                            if (data == "") {
                                                swal({
                                                    title: 'El producto no aparece en el Inventario',
                                                    icon: "error",
                                                    buttons: "Aceptar!",
                                                });
                                            }else{
                                                swal({
                                                    title: 'El producto se encuentra Inventariado',
                                                    icon: "success",
                                                    buttons: "Aceptar!",
                                                });
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
                                <input type="number" name="existencia_inicial" placeholder="Existencia Inicial" class="form-control input-lg" tabindex="3" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="existencia_actual" placeholder="Existencia Actual" class="form-control input-lg" tabindex="4" required="required">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="existencia_minima" placeholder="Existencia Minima" class="form-control input-lg" tabindex="5" required="required">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <input type="number" name="existencia_maxima" placeholder="Existencia Maxima" class="form-control input-lg" tabindex="6" required="required">
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
<!-- <script src="https://code.jquery.cla/jquery-3.2.1.js"></script> -->
	<script type="text/javascript">
	 	$(".input").focus(function() {
	 		$(this).parent().addClass("focus");
	 	})
	</script>
@endsection
