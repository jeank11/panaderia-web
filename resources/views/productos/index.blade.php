@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">
    Productos
</h2>


@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif



<a href="{{ route('productos.create') }}"
   class="btn btn-success mb-3">

    Nuevo producto

</a>



<table class="table table-bordered table-hover bg-white">

<thead class="table-dark">

<tr>

    <th>Imagen</th>
    <th>Código</th>
    <th>Nombre</th>
    <th>Categoría</th>
    <th>Precio Venta</th>
    <th>Stock</th>
    <th>Estado</th>
    <th width="180">
        Acciones
    </th>

</tr>

</thead>



<tbody>


@forelse($productos as $producto)


<tr>


<td>

@if($producto->imagen)

<img
src="{{ asset('storage/'.$producto->imagen) }}"
width="70"
height="70"
style="object-fit:cover"
class="rounded">

@else

<span class="text-muted">

Sin imagen

</span>

@endif


</td>



<td>

{{ $producto->codigo }}

</td>



<td>

{{ $producto->nombre }}

</td>



<td>

{{ $producto->categoria->nombre }}

</td>



<td>

${{ number_format($producto->precio_venta,2) }}

</td>



<td>

{{ $producto->stock }}

</td>



<td>


@if($producto->estado)

<span class="badge bg-success">

Activo

</span>


@else

<span class="badge bg-danger">

Inactivo

</span>


@endif


</td>




<td>


<a href="{{ route('productos.edit',$producto) }}"
   class="btn btn-primary btn-sm">

    Editar

</a>



<form action="{{ route('productos.estado',$producto) }}"
      method="POST"
      style="display:inline">

@csrf

@method('PATCH')


<button class="btn btn-warning btn-sm">

@if($producto->estado)

Desactivar

@else

Activar

@endif

</button>


</form>


</td>


</tr>



@empty


<tr>

<td colspan="8"
class="text-center">

No existen productos registrados.

</td>

</tr>


@endforelse



</tbody>


</table>



{{ $productos->links() }}



@endsection