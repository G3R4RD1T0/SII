
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">
    header { position: fixed; top: 0px; left: 0px;}
    footer { position: fixed; bottom: 0px; left: 0px;}
    footer .pagenum:before {
      content: counter(page);
    }
      h3{
        text-align: center;
        font-weight: bold;
      }
      p{
        text-align: justify;
      }
      table,td,th, .cuadro{
        table-layout: fixed;
        width: 100%;
        border: 1px solid black;
        border-collapse: collapse;
      }

      thead,tfoot{
        text-align: center;
      }
      tbody {
        padding: 5px;
        text-align: left;
      }
    </style>
  </head>
  <body>
    <header>M00-PR-03-R02</header>
    <footer>
        <span><?php echo date('d/m/Y h:i:s a');?></span>
        <div style="float: right ; text-align: right" class="pagenum-container">Pagina: <span class="pagenum"></span></div>
    </footer>
  <h3>PROTOCOLO DEL PROYECTO (CI-02/2017)</h3>
  <h3>NOMBRE DE LA INSTITUCIÓN: <span class="cuadro">{{$proyecto->nombre_ies}}</span></h3>
  <br>
  <p>Titutlo del proyecto:</p>
  <div class="cuadro">{{$proyecto->titulo}}</div>

  <h3>1. DESCRIPCIÓN DEL PROYECTO</h3>
 <article>
  <p><b>1.1 Resumen</b></p>
  <p>{{$proyecto->resumen}}</p>
 </article>
 <article>
  <p><b>1.2 Introducción</b></p>
  <p>{{$proyecto->introduccion}}</p>
</article>
 <article>
  <p><b>1.3 Antecedentes</b></p>
  <p>{{$proyecto->antecedentes}}</p>
</article>
 <article>
  <p><b>1.4 Marco teórico</b></p>
  <p>{{$proyecto->marco_teorico}}</p>
</article>
 <article>
  <p><b>1.5 Objetivos</b></p>
  <p>{{$proyecto->objetivo_general}}</p>
  <p>{!!$proyecto->objetivos_especificos!!}</p>
</article>
 <article>
  <p><b>1.6 Metas</b></p>
@php
  $entregablesa = $proyecto->entregables->where('tipo',"ACADEMICO");
  $listaa = "<ol>";
  foreach ($entregablesa as $entregable) {
    $listaa .= "<li>$entregable->descripcion: $entregable->cuantos</li>";
  }
  $listaa . "</ol>";

  $entregablesh = $proyecto->entregables->where('tipo',"HUMANO");
  $listah = "<ol>";
  foreach ($entregablesh as $entregable) {
    $listah .= "<li>$entregable->descripcion: $entregable->cuantos</li>";
  }
  $listah . "</ol>";
@endphp
<table>
  <thead>
    <tr>
      <th colspan="2">PRODUCTOS ENTREGABLES</th>
    </tr>
    <tr>
      <th>Cotribucion a la Formacion de Recursos Humanos</th>
      <th>Productividad Académica</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>{!!$listah!!}</td>
      <td>{!!$listaa!!}</td>
    </tr>
  </tbody>
</table>
</article>
<article>
 <p><b>1.7 Impacto o beneficio en la solución a un problema relacionado con el sector productivo o la generación  del conocimiento científico o tecnológico. </b></p>
 <p>{{$proyecto->impacto_beneficio}}</p>
</article>
<article>
 <p><b>1.8 Metodologia</b></p>
 <p>{{$proyecto->metodologia}}</p>
</article>
<article>
 <p><b>1.9 Programa de actividades, calendarización y presupuesto solicitado</b></p>
<table>
  <thead>
    <tr>
      <th>No.</th>
      <th>Actividad</th>
      <th>Entregables</th>
      <th>Periodo de realizacion</th>
      <th>Monto</th>
    </tr>
  </thead>
  <tbody>
    @php
      $actividades = $proyecto->actividades;
    @endphp
    @foreach ($actividades as $actividad)
      <tr>
         <td>{{$loop->iteration}}</td>
        <td>{{$actividad['actividad']}}</td>
        <td>{{$actividad->entregable->descripcion}}</td>
         <td>{{$actividad->fecha_inicio}} a {{$actividad->fecha_fin}}</td>
         @php
            $suma = 0;
            $gastos=$actividad->gastos;
            foreach ($gastos as $gasto) {
              $suma += $gasto->monto;
            }
         @endphp
        <td style="text-align: right">
          $ {{$suma}}
        </td>
     </tr>
    @endforeach
  </tbody>
</table>
</article>
<p><b>1.10 Vinculación</b></p>
<p>{{$proyecto->tvinculacion}}</p>
<p> @if($proyecto->vinculacion=="") {{"NO "}} @else {{"Si "}} @endif presenta carta de vinculación</p>
</article>
<p><b>1.11 Referencias</b></p>
<p>{{$proyecto->referencias}}</p>
</article>

    <div style=" border: 1px solid black; float: right ; width:50%">
          Profesor Investigador Responsable:
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          Nombre y firma
    </div>



  </body>
</html>
