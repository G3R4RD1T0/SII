@extends('layouts.app')
@section('content')

<div class="container">
  <table class="table table-striped">
  <thead>
    <tr>
      <th>Nombre</th>

      <th colspan="2"><center>Acciones</center></th>
    </tr>
  </thead>
  <tbody>
    @foreach($catalagos as $catalago)
    <tr>
      <td>{{$catalago->area}}</td>
      <td>
        <a href="{{action('Coordinador\CrudAreasController@edit', $catalago->id)}}"  class="btn btn-warning">Edit</a>
      <td>
        <form action="{{action('Coordinador\CrudAreasController@destroy', $catalago->id)}}" method="post">
          @csrf
          <input name="_method" type="hidden" value="DELETE">
          <button class="btn btn-danger" type="submit">Delete</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

</div>
@endsection
