@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">
    Editar Producto
</h2>

<form action="{{ route('productos.update',$producto) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    @method('PUT')

    @include('productos.form')

    <button class="btn btn-primary">
        Actualizar
    </button>

    <a href="{{ route('productos.index') }}"
       class="btn btn-secondary">

        Cancelar

    </a>

</form>

@endsection