@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">
    Cuenta corriente
</h2>


<div class="card mb-4">

    <div class="card-body">

        <h4>
            {{ $cliente->nombre }} {{ $cliente->apellido }}
        </h4>

        <p>
            Límite de crédito:
            <strong>
                ${{ number_format($cliente->limite_credito,2) }}
            </strong>
        </p>


        <p>
            Deuda actual:
            <strong class="text-danger">
                ${{ number_format($cliente->deuda_actual,2) }}
            </strong>
        </p>


        <p>
            Crédito disponible:
            <strong class="text-success">
                ${{ number_format($cliente->credito_disponible,2) }}
            </strong>
        </p>

    </div>

</div>



<div class="card">

    <div class="card-body">

        <h4>
            Ventas pendientes
        </h4>


        <table class="table">

            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Saldo pendiente</th>
                    <th>Estado</th>
                </tr>
            </thead>


            <tbody>

            @forelse($ventas as $venta)

                <tr>

                    <td>
                        {{ $venta->fecha }}
                    </td>

                    <td>
                        ${{ number_format($venta->total,2) }}
                    </td>

                    <td>
                        ${{ number_format($venta->saldo_pendiente,2) }}
                    </td>

                    <td>
                        <span class="badge bg-warning">
                            Pendiente
                        </span>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="4">
                        No tiene ventas pendientes
                    </td>
                </tr>

            @endforelse


            </tbody>

        </table>


    </div>

</div>


@endsection