@extends('layouts.app')

@section('contenido')

<div class="container">

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">

        📊 Producción

    </h2>


    <div>

        <a
            href="{{ route('pedidos.index') }}"
            class="btn btn-secondary"
        >

            ← Pedidos

        </a>


       <a
    href="{{ route('pedidos.produccion.imprimir', [
        'fecha' => $fecha,
        'direccion' => $direccion
    ]) }}"
    target="_blank"
    class="btn btn-dark"
>
    🖨️ Imprimir producción
</a>

    </div>

</div>


    {{-- FILTRO DE FECHA --}}

    <div class="card shadow mb-4">

        <div class="card-body">

            <form
                action="{{ route('pedidos.produccion') }}"
                method="GET"
            >

<div class="row align-items-end">

    <div class="col-md-4">

        <label class="form-label">

            <strong>
                Fecha de producción
            </strong>

        </label>

        <input
            type="date"
            name="fecha"
            class="form-control"
            value="{{ $fecha }}"
            required
        >

    </div>


    <div class="col-md-5">

        <label class="form-label">

            <strong>
                Dirección
            </strong>

        </label>

        <input
            type="text"
            name="direccion"
            class="form-control"
            value="{{ $direccion ?? '' }}"
            placeholder="Ej: Piedras Coloradas"
        >

    </div>


    <div class="col-md-3">

        <button
            type="submit"
            class="btn btn-primary w-100"
        >

            🔍 Consultar

        </button>

    </div>

</div>

            </form>

        </div>

    </div>
@if($direccion)

    <div class="alert alert-info">

        📍 Mostrando producción para:

        <strong>
            {{ $direccion }}
        </strong>

    </div>

@endif

    {{-- RESUMEN --}}

    <div class="row mb-4">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-body text-center">

                    <div class="text-muted">

                        Pedidos para esta fecha

                    </div>

                    <h2 class="mb-0">

                        {{ $pedidos->count() }}

                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-body text-center">

                    <div class="text-muted">

                        Total de unidades

                    </div>

                    <h2 class="mb-0">

                        {{ collect($produccion)->sum('cantidad') }}

                    </h2>

                </div>

            </div>

        </div>

    </div>


    {{-- PRODUCTOS --}}

    <div class="card shadow">

        <div class="card-header bg-dark text-white">

            <strong>

                📦 Productos a preparar

            </strong>

        </div>


        <div class="card-body">


            @if(count($produccion))


                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Producto
                            </th>

                            <th
                                class="text-center"
                                width="200"
                            >
                                Cantidad
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @foreach($produccion as $item)


                        <tr>

                            <td>

                                <strong>

                                    {{ $item['producto']->nombre }}

                                </strong>

                            </td>


                            <td class="text-center">

                                <span
                                    class="badge bg-primary fs-6"
                                >

                                    {{ $item['cantidad'] }}

                                </span>

                            </td>

                        </tr>


                    @endforeach


                    </tbody>

                </table>


            @else


                <div class="alert alert-info mb-0">

                    📭 No hay pedidos pendientes de producción
                    para esta fecha.

                </div>


            @endif


        </div>

    </div>

</div>

@endsection