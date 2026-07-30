@extends('layouts.cliente')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
        📦 Mis Pedidos
    </h2>

    <a href="{{ route('clientes.productos') }}"
       class="btn btn-success">
        🛍 Hacer un nuevo pedido
    </a>

</div>


@if($pedidos->count())


<div class="card shadow">

<div class="card-body">


<table class="table table-hover align-middle">


<thead class="table-dark">

<tr>

<th>Código</th>
<th>Fecha entrega</th>
<th>Hora</th>
<th>Total</th>
<th>Estado</th>
<th>Acción</th>

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

{{ \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y') }}

</td>



<td>

{{ $pedido->hora_entrega }}

</td>



<td>

${{ number_format($pedido->total,2) }}

</td>



<td>


@if($pedido->estado == 'Pendiente')

<span class="badge bg-warning text-dark">
🟡 Pendiente
</span>


@elseif($pedido->estado == 'Preparando')


<span class="badge bg-primary">
🔵 Preparando
</span>


@elseif($pedido->estado == 'Listo')


<span class="badge bg-success">
🟢 Listo
</span>


@elseif($pedido->estado == 'Entregado')


<span class="badge bg-success">
✅ Entregado
</span>


@elseif($pedido->estado == 'Cancelado')


<span class="badge bg-danger">
❌ Cancelado
</span>


@endif


</td>



<td>


<a href="{{ route('clientes.pedido.detalle',$pedido) }}"
class="btn btn-primary btn-sm">


<i class="bi bi-eye"></i>

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


<div class="alert alert-info text-center">

Todavía no tienes pedidos realizados.

</div>


@endif


@endsection