@extends('layouts.web')

@section('titulo','Confirmar Pedido')

@section('contenido')

<div class="container py-5">


<div class="row justify-content-center">


<div class="col-md-8">


<div class="card shadow">


<div class="card-header bg-primary text-white">

<h3 class="mb-0">

📦 Confirmar Pedido

</h3>

</div>



<div class="card-body">


<form action="{{ route('pedido.guardar') }}"
      method="POST">

@csrf



<h5 class="mb-3">

Datos de entrega

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


<option value="Retiro">

🏪 Retiro en local

</option>


<option value="Domicilio">

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
value="{{ $cliente->direccion }}">


</div>





<div class="mb-3">


<label class="form-label">

Observaciones

</label>


<textarea
name="observaciones"
class="form-control"
rows="3"></textarea>


</div>




<hr>



<h5>

Productos

</h5>



<ul class="list-group mb-3">


@php

$total = 0;

@endphp



@foreach($carrito as $item)


@php

$subtotal =
$item['precio'] *
$item['cantidad'];

$total += $subtotal;

@endphp



<li class="list-group-item d-flex justify-content-between">


<span>

{{ $item['nombre'] }}

x

{{ $item['cantidad'] }}

</span>


<strong>

${{ number_format($subtotal,2) }}

</strong>


</li>


@endforeach


<li class="list-group-item d-flex justify-content-between">


<strong>

Total

</strong>


<strong class="text-success">

${{ number_format($total,2) }}

</strong>


</li>


</ul>




<button
class="btn btn-success btn-lg w-100">


✅ Confirmar Pedido


</button>



</form>



</div>


</div>


</div>


</div>


</div>


@endsection