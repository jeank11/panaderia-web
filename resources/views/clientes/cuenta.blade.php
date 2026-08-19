@extends('layouts.app')

@section('contenido')

{{-- ============================================================
     MENSAJES
============================================================ --}}

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



{{-- ============================================================
     TÍTULO
============================================================ --}}

<h2 class="mb-4">
    Cuenta corriente
</h2>



{{-- ============================================================
     BUSCADOR
============================================================ --}}

<div class="card mb-4 shadow-sm">

    <div class="card-body">

        <h5 class="mb-3">
            🔎 Buscar ventas pendientes
        </h5>


        <form
            method="GET"
            action="{{ route('clientes.cuenta', $cliente) }}"
        >

            <div class="row g-2">

                <div class="col-md-9">

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Buscar por número de venta o fecha..."
                        value="{{ $buscar }}"
                    >

                </div>


                <div class="col-md-3">

                    <div class="d-grid">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            🔎 Buscar

                        </button>

                    </div>

                </div>

            </div>


            @if($buscar)

                <div class="mt-3">

                    <a
                        href="{{ route('clientes.cuenta', $cliente) }}"
                        class="btn btn-outline-secondary btn-sm"
                    >

                        ✖ Limpiar búsqueda

                    </a>

                </div>

            @endif

        </form>

    </div>

</div>



{{-- ============================================================
     INFORMACIÓN DEL CLIENTE
============================================================ --}}

<div class="card mb-4 shadow-sm">

    <div class="card-body">

        <h4>

            {{ $cliente->nombre }}
            {{ $cliente->apellido }}

        </h4>


        <p>

            Límite de crédito:

            <strong>

                ${{ number_format(
                    $cliente->limite_credito,
                    2
                ) }}

            </strong>

        </p>


        <p>

            Deuda actual:

            <strong class="text-danger">

                ${{ number_format(
                    $cliente->deuda_actual,
                    2
                ) }}

            </strong>

        </p>


        <p>

            Crédito disponible:

            <strong class="text-success">

                ${{ number_format(
                    $cliente->credito_disponible,
                    2
                ) }}

            </strong>

        </p>



        {{-- ====================================================
             PAGO GLOBAL
        ===================================================== --}}

        @if($cliente->deuda_actual > 0)

            <form
                action="{{ route('clientes.pago.global', $cliente) }}"
                method="POST"
                class="mt-4"
            >

                @csrf


                <div class="row align-items-end">

                    {{-- MONTO --}}

                    <div class="col-md-4">

                        <label class="form-label fw-bold">

                            💰 Monto recibido

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>


                            <input
                                type="number"
                                name="monto"
                                class="form-control"
                                step="0.01"
                                min="0.01"
                                max="{{ $cliente->deuda_actual }}"
                                placeholder="0.00"
                                required
                            >

                        </div>


                        <small class="text-muted">

                            Deuda actual:

                            ${{ number_format(
                                $cliente->deuda_actual,
                                2
                            ) }}

                        </small>

                    </div>


                    {{-- FECHA --}}

                    <div class="col-md-4 mt-3 mt-md-0">

                        <label class="form-label fw-bold">

                            📅 Fecha del pago

                        </label>


                        <input
                            type="date"
                            name="fecha"
                            class="form-control"
                            value="{{ date('Y-m-d') }}"
                            required
                        >

                    </div>


                    {{-- BOTÓN --}}

                    <div class="col-md-4 mt-3 mt-md-0">

                        <button
                            type="submit"
                            class="btn btn-success w-100"
                        >

                            💰 Registrar pago

                        </button>

                    </div>

                </div>


                {{-- OBSERVACIÓN --}}

                <div class="mt-3">

                    <label class="form-label fw-bold">

                        📝 Observación

                    </label>


                    <input
                        type="text"
                        name="observacion"
                        class="form-control"
                        placeholder="Ej: Pago parcial de cuenta corriente"
                    >

                </div>

            </form>

        @endif

    </div>

</div>



{{-- ============================================================
     VENTAS PENDIENTES
============================================================ --}}

