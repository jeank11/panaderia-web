@extends('layouts.cliente')

@section('contenido')


<h2 class="mb-4">
    🛒 Mis Compras
</h2>


@forelse($cliente->ventas as $venta)


<div class="card shadow mb-4">


<div class="card-header bg-primary text-white">

    Compra #{{ $venta->id }}

</div>


<div class="card-body">


<p>
<strong>Fecha:</strong>

{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}

</p>


<p>

<strong>Total:</strong>

${{ number_format($venta->total,2) }}

</p>


<p>

<strong>Estado:</strong>

@if($venta->estado)

<span class="badge bg-success">
Activa
</span>

@else

<span class="badge bg-danger">
Anulada
</span>

@endif

</p>



<hr>


<h5>
Productos
</h5>


<ul>

@foreach($venta->detalles as $detalle)

<li>

{{ $detalle->producto->nombre }}

-

Cantidad:
{{ $detalle->cantidad }}

-

${{ number_format($detalle->subtotal,2) }}

</li>

@endforeach


</ul>


</div>


</div>


@empty


<div class="alert alert-info">

Todavía no tienes compras.

</div>


@endforelse



@endsection