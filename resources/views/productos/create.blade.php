@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">Nuevo Producto</h2>

<form action="{{ route('productos.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    @include('productos.form')

    <button class="btn btn-success">
        Guardar
    </button>

    <a href="{{ route('productos.index') }}" 
       class="btn btn-secondary">
        Cancelar
    </a>

</form>

@endsection