<div class="card shadow-sm border-0">

    <div class="card-body">


        <div class="d-flex flex-column flex-md-row
                    justify-content-between
                    align-items-md-center
                    mb-4"
        >

            <div>

                <h4 class="mb-1">

                    💳 Ventas pendientes

                </h4>


                <p class="text-muted mb-0">

                    Ventas que todavía tienen saldo pendiente.

                </p>

            </div>


            <div class="mt-2 mt-md-0">

                <span class="badge bg-danger fs-6">

                    {{ $ventas->count() }}

                    {{
                        $ventas->count() == 1
                            ? 'venta pendiente'
                            : 'ventas pendientes'
                    }}

                </span>

            </div>

        </div>



        @if($ventas->count())


            <div class="table-responsive">

                <table class="table table-hover align-middle">


                    <thead class="table-dark">

                        <tr>

                            <th>
                                📅 Fecha
                            </th>

                            <th>
                                🧾 Venta
                            </th>

                            <th class="text-end">
                                💰 Total
                            </th>

                            <th class="text-end">
                                🔴 Saldo pendiente
                            </th>

                            <th class="text-center">
                                Estado
                            </th>

                            <th class="text-center">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @foreach($ventas as $venta)


                        {{-- =================================================
                             VENTA
                        ================================================== --}}

                        <tr>


                            {{-- FECHA --}}

                            <td>

                                <strong>

                                    {{
                                        \Carbon\Carbon::parse(
                                            $venta->fecha
                                        )->format('d/m/Y')
                                    }}

                                </strong>


                                <div class="text-muted small">

                                    {{
                                        \Carbon\Carbon::parse(
                                            $venta->fecha
                                        )->format('H:i')
                                    }}

                                </div>

                            </td>


                            {{-- VENTA --}}

                            <td>

                                <span class="fw-bold">

                                    #{{ $venta->id }}

                                </span>


                                @if($venta->pedido)

                                    <div class="text-muted small">

                                        📦 Pedido #{{ $venta->pedido->id }}

                                    </div>

                                @endif

                            </td>


                            {{-- TOTAL --}}

                            <td class="text-end">

                                <strong>

                                    ${{ number_format(
                                        $venta->total,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            {{-- SALDO --}}

                            <td class="text-end">

                                <strong class="text-danger fs-5">

                                    ${{ number_format(
                                        $venta->saldo_pendiente,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            {{-- ESTADO --}}

                            <td class="text-center">

                                @if(
                                    $venta->estado_pago == 'parcial'
                                )

                                    <span class="badge bg-warning text-dark">

                                        🟡 Pago parcial

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        🔴 Pendiente

                                    </span>

                                @endif

                            </td>


                            {{-- ACCIONES --}}

                            <td>

                                <div class="d-flex flex-column gap-2">


                                    {{-- TICKET --}}

                                    <a
                                        href="{{
                                            route(
                                                'ventas.ticket',
                                                $venta
                                            )
                                        }}"
                                        target="_blank"
                                        class="btn btn-secondary btn-sm"
                                    >

                                        🧾 Ticket

                                    </a>

                                </div>

                            </td>


                        </tr>



                        {{-- =================================================
                             FORMULARIO DE PAGO
                        ================================================== --}}

                        <tr class="bg-light">

                            <td colspan="6">

                                <div class="p-3">


                                    <div class="row align-items-end g-3">


                                        {{-- INFORMACIÓN --}}

                                        <div class="col-lg-3">

                                            <div class="small text-muted">

                                                Saldo a pagar

                                            </div>


                                            <div
                                                class="fs-5 fw-bold text-danger"
                                            >

                                                ${{ number_format(
                                                    $venta->saldo_pendiente,
                                                    2
                                                ) }}

                                            </div>

                                        </div>



                                        {{-- FORMULARIO --}}

                                        <div class="col-lg-5">

                                            <form
                                                action="{{
                                                    route(
                                                        'clientes.pago.store',
                                                        $cliente
                                                    )
                                                }}"
                                                method="POST"
                                            >

                                                @csrf


                                                <input
                                                    type="hidden"
                                                    name="venta_id"
                                                    value="{{ $venta->id }}"
                                                >


                                                <div class="row g-2">


                                                    {{-- MONTO --}}

                                                    <div class="col-md-6">

                                                        <label
                                                            class="form-label small fw-bold"
                                                        >

                                                            💰 Monto recibido

                                                        </label>


                                                        <div class="input-group">

                                                            <span
                                                                class="input-group-text"
                                                            >

                                                                $

                                                            </span>


                                                            <input
                                                                type="number"
                                                                step="0.01"
                                                                min="0.01"
                                                                max="{{
                                                                    $venta->saldo_pendiente
                                                                }}"
                                                                name="monto"
                                                                class="form-control"
                                                                placeholder="0.00"
                                                                required
                                                            >

                                                        </div>

                                                    </div>



                                                    {{-- FECHA --}}

                                                    <div class="col-md-6">

                                                        <label
                                                            class="form-label small fw-bold"
                                                        >

                                                            📅 Fecha

                                                        </label>


                                                        <input
                                                            type="date"
                                                            name="fecha"
                                                            class="form-control"
                                                            value="{{
                                                                date('Y-m-d')
                                                            }}"
                                                            required
                                                        >

                                                    </div>

                                                </div>


                                                {{-- OBSERVACIÓN --}}

                                                <div class="mt-2">

                                                    <input
                                                        type="text"
                                                        name="observacion"
                                                        class="form-control form-control-sm"
                                                        placeholder="📝 Observación del pago (opcional)"
                                                    >

                                                </div>


                                                <button
                                                    type="submit"
                                                    class="btn btn-success btn-sm mt-2"
                                                >

                                                    💰 Registrar pago

                                                </button>

                                            </form>

                                        </div>



                                        {{-- CANCELAR DEUDA --}}

                                        <div class="col-lg-4">

                                            <div
                                                class="d-flex flex-column gap-2"
                                            >

                                                <div class="small text-muted">

                                                    Si el cliente paga la totalidad:

                                                </div>


                                                <form
                                                    action="{{
                                                        route(
                                                            'clientes.pago.cancelar',
                                                            [
                                                                $cliente,
                                                                $venta
                                                            ]
                                                        )
                                                    }}"
                                                    method="POST"
                                                    onsubmit="return confirm('¿Está seguro de cancelar completamente esta deuda?');"
                                                >

                                                    @csrf


                                                    <button
                                                        type="submit"
                                                        class="btn btn-outline-danger btn-sm w-100"
                                                    >

                                                        ❌ Cancelar deuda completa

                                                    </button>

                                                </form>

                                            </div>

                                        </div>


                                    </div>

                                </div>

                            </td>

                        </tr>


                    @endforeach


                    </tbody>

                </table>

            </div>


        @else


            <div class="text-center py-5">

                <div style="font-size:60px;">
                    🎉
                </div>


                <h5 class="mt-3">

                    No hay ventas pendientes

                </h5>


                <p class="text-muted mb-0">

                    Este cliente no tiene deudas pendientes.

                </p>

            </div>


        @endif

    </div>

