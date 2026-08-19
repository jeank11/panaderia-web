@extends('layouts.app')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1">
            🏦 Transferencias
        </h2>

        <p class="text-muted mb-0">
            Transferencias informadas por los clientes
        </p>

    </div>

</div>


{{-- Mensaje de éxito --}}
@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        ✅ {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- Mensaje de error --}}
@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show">

        ❌ {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


<div class="card shadow-sm">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>
                            Cliente
                        </th>

                        <th>
                            Monto
                        </th>

                        <th>
                            Fecha
                        </th>

                        <th>
                            Referencia
                        </th>

                        <th>
                            Estado
                        </th>

                        <th>
                            Comprobante
                        </th>

                        <th>
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($transferencias as $transferencia)

                        <tr>

                            {{-- Cliente --}}
                            <td>

                                @if($transferencia->cliente)

                                    <strong>

                                        {{ $transferencia->cliente->nombre }}
                                        {{ $transferencia->cliente->apellido }}

                                    </strong>

                                @else

                                    <span class="text-danger">
                                        Cliente no encontrado
                                    </span>

                                @endif

                            </td>


                            {{-- Monto --}}
                            <td>

                                <strong>

                                    ${{ number_format(
                                        $transferencia->monto,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            {{-- Fecha --}}
                            <td>

                                {{ \Carbon\Carbon::parse(
                                    $transferencia->fecha_transferencia
                                )->format('d/m/Y') }}

                            </td>


                            {{-- Referencia --}}
                            <td>

                                {{ $transferencia->referencia }}

                            </td>


                            {{-- Estado --}}
                            <td>

                                @if($transferencia->estado === 'pendiente')

                                    <span class="badge bg-warning text-dark">

                                        ⏳ Pendiente

                                    </span>

                                @elseif($transferencia->estado === 'aprobado')

                                    <span class="badge bg-success">

                                        ✅ Aprobada

                                    </span>

                                @elseif($transferencia->estado === 'rechazado')

                                    <span class="badge bg-danger">

                                        ❌ Rechazada

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        {{ ucfirst(
                                            $transferencia->estado
                                        ) }}

                                    </span>

                                @endif

                            </td>


                            {{-- Comprobante --}}
                            <td>

                                @if($transferencia->comprobante)

                                    <a
                                        href="{{ asset(
                                            'storage/' .
                                            $transferencia->comprobante
                                        ) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-primary">

                                        📎 Ver

                                    </a>

                                @else

                                    <span class="text-muted">
                                        Sin comprobante
                                    </span>

                                @endif

                            </td>


                            {{-- Acciones --}}
                            {{-- Acciones --}}
<td>

    @if($transferencia->estado === 'pendiente')

        <div class="d-flex gap-2 flex-wrap">

            {{-- APROBAR --}}
            <form
                action="{{ route(
                    'transferencias.aprobar',
                    $transferencia
                ) }}"
                method="POST"
                onsubmit="return confirm(
                    '¿Confirmás aprobar esta transferencia y aplicar el pago a la deuda del cliente?'
                );">

                @csrf

                <button
                    type="submit"
                    class="btn btn-sm btn-success">

                    ✅ Aprobar

                </button>

            </form>


            {{-- RECHAZAR --}}
            <form
                action="{{ route(
                    'transferencias.rechazar',
                    $transferencia
                ) }}"
                method="POST"
                onsubmit="return confirm(
                    '¿Estás seguro de rechazar esta transferencia? No se aplicará ningún pago a la deuda del cliente.'
                );">

                @csrf

                <button
                    type="submit"
                    class="btn btn-sm btn-danger">

                    ❌ Rechazar

                </button>

            </form>

        </div>


    @elseif($transferencia->estado === 'aprobado')

        <span class="text-success">

            ✅ Procesada

        </span>


    @elseif($transferencia->estado === 'rechazado')

        <span class="text-danger">

            ❌ Rechazada

        </span>

    @endif

</td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-4">

                                <div style="font-size:45px;">
                                    🏦
                                </div>

                                <h5 class="mt-2">
                                    No hay transferencias
                                </h5>

                                <p class="text-muted mb-0">
                                    Todavía no se han informado transferencias.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection