@extends('layouts.cliente')

@section('titulo','Confirmar Pedido')

@section('contenido')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            {{-- Mensajes de error --}}

            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    <strong>⚠️ Atención</strong>

                    <br>

                    {{ session('error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            {{-- Errores de validación --}}

            @if($errors->any())

                <div class="alert alert-danger">

                    <strong>⚠️ Revisa los siguientes datos:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h3 class="mb-0">

                        📦 Confirmar Pedido

                    </h3>

                </div>


                <div class="card-body">


                    <form
                        action="{{ route('pedido.guardar') }}"
                        method="POST">

                        @csrf


                        {{-- =====================================================
                             DATOS DE ENTREGA
                        ====================================================== --}}

                        <h5 class="mb-3">

                            🚚 Datos de entrega

                        </h5>


                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Fecha de entrega

                                </label>

                                <input
                                    type="date"
                                    name="fecha_entrega"
                                    class="form-control"
                                    value="{{ old('fecha_entrega') }}"
                                    min="{{ date('Y-m-d') }}"
                                    required>

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Hora de entrega

                                </label>

                                <input
                                    type="time"
                                    name="hora_entrega"
                                    class="form-control"
                                    value="{{ old('hora_entrega') }}"
                                    required>

                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">

                                Tipo de entrega

                            </label>

                            <select
                                name="tipo_entrega"
                                class="form-select"
                                required>

                                <option
                                    value="Retiro"
                                    {{ old('tipo_entrega') == 'Retiro' ? 'selected' : '' }}>

                                    🏪 Retiro en local

                                </option>

                                <option
                                    value="Domicilio"
                                    {{ old('tipo_entrega') == 'Domicilio' ? 'selected' : '' }}>

                                    🚚 Envío a domicilio

                                </option>

                            </select>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">

                                Dirección de entrega

                            </label>

                            <input
                                type="text"
                                name="direccion_entrega"
                                class="form-control"
                                value="{{ old('direccion_entrega', $cliente->direccion) }}">

                        </div>


                        <div class="mb-3">

                            <label class="form-label">

                                Observaciones

                            </label>

                            <textarea
                                name="observaciones"
                                class="form-control"
                                rows="3"
                                placeholder="Alguna indicación para preparar o entregar tu pedido...">{{ old('observaciones') }}</textarea>

                        </div>


                        <hr>


                        {{-- =====================================================
                             PRODUCTOS
                        ====================================================== --}}

                        <h5 class="mb-3">

                            🛒 Productos del pedido

                        </h5>


                        @php

                            $total = 0;

                        @endphp


                        <ul class="list-group mb-4">

                            @foreach($carrito as $item)

                                @php

                                    $subtotal =
                                        $item['precio'] *
                                        $item['cantidad'];

                                    $total += $subtotal;

                                @endphp


                                <li class="list-group-item d-flex justify-content-between align-items-center">

                                    <div>

                                        <strong>

                                            {{ $item['nombre'] }}

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            Cantidad:
                                            {{ $item['cantidad'] }}

                                        </small>

                                    </div>


                                    <strong>

                                        ${{ number_format($subtotal,2) }}

                                    </strong>

                                </li>

                            @endforeach


                            <li class="list-group-item d-flex justify-content-between">

                                <strong>

                                    Total del pedido

                                </strong>

                                <strong class="text-success fs-4">

                                    ${{ number_format($total,2) }}

                                </strong>

                            </li>

                        </ul>


                        {{-- =====================================================
                             FORMA DE PAGO
                        ====================================================== --}}

                        <div class="card border mb-4">

                            <div class="card-body">

                                <h5 class="mb-3">

                                    💳 Forma de pago

                                </h5>


                                {{-- CONTADO --}}

                                <div class="form-check border rounded p-3 mb-3">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="tipo_pago"
                                        id="pagoContado"
                                        value="contado"
                                        {{ old('tipo_pago', 'contado') == 'contado' ? 'checked' : '' }}
                                        required>

                                    <label
                                        class="form-check-label w-100"
                                        for="pagoContado">

                                        <strong>

                                            💵 Pago contado

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            El pedido se paga al momento de recibirlo o retirarlo.

                                        </small>

                                    </label>

                                </div>


                                {{-- FIADO --}}

                                @if($cliente->permite_fiado)

                                    <div class="form-check border border-warning rounded p-3">

                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="tipo_pago"
                                            id="pagoFiado"
                                            value="fiado"
                                            {{ old('tipo_pago') == 'fiado' ? 'checked' : '' }}>

                                        <label
                                            class="form-check-label w-100"
                                            for="pagoFiado">

                                            <strong>

                                                📒 Comprar fiado

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                El importe quedará registrado en tu estado de cuenta.

                                            </small>

                                        </label>

                                    </div>


                                    {{-- INFORMACIÓN DEL CRÉDITO --}}

                                    <div class="alert alert-warning mt-3 mb-0">

                                        <h6 class="fw-bold">

                                            📊 Información de tu crédito

                                        </h6>


                                        <div class="row text-center mt-3">

                                            <div class="col-md-4 mb-2">

                                                <small class="text-muted d-block">

                                                    Límite de crédito

                                                </small>

                                                <strong>

                                                    ${{ number_format($limiteCredito,2) }}

                                                </strong>

                                            </div>


                                            <div class="col-md-4 mb-2">

                                                <small class="text-muted d-block">

                                                    Deuda actual

                                                </small>

                                                <strong class="text-danger">

                                                    ${{ number_format($deudaActual,2) }}

                                                </strong>

                                            </div>


                                            <div class="col-md-4 mb-2">

                                                <small class="text-muted d-block">

                                                    Crédito disponible

                                                </small>

                                                <strong class="text-success">

                                                    ${{ number_format($creditoDisponible,2) }}

                                                </strong>

                                            </div>

                                        </div>


                                        @if($total > $creditoDisponible)

                                            <div class="alert alert-danger mt-3 mb-0">

                                                ⚠️ Este pedido supera tu crédito disponible.

                                                <br>

                                                Puedes elegir <strong>Pago contado</strong> para continuar.

                                            </div>

                                        @endif

                                    </div>


                                @else

                                    {{-- CLIENTE SIN PERMISO --}}

                                    <div class="alert alert-info mt-3 mb-0">

                                        ℹ️ Tu cuenta actualmente no tiene habilitadas las compras fiadas.

                                        <br>

                                        Puedes realizar tu pedido mediante <strong>pago contado</strong>.

                                    </div>

                                @endif

                            </div>

                        </div>


                        {{-- =====================================================
                             BOTÓN
                        ====================================================== --}}

                        <button
                            type="submit"
                            class="btn btn-success btn-lg w-100">

                            ✅ Confirmar Pedido

                        </button>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
     JAVASCRIPT
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const pagoFiado = document.getElementById('pagoFiado');

    const total = {{ $total }};

    const creditoDisponible = {{ $creditoDisponible }};

    const formulario = document.querySelector('form[action="{{ route('pedido.guardar') }}"]');

    if (pagoFiado) {

        pagoFiado.addEventListener('change', function () {

            if (total > creditoDisponible) {

                alert(
                    '⚠️ No puedes realizar este pedido fiado porque supera tu crédito disponible.'
                );

                document.getElementById('pagoContado').checked = true;

            }

        });

    }


    formulario.addEventListener('submit', function (event) {

        if (
            pagoFiado &&
            pagoFiado.checked &&
            total > creditoDisponible
        ) {

            event.preventDefault();

            alert(
                '⚠️ El pedido supera tu crédito disponible. Selecciona Pago contado para continuar.'
            );

            document.getElementById('pagoContado').checked = true;

        }

    });

});

</script>

@endsection