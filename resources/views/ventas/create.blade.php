@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">Nueva Venta</h2>

<div class="card">

    <div class="card-header">
        Registrar Venta
    </div>

    <div class="card-body">

        <div class="row mb-4">

            <div class="col-md-6">

                <label class="form-label"><strong>Fecha</strong></label>

                <input
                    type="date"
                    class="form-control"
                    value="{{ date('Y-m-d') }}"
                    readonly>

            </div>

            <div class="col-md-6">

                <label class="form-label"><strong>Usuario</strong></label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ auth()->user()->name }}"
                    readonly>

            </div>

        </div>

        <form>

            <div class="row align-items-end">

                <div class="col-md-6">

                    <label class="form-label">
                        Producto
                    </label>

                    <select class="form-select">

                        <option>
                            Seleccione un producto
                        </option>

                        @foreach($productos as $producto)

                            <option>

                                {{ $producto->nombre }}
                                -
                                Stock: {{ $producto->stock }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label">
                        Cantidad
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        value="1"
                        min="1">

                </div>

                <div class="col-md-2">

                    <button
                        class="btn btn-primary w-100">

                        Agregar

                    </button>

                </div>

            </div>

        </form>

        <hr>

        <table class="table table-bordered">

            <thead class="table-dark">

                <tr>

                    <th>Producto</th>

                    <th>Cantidad</th>

                    <th>Precio</th>

                    <th>Subtotal</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td colspan="4" class="text-center">

                        Todavía no hay productos agregados.

                    </td>

                </tr>

            </tbody>

        </table>

        <div class="text-end">

            <h3>

                Total:

                $0.00

            </h3>

        </div>

    </div>

</div>

@endsection