</div>



{{-- ============================================================
     HISTORIAL DE PAGOS
============================================================ --}}

<div class="card mt-4 shadow-sm border-0">

    <div class="card-body">


        <div class="d-flex justify-content-between
                    align-items-center mb-4"
        >

            <div>

                <h4 class="mb-1">

                    💰 Historial de pagos

                </h4>


                <p class="text-muted mb-0">

                    Pagos registrados para este cliente.

                </p>

            </div>


            <span class="badge bg-primary fs-6">

                {{ $pagos->count() }}

                {{
                    $pagos->count() == 1
                        ? 'pago'
                        : 'pagos'
                }}

            </span>

        </div>



        @if($pagos->count())


            <div class="table-responsive">

                <table class="table table-hover align-middle">


                    <thead class="table-dark">

                        <tr>

                            <th>
                                📅 Fecha
                            </th>

                            <th>
                                🕐 Hora
                            </th>

                            <th class="text-end">
                                💰 Monto
                            </th>

                            <th>
                                📝 Observación
                            </th>


                            <th class="text-center">
                                Acción
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @foreach($pagos as $pago)


                        <tr>


                            {{-- FECHA --}}

                            <td>

                                <strong>

                                    {{
                                        \Carbon\Carbon::parse(
                                            $pago->fecha
                                        )->format('d/m/Y')
                                    }}

                                </strong>

                            </td>


                            {{-- HORA --}}

                            <td>

                                <span class="text-muted">

                                    🕐

                                    {{
                                        $pago->created_at
                                            ? $pago->created_at->format('H:i')
                                            : '--:--'
                                    }}

                                </span>

                            </td>


                            {{-- MONTO --}}

                            <td class="text-end">

                                <strong class="text-success fs-5">

                                    ${{ number_format(
                                        $pago->monto,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            {{-- OBSERVACIÓN --}}

                            <td>

                                {{
                                    $pago->observacion
                                        ?? 'Sin observación'
                                }}

                            </td>


                            {{-- ACCIÓN --}}

                            <td class="text-center">

                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm ver-pago"
                                    data-url="{{
                                        route(
                                            'pagos.detalle',
                                            ['pago' => $pago->id]
                                        )
                                    }}"
                                >

                                    👁️ Ver detalle

                                </button>

                            </td>


                        </tr>


                    @endforeach


                    </tbody>

                </table>

            </div>


        @else


            <div class="text-center py-5">

                <div style="font-size:55px;">
                    💰
                </div>


                <h5 class="mt-3">

                    No hay pagos registrados

                </h5>


                <p class="text-muted mb-0">

                    Este cliente todavía no tiene pagos registrados.

                </p>

            </div>


        @endif

    </div>

</div>



{{-- ============================================================
     MODAL DETALLE COMPLETO DEL PAGO
============================================================ --}}

<div
    class="modal fade"
    id="detallePagoModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-xl">

        <div class="modal-content">


            {{-- HEADER --}}

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">

                    💰 Detalle del pago

                </h5>


                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            {{-- BODY --}}

            <div class="modal-body">


                {{-- INFORMACIÓN GENERAL --}}

                <div class="row g-3 mb-4">


                    {{-- FECHA --}}

                    <div class="col-md-4">

                        <div class="card border-0 bg-light">

                            <div class="card-body">

                                <small class="text-muted">

                                    📅 Fecha y hora

                                </small>


                                <h5
                                    id="detallePagoFecha"
                                    class="mb-0"
                                >

                                    -

                                </h5>

                            </div>

                        </div>

                    </div>


                    {{-- MONTO --}}

                    <div class="col-md-4">

                        <div class="card border-0 bg-light">

                            <div class="card-body">

                                <small class="text-muted">

                                    💰 Monto pagado

                                </small>


                                <h4
                                    id="detallePagoMonto"
                                    class="text-success mb-0"
                                >

                                    $0.00

                                </h4>

                            </div>

                        </div>

                    </div>


                    {{-- OBSERVACIÓN --}}

                    <div class="col-md-4">

                        <div class="card border-0 bg-light">

                            <div class="card-body">

                                <small class="text-muted">

                                    📝 Observación

                                </small>


                                <div
                                    id="detallePagoObservacion"
                                    class="small"
                                >

                                    -

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- VENTAS --}}

                <h5 class="mb-3">

                    🧾 Ventas asociadas

                </h5>


                <div id="detallePagoVentas">

                    <div class="text-center py-4">

                        <div
                            class="spinner-border text-primary"
                            role="status"
                        ></div>


                        <div class="mt-2 text-muted">

                            Cargando detalle...

                        </div>

                    </div>

                </div>

            </div>



            {{-- FOOTER --}}

            <div class="modal-footer">


                <strong
                    class="me-auto"
                    id="detallePagoTotal"
                >

                    Total aplicado: $0.00

                </strong>


                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >

                    Cerrar

                </button>

            </div>


        </div>

    </div>

</div>



{{-- ============================================================
     MODAL DETALLE DE VENTA
============================================================ --}}

<div
    class="modal fade"
    id="detalleVentaModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-xl">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    🧾 Detalle de la Venta

                </h5>


                <div>

                    <button
                        id="btnImprimirVenta"
                        class="btn btn-primary btn-sm me-2"
                    >

                        🖨 Imprimir

                    </button>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

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



{{-- ============================================================
     JAVASCRIPT - DETALLE DEL PAGO
============================================================ --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    document.querySelectorAll('.ver-pago').forEach(function (boton) {


        boton.addEventListener('click', function () {


            const url = this.dataset.url;


            const contenedor =
                document.getElementById(
                    'detallePagoVentas'
                );


            const modalElement =
                document.getElementById(
                    'detallePagoModal'
                );


            /*
            |--------------------------------------------------------------------------
            | Verificar URL
            |--------------------------------------------------------------------------
            */

            if (!url) {

                console.error(
                    'El botón no tiene data-url.'
                );


                contenedor.innerHTML = `

                    <div class="alert alert-danger">

                        ❌ No se encontró la URL
                        del detalle del pago.

                    </div>

                `;


                bootstrap.Modal
                    .getOrCreateInstance(
                        modalElement
                    )
                    .show();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Restablecer información
            |--------------------------------------------------------------------------
            */

            document.getElementById(
                'detallePagoFecha'
            ).textContent = '-';


            document.getElementById(
                'detallePagoMonto'
            ).textContent = '$0.00';


            document.getElementById(
                'detallePagoObservacion'
            ).textContent = '-';


            document.getElementById(
                'detallePagoTotal'
            ).textContent =
                'Total aplicado: $0.00';


            /*
            |--------------------------------------------------------------------------
            | Cargando
            |--------------------------------------------------------------------------
            */

            contenedor.innerHTML = `

                <div class="text-center py-5">

                    <div
                        class="spinner-border text-primary"
                        role="status"
                    ></div>


                    <div class="mt-2 text-muted">

                        Cargando detalle...

                    </div>

                </div>

            `;


            /*
            |--------------------------------------------------------------------------
            | Mostrar modal
            |--------------------------------------------------------------------------
            */

            const modal =
                bootstrap.Modal
                    .getOrCreateInstance(
                        modalElement
                    );


            modal.show();


            /*
            |--------------------------------------------------------------------------
            | AJAX
            |--------------------------------------------------------------------------
            */

            fetch(url, {

                method: 'GET',

                headers: {

                    'Accept':
                        'application/json',

                    'X-Requested-With':
                        'XMLHttpRequest'

                }

            })


            .then(function (response) {


                if (!response.ok) {

                    throw new Error(
                        'HTTP ' +
                        response.status
                    );

                }


                return response.json();

            })


            .then(function (data) {


                console.log(
                    'Detalle del pago:',
                    data
                );


                /*
                |--------------------------------------------------------------------------
                | DATOS GENERALES
                |--------------------------------------------------------------------------
                */

                document.getElementById(
                    'detallePagoFecha'
                ).textContent =
                    data.fecha || '-';


                document.getElementById(
                    'detallePagoMonto'
                ).textContent =
                    '$' +
                    parseFloat(
                        data.monto || 0
                    ).toFixed(2);


                document.getElementById(
                    'detallePagoObservacion'
                ).textContent =
                    data.observacion ||
                    'Sin observación';


                /*
                |--------------------------------------------------------------------------
                | VENTAS
                |--------------------------------------------------------------------------
                */

                let html = '';

                let totalAplicado = 0;


                if (
                    data.ventas &&
                    data.ventas.length > 0
                ) {


                    data.ventas.forEach(
                        function (venta) {


                            const montoAplicado =
                                parseFloat(
                                    venta.monto_aplicado ||
                                    0
                                );


                            totalAplicado +=
                                montoAplicado;


                            html += `

                                <div
                                    class="card mb-4 shadow-sm"
                                >

                                    <div
                                        class="card-header bg-light"
                                    >

                                        <div
                                            class="d-flex
                                            justify-content-between
                                            align-items-center"
                                        >

                                            <strong>

                                                🧾 Venta
                                                #${venta.id}

                                            </strong>


                                            <span
                                                class="badge bg-primary"
                                            >

                                                Pago aplicado:
                                                $${montoAplicado.toFixed(2)}

                                            </span>

                                        </div>

                                    </div>


                                    <div class="card-body">


                                        <div class="row mb-4">


                                            <div class="col-md-4">

                                                <small
                                                    class="text-muted"
                                                >

                                                    📅 Fecha de venta

                                                </small>


                                                <div class="fw-bold">

                                                    ${
                                                        venta.fecha
                                                        || '-'
                                                    }

                                                </div>

                                            </div>


                                            <div class="col-md-4">

                                                <small
                                                    class="text-muted"
                                                >

                                                    💰 Total de venta

                                                </small>


                                                <div class="fw-bold">

                                                    $${parseFloat(
                                                        venta.total ||
                                                        0
                                                    ).toFixed(2)}

                                                </div>

                                            </div>


                                            <div class="col-md-4">

                                                <small
                                                    class="text-muted"
                                                >

                                                    🔴 Saldo pendiente

                                                </small>


                                                <div
                                                    class="fw-bold
                                                    text-danger"
                                                >

                                                    $${parseFloat(
                                                        venta.saldo_pendiente ||
                                                        0
                                                    ).toFixed(2)}

                                                </div>

                                            </div>


                                        </div>


                                        <h6 class="mb-3">

                                            🥖 Productos de la venta

                                        </h6>

                            `;


                            /*
                            |--------------------------------------------------
                            | PRODUCTOS
                            |--------------------------------------------------
                            */

                            if (
                                venta.detalles &&
                                venta.detalles.length > 0
                            ) {


                                html += `

                                    <div
                                        class="table-responsive"
                                    >

                                        <table
                                            class="table
                                            table-bordered
                                            table-sm
                                            align-middle"
                                        >

                                            <thead
                                                class="table-dark"
                                            >

                                                <tr>

                                                    <th>
                                                        Producto
                                                    </th>

                                                    <th
                                                        class="text-center"
                                                    >
                                                        Cantidad
                                                    </th>

                                                    <th
                                                        class="text-end"
                                                    >
                                                        Precio
                                                    </th>

                                                    <th
                                                        class="text-end"
                                                    >
                                                        Subtotal
                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody>

                                `;


                                venta.detalles.forEach(
                                    function (detalle) {


                                        html += `

                                            <tr>

                                                <td>

                                                    ${
                                                        detalle.producto
                                                        ? detalle.producto.nombre
                                                        : 'Producto eliminado'
                                                    }

                                                </td>


                                                <td
                                                    class="text-center"
                                                >

                                                    ${
                                                        detalle.cantidad
                                                    }

                                                </td>


                                                <td
                                                    class="text-end"
                                                >

                                                    $${parseFloat(
                                                        detalle.precio ||
                                                        0
                                                    ).toFixed(2)}

                                                </td>


                                                <td
                                                    class="text-end"
                                                >

                                                    $${parseFloat(
                                                        detalle.subtotal ||
                                                        0
                                                    ).toFixed(2)}

                                                </td>

                                            </tr>

                                        `;

                                    }
                                );


                                html += `

                                            </tbody>

                                        </table>

                                    </div>

                                `;


                            } else {


                                html += `

                                    <div
                                        class="alert alert-warning"
                                    >

                                        ⚠️ Esta venta no tiene
                                        productos asociados.

                                    </div>

                                `;

                            }


                            html += `

                                    </div>

                                </div>

                            `;


                        }
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | TOTAL
                    |--------------------------------------------------------------------------
                    */

                    document.getElementById(
                        'detallePagoTotal'
                    ).textContent =
                        'Total aplicado: $' +
                        totalAplicado.toFixed(2);


                } else {


                    html = `

                        <div
                            class="alert alert-info"
                        >

                            ℹ️ Este pago no tiene
                            ventas asociadas.

                        </div>

                    `;


                    document.getElementById(
                        'detallePagoTotal'
                    ).textContent =
                        'Total aplicado: $0.00';

                }


                contenedor.innerHTML = html;


            })


            .catch(function (error) {


                console.error(
                    'Error al cargar detalle:',
                    error
                );


                contenedor.innerHTML = `

                    <div
                        class="alert alert-danger"
                    >

                        ❌ No se pudo cargar
                        el detalle del pago.

                        <div class="small mt-2">

                            ${error.message}

                        </div>

                    </div>

                `;

            });


        });

    });


});

</script>



{{-- ============================================================
     JAVASCRIPT - DETALLE DE VENTA
============================================================ --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    document.querySelectorAll('.ver-detalle')
        .forEach(function (boton) {


            boton.addEventListener(
                'click',
                function () {


                    const ventaId =
                        this.dataset.id;


                    document.getElementById(
                        'btnImprimirVenta'
                    ).dataset.id =
                        ventaId;


                    fetch(
                        '/ventas/' +
                        ventaId +
                        '/detalle'
                    )


                    .then(function (response) {

                        if (!response.ok) {

                            throw new Error(
                                'HTTP ' +
                                response.status
                            );

                        }


                        return response.json();

                    })


                    .then(function (data) {


                        let html = `

                            <div class="row mb-4">

                                <div class="col-md-8">

                                    <h4 class="mb-3">

                                        🧾 Detalle de la Venta

                                    </h4>


                                    <p class="mb-1">

                                        <strong>
                                            Venta:
                                        </strong>

                                        #${data.id}

                                    </p>


                                    <p class="mb-1">

                                        <strong>
                                            Fecha:
                                        </strong>

                                        ${data.fecha}

                                    </p>


                                    <p class="mb-1">

                                        <strong>
                                            Cliente:
                                        </strong>

                                        ${data.cliente.nombre}
                                        ${data.cliente.apellido}

                                    </p>

                                </div>


                                <div
                                    class="col-md-4 text-end"
                                >

                                    <span
                                        class="badge bg-warning fs-6"
                                    >

                                        ${data.estado_pago.toUpperCase()}

                                    </span>

                                </div>

                            </div>


                            <table
                                class="table
                                table-bordered
                                table-hover
                                align-middle"
                            >

                                <thead
                                    class="table-dark"
                                >

                                    <tr>

                                        <th>
                                            Producto
                                        </th>

                                        <th
                                            class="text-center"
                                        >
                                            Cantidad
                                        </th>

                                        <th
                                            class="text-end"
                                        >
                                            Precio
                                        </th>

                                        <th
                                            class="text-end"
                                        >
                                            Subtotal
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                        `;


                        data.detalles.forEach(
                            function (detalle) {


                                html += `

                                    <tr>

                                        <td>

                                            ${
                                                detalle.producto
                                                ? detalle.producto.nombre
                                                : 'Producto eliminado'
                                            }

                                        </td>


                                        <td
                                            class="text-center"
                                        >

                                            ${detalle.cantidad}

                                        </td>


                                        <td
                                            class="text-end"
                                        >

                                            $${parseFloat(
                                                detalle.precio
                                            ).toFixed(2)}

                                        </td>


                                        <td
                                            class="text-end"
                                        >

                                            $${parseFloat(
                                                detalle.subtotal
                                            ).toFixed(2)}

                                        </td>

                                    </tr>

                                `;

                            }
                        );


                        html += `

                                </tbody>

                            </table>


                            <div class="row mt-4">


                                <div class="col-md-6">

                                    <div
                                        class="card border-primary"
                                    >

                                        <div class="card-body">

                                            <h6
                                                class="text-muted"
                                            >

                                                Total de la venta

                                            </h6>


                                            <h3
                                                class="text-primary"
                                            >

                                                $${parseFloat(
                                                    data.total
                                                ).toFixed(2)}

                                            </h3>

                                        </div>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <div
                                        class="card border-danger"
                                    >

                                        <div
                                            class="card-body text-end"
                                        >

                                            <h6
                                                class="text-muted"
                                            >

                                                Saldo pendiente

                                            </h6>


                                            <h3
                                                class="text-danger"
                                            >

                                                $${parseFloat(
                                                    data.saldo_pendiente
                                                ).toFixed(2)}

                                            </h3>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        `;


                        document.getElementById(
                            'contenidoDetalle'
                        ).innerHTML = html;


                        new bootstrap.Modal(
                            document.getElementById(
                                'detalleVentaModal'
                            )
                        ).show();


                    })


                    .catch(function (error) {


                        console.error(
                            'Error detalle venta:',
                            error
                        );


                        document.getElementById(
                            'contenidoDetalle'
                        ).innerHTML = `

                            <div
                                class="alert alert-danger"
                            >

                                ❌ No se pudo cargar
                                el detalle de la venta.

                            </div>

                        `;

                    });

                });

        });


});



/*
|--------------------------------------------------------------------------
| IMPRIMIR DETALLE DE VENTA
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const boton =
            document.getElementById(
                'btnImprimirVenta'
            );


        if (!boton) {

            return;

        }


        boton.addEventListener(
            'click',
            function () {


                const contenido =
                    document.getElementById(
                        'contenidoDetalle'
                    ).innerHTML;


                const ventana =
                    window.open(
                        '',
                        '',
                        'width=900,height=700'
                    );


                ventana.document.write(`

                    <html>

                    <head>

                        <title>
                            Detalle de Venta
                        </title>


                        <link
                            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                            rel="stylesheet"
                        >

                    </head>


                    <body class="p-4">

                        ${contenido}

                    </body>

                    </html>

                `);


                ventana.document.close();


                ventana.focus();


                ventana.print();

            }
        );

    }
);

</script>


@endsection