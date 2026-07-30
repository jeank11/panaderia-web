@extends('layouts.app')

@section('contenido')

@if(session('success'))

<div class="alert alert-success">
    {{ session('success') }}
</div>

@endif


@if(session('error'))

<div class="alert alert-danger">
    {{ session('error') }}
</div>

@endif

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

        @if($cliente->deuda_actual > 0)

<form action="{{ route('clientes.pago.global',$cliente) }}"
      method="POST"
      class="mt-3">

    @csrf

    <button class="btn btn-success">
         💰 Cobrar deuda completa (${{ number_format($cliente->deuda_actual,2) }})
    </button>

</form>

@endif

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
        <th>Acción</th>
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
                    <td>

    <form action="{{ route('clientes.pago.cancelar',
    [$cliente,$venta]) }}"
    method="POST"
    class="mt-2">

        @csrf

        <button class="btn btn-danger btn-sm">
            Cancelar deuda completa
        </button>

    </form>

</td>
                    <td>

    <form action="{{ route('clientes.pago.store',$cliente) }}"
          method="POST">

        @csrf

        <input type="hidden"
               name="venta_id"
               value="{{ $venta->id }}">


        <input type="number"
               step="0.01"
               name="monto"
               class="form-control mb-2"
               placeholder="Monto">


        <input type="date"
               name="fecha"
               class="form-control mb-2"
               value="{{ date('Y-m-d') }}">


        <input type="text"
               name="observacion"
               class="form-control mb-2"
               placeholder="Observación">


        <button class="btn btn-success btn-sm">
            Registrar pago
        </button>

    </form>

</td>

                </tr>

            @empty

                <tr>
                    <td colspan="5">
                        No tiene ventas pendientes
                    </td>
                </tr>

            @endforelse


            </tbody>

        </table>


    </div>

</div>

<div class="card mt-4">

    <div class="card-body">

        <h4>
            Historial de pagos
        </h4>


        <table class="table">

            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Observación</th>
                </tr>
            </thead>


            <tbody>

            @forelse($pagos as $pago)

                <tr>

                    <td>
                        {{ $pago->fecha }}
                    </td>


                    <td>
                        ${{ number_format($pago->monto,2) }}
                    </td>


                    <td>
                        {{ $pago->observacion ?? 'Sin observación' }}
                    </td>

                </tr>


            @empty

                <tr>
                    <td colspan="3">
                        No hay pagos registrados
                    </td>
                </tr>

            @endforelse


            </tbody>

        </table>


    </div>

</div>



@endsection