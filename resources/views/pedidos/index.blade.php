@extends('layouts.app')

@section('contenido')

<div class="container">

    <h2 class="mb-4">

        📦 Pedidos

    </h2>


    @if(session('success'))

    <div class="alert alert-success">

        {{ session('success') }}

    </div>

    @endif



    @if($pedidos->count())


    <div class="card shadow">

        <div class="card-body">


            <table class="table table-bordered table-hover">


                <thead class="table-dark">

                    <tr>

                        <th>Código</th>

                        <th>Cliente</th>

                        <th>Entrega</th>

                        <th>Total</th>

                        <th>Estado</th>

                        <th width="120">
                            Acción
                        </th>

                    </tr>

                </thead>



                <tbody>


                @foreach($pedidos as $pedido)


                <tr>


                    <td>

                        <strong>

                            {{ $pedido->codigo }}

                        </strong>

                    </td>



                    <td>

                        {{ $pedido->cliente->nombre_completo }}

                    </td>



                    <td>


                        {{ \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y') }}

                        <br>

                        <small>

                            {{ $pedido->hora_entrega }}

                        </small>


                    </td>



                    <td>

                        ${{ number_format($pedido->total,2) }}

                    </td>



<td>

<form
    action="{{ route('pedidos.estado', $pedido) }}"
    method="POST">

    @csrf
    @method('PATCH')

    <select
        name="estado"
        class="form-select form-select-sm"
        onchange="this.form.submit()">

        <option value="Pendiente"
            {{ $pedido->estado == 'Pendiente' ? 'selected' : '' }}>
            🟡 Pendiente
        </option>

        <option value="Preparando"
            {{ $pedido->estado == 'Preparando' ? 'selected' : '' }}>
            🔵 Preparando
        </option>

        <option value="Listo"
            {{ $pedido->estado == 'Listo' ? 'selected' : '' }}>
            🟢 Listo
        </option>

        <option value="Entregado"
            {{ $pedido->estado == 'Entregado' ? 'selected' : '' }}>
            ✅ Entregado
        </option>

        <option value="Cancelado"
            {{ $pedido->estado == 'Cancelado' ? 'selected' : '' }}>
            ❌ Cancelado
        </option>

    </select>

</form>

</td>



                    <td>


                        <a
href="{{ route('pedidos.show', $pedido) }}"
class="btn btn-primary btn-sm">

    Ver

</a>


                    </td>


                </tr>


                @endforeach


                </tbody>


            </table>


            {{ $pedidos->links() }}


        </div>

    </div>


    @else


    <div class="alert alert-info">

        Todavía no existen pedidos registrados.

    </div>


    @endif


</div>


@endsection