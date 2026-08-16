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
        <th width="120">
            Consulta
        </th>
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

    <div class="d-grid gap-2">

        <button
            class="btn btn-info btn-sm ver-detalle"
            data-id="{{ $venta->id }}">

            👁 Ver

        </button>

        <a
            href="{{ route('ventas.ticket',$venta) }}"
            target="_blank"
            class="btn btn-secondary btn-sm">

            🧾 Ticket

        </a>

    </div>

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


<div class="modal fade"
     id="detalleVentaModal"
     tabindex="-1">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    🧾 Detalle de la Venta

                </h5>

                <div>

                    <button
                        id="btnImprimirVenta"
                        class="btn btn-primary btn-sm me-2">

                        🖨 Imprimir

                    </button>

                    <button
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

            </div>

            <div class="modal-body">

                <div id="contenidoDetalle">

                    Cargando...

                </div>

            </div>

        </div>

    </div>

</div>
<script>

document.querySelectorAll('.ver-detalle').forEach(boton => {

    boton.addEventListener('click', function () {

        let ventaId = this.dataset.id;
        document.getElementById('btnImprimirVenta').dataset.id = ventaId;

        fetch('/ventas/' + ventaId + '/detalle')

        .then(res => res.json())

        .then(data => {

            let html = `

                <div class="row mb-4">

                    <div class="col-md-8">

                        <h4 class="mb-3">

                            🧾 Detalle de la Venta

                        </h4>

                        <p class="mb-1">
                            <strong>Venta:</strong>
                            #${data.id}
                        </p>

                        <p class="mb-1">
                            <strong>Fecha:</strong>
                            ${data.fecha}
                        </p>

                        <p class="mb-1">
                            <strong>Cliente:</strong>
                            ${data.cliente.nombre} ${data.cliente.apellido}
                        </p>

                    </div>

                    <div class="col-md-4 text-end">

                        <span class="badge bg-warning fs-6">

                            ${data.estado_pago.toUpperCase()}

                        </span>

                    </div>

                </div>

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>Producto</th>

                            <th class="text-center">
                                Cantidad
                            </th>

                            <th class="text-end">
                                Precio
                            </th>

                            <th class="text-end">
                                Subtotal
                            </th>

                        </tr>

                    </thead>

                    <tbody>

            `;

            data.detalles.forEach(detalle => {

                html += `

                    <tr>

                        <td>

                            ${detalle.producto.nombre}

                        </td>

                        <td class="text-center">

                            ${detalle.cantidad}

                        </td>

                        <td class="text-end">

                            $${parseFloat(detalle.precio).toFixed(2)}

                        </td>

                        <td class="text-end">

                            $${parseFloat(detalle.subtotal).toFixed(2)}

                        </td>

                    </tr>

                `;

            });

            html += `

                    </tbody>

                </table>

                <div class="row mt-4">

                    <div class="col-md-6">

                        <div class="card border-primary">

                            <div class="card-body">

                                <h6 class="text-muted">

                                    Total de la venta

                                </h6>

                                <h3 class="text-primary">

                                    $${parseFloat(data.total).toFixed(2)}

                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="card border-danger">

                            <div class="card-body text-end">

                                <h6 class="text-muted">

                                    Saldo pendiente

                                </h6>

                                <h3 class="text-danger">

                                    $${parseFloat(data.saldo_pendiente).toFixed(2)}

                                </h3>

                            </div>

                        </div>

                    </div>

                </div>

            `;

            document.getElementById('contenidoDetalle').innerHTML = html;

            new bootstrap.Modal(
                document.getElementById('detalleVentaModal')
            ).show();

        });

    });

});
document.getElementById('btnImprimirVenta')
.addEventListener('click', function () {

    let contenido =
        document.getElementById('contenidoDetalle').innerHTML;

    let ventana = window.open('', '', 'width=900,height=700');

    ventana.document.write(`
        <html>

        <head>

            <title>Detalle de Venta</title>

            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                rel="stylesheet">

        </head>

        <body class="p-4">

            ${contenido}

        </body>

        </html>
    `);

    ventana.document.close();

    ventana.print();

});

</script>
@endsection