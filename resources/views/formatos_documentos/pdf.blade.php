<!DOCTYPE html>
<html lang="es">
    <head>
      @foreach($docInv as $enca)
        <title>Documento_{{ $enca->numero }}_{{ $enca->prefijo }}_{{ $enca->created_at }}</title>
      @endforeach
        <meta charset="utf-8">

        <!-- estilo de la tabla -->
        <style>
        #principal {
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            border-collapse: collapse;
            width: 100%;
        }

        #principal td, #principal th {
            border: 1px solid #ddd;
        }
        </style>

    </head>
    <body>

    <header>
      <i style="float: right; font-size: 14px;">Fecha y Hora de Impresion <?php date_default_timezone_set('America/Bogota'); echo now(); ?>
      </i>
    </header>
    <br>

    <table id="principal">
      <thead>
      @foreach($docInv as $doc)
        <tr>
          <td style="width: 10%;">
            <img src="../public/images/logo/logo.png" style="width: 8em; margin-top: 1em;">
          </td>
          <td colspan="4">
            <strong><p>
              DISTRIBUIDORA Y PAPELERIA UNICA
            </p>
            <p>
              NIT.  900,234,222
            </p>
            <p>
              TELÉFONO: 3174422584 
            </p>
            <p>
              DIRECCION: CR 12 9 51
            </p></strong>
          </td>
        </tr>
        <tr>
          <td colspan="5" style="text-align: center;">
            <strong><p>
              INGRESO AJUSTE INVENTARIO 
            </p></strong>
          </td>
        </tr>
        <tr>
          <th colspan="2">
            <p>
             NUMERO DOCUMENTO 
            </p>
          </th>
          <td colspan="3">
            <p>
              {{ $enca->prefijo }} {{ $enca->numero }} 
            </p>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <strong><p>
              BODEGA:
            </p></strong>
          </td>
          <td colspan="1">
            <strong><p>
              FECHA:
            </p></strong>
          </td>
          <td colspan="2">
            <strong><p>
              USUARIO:
            </p></strong>
          </td>
        </tr>
         <tr>
          <td colspan="2">
            <p>
              {{ $enca->bodega }}
            </p>
          </td>
          <td colspan="1">
            <p>
              {{ $enca->created_at }}
            </p>
          </td>
          <td colspan="2">
            <p>
              {{ $enca->usuario }}
            </p>
          </td>
        </tr>
      @endforeach
    </thead>
  </table>
  <br>
  <table id="principal">
    <tbody>
      <tr>
        <th>
            ID PRODUCTO
        </th>
        <th>
            DESCRIPCION
        </th>
        <th>
            CANTIDAD
        </th>
        <th>
            LOTE
        </th>
        <th>
            COSTO UNITARIO
        </th>
        <th>
            COSTO TOTAL
        </th>
      </tr>
      @foreach($docInvDet as $detail)
        <tr>
          <td>
            {{ $detail->id_producto }}
          </td>
          <td>
            {{ $detail->producto }}
          </td>
          <td>
            {{ $detail->cantidad }}
          </td>
          <td>
            {{ $detail->lote }}
          </td>
          <td>
            $ {{ number_format($detail->costo_und) }}
          </td>
          <td>
            $ {{ number_format($detail->costo_total) }}
          </td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
          <th colspan="5">
            TOTAL
          </th>
          <th>
            $ {{ number_format($total) }}
          </th>      
        </tr>
    </tfoot>
  </table>
  </div>
  </body>
</html>
