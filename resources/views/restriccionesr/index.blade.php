@extends('layouts.app')
@section('content')
  <section class="content">
    <div class="container">

    <table class="table table-striped">
    <thead>
      <tr>
        <th>Descripcion</th>
        <th>Valor</th>
        <th >Acciones</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($restricciones as $restriccion)
      <tr>
      <td>  {{$restriccion->descripcion}}</td>
      <td>  {{$restriccion->valor}}</td>
      <td>
        <a href="{{action('Coordinador\RestriccionesRegistroController@edit', $restriccion->id)}}" class="btn btn-warning">Edit</a>
      </td>
      </tr>
    @endforeach
  </tbody>
  </table>

  </section>

  </div>
@endsection
