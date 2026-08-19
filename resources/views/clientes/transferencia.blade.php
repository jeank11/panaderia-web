@extends('layouts.cliente')

@section('contenido')

<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h4 class="mb-0">
                        🏦 Pago por transferencia bancaria
                    </h4>

                </div>


                <div class="card-body">

                    <div class="alert alert-info">

                        <h5>
                            Datos para realizar la transferencia
                        </h5>

                        <hr>

                        <p class="mb-2">
                            🏦 <strong>Banco:</strong> BROU
                        </p>

                        <p class="mb-2">
                            👤 <strong>Titular:</strong> Carlos Echeveste
                        </p>

                        <p class="mb-2">
                            💳 <strong>Número de cuenta:</strong>
                            110027653-00001
                        </p>

                        <p class="mb-0">
                            🔢 <strong>Número de referencia:</strong>
                            098402862
                        </p>

                    </div>


                    <div class="alert alert-warning">

                        <strong>
                            Deuda actual:
                        </strong>

                        ${{ number_format(
                            $cliente->deuda_actual,
                            2
                        ) }}

                    </div>


                    <form
                        action="{{ route(
                            'clientes.transferencia.store'
                        ) }}"
                        method="POST"
                        enctype="multipart/form-data">

                        @csrf


                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                💰 Monto transferido
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
                                    value="{{ old('monto') }}"
                                    required>

                            </div>

                            @error('monto')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                📅 Fecha de transferencia
                            </label>

                            <input
                                type="date"
                                name="fecha_transferencia"
                                class="form-control"
                                value="{{ old(
                                    'fecha_transferencia',
                                    date('Y-m-d')
                                ) }}"
                                required>

                            @error('fecha_transferencia')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                🔢 Referencia de la transferencia
                            </label>

                            <input
                                type="text"
                                name="referencia"
                                class="form-control"
                                value="{{ old('referencia') }}"
                                placeholder="Ingrese la referencia de la transferencia"
                                required>

                            @error('referencia')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                📎 Comprobante
                            </label>

                            <input
                                type="file"
                                name="comprobante"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.pdf">

                            <small class="text-muted">
                                Puede adjuntar una imagen o PDF del comprobante.
                            </small>

                            @error('comprobante')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="mb-4">

                            <label class="form-label fw-bold">
                                📝 Observación
                            </label>

                            <textarea
                                name="observacion"
                                class="form-control"
                                rows="3"
                                placeholder="Opcional">{{ old('observacion') }}</textarea>

                            @error('observacion')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="d-flex gap-2">

                            <a
                                href="{{ route(
                                    'clientes.cuenta',
                                    $cliente
                                ) }}"
                                class="btn btn-secondary">

                                ← Volver al estado de cuenta

                            </a>


                            <button
                                type="submit"
                                class="btn btn-success">

                                🏦 Informar transferencia

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

