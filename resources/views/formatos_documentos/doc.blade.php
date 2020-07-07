<!DOCTYPE html>
<html lang="es">
    <head>
      @foreach($docInv as $enca)
        <title>Documento_{{ $enca->numero }}_{{ $enca->prefijo }}_{{ $enca->created_at }}</title>
      @endforeach
        <meta charset="utf-8">
        <link href="../public/css/sb-admin-2.min.css" rel="stylesheet">

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
            color: #000;
        }
        </style>

    </head>
    <body>

      <div class="container">
        <header>
          <i style="float: right; font-size: 14px; color: #000;">Fecha y Hora de Impresion <?php date_default_timezone_set('America/Bogota'); echo now(); ?>
          </i>
        </header>

        <table id="principal">
          <thead>
          @foreach($docInv as $doc)
            <tr>
              <td style="width: 10%;">
                <img src="../public/images/logo/logo.png" style="width: 8em; margin-top: 1em;">
              </td>
              <td colspan="3">
                <strong><p>
                  DISTRIBUIDORA Y PAPELERIA UNICA
                </p>
                <p>
                  NIT.  900,234,222
                </p>
                <p>
                  DIRECCION: CR 12 9 51 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TELÉFONO: 3174422584 
                </p></strong>
              </td>
            </tr>
            <tr>
              <td colspan="4" style="text-align: center;">
                <strong><p>
                  INGRESO AJUSTE INVENTARIO 
                </p></strong>
              </td>
            </tr>
            <tr>
              <th>
                <p>
                 NUMERO DOCUMENTO 
                </p>
              </th>
              <td>
                <strong><p>
                  BODEGA:
                </p></strong>
              </td>
              <td>
                <strong><p>
                  USUARIO:
                </p></strong>
              </td>
              <td>
                <strong><p>
                  FECHA:
                </p></strong>
              </td>
            </tr>
            <tr>
              <td>
                <p>
                  {{ $enca->prefijo }} {{ $enca->numero }} 
                </p>
              </td>
              <td>
                <p>
                  {{ $enca->bodega }}
                </p>
              </td>
              <td>
                <p>
                  {{ $enca->usuario }}
                </p>
              </td>
              <td>
                <p>
                  {{ $enca->created_at }}
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
