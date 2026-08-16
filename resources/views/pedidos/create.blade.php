@extends('layouts.app')

@section('contenido')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">
                ➕ Nuevo pedido
            </h2>

            <p class="text-muted mb-0">
                Registrar un pedido desde administración
            </p>

        </div>

        <a
            href="{{ route('pedidos.index') }}"
            class="btn btn-secondary">

            ↩️ Volver

        </a>

    </div>


    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Revisá los siguientes errores:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('pedidos.store') }}"
        method="POST"
        id="formPedido">

        @csrf


        {{-- ========================================================
             DATOS DEL PEDIDO
        ========================================================= --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">

                📋 Datos del pedido

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- CLIENTE --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            <strong>
                                👤 Cliente
                            </strong>

                        </label>

                        <select
                            id="cliente"
                            name="cliente_id"
                            class="form-select"
                            required>

                            <option value="">
                                Seleccione un cliente
                            </option>

                            @foreach($clientes as $cliente)

                                <option
                                    value="{{ $cliente->id }}"
                                    {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>

                                    {{ $cliente->nombre_completo }}

                                    @if($cliente->documento)
                                        - {{ $cliente->documento }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- FECHA --}}

                    <div class="col-md-3">

                        <label class="form-label">

                            <strong>
                                📅 Fecha de entrega
                            </strong>

                        </label>

                        <input
                            type="date"
                            name="fecha_entrega"
                            class="form-control"
                            value="{{ old(
                                'fecha_entrega',
                                date('Y-m-d')
                            ) }}"
                            required>

                    </div>


                    {{-- HORA --}}

                    <div class="col-md-3">

                        <label class="form-label">

                            <strong>
                                🕐 Hora de entrega
                            </strong>

                        </label>

                        <input
                            type="time"
                            name="hora_entrega"
                            class="form-control"
                            value="{{ old(
                                'hora_entrega'
                            ) }}"
                            required>

                    </div>


                    {{-- TIPO ENTREGA --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            <strong>
                                🚚 Tipo de entrega
                            </strong>

                        </label>

                        <select
                            name="tipo_entrega"
                            id="tipo_entrega"
                            class="form-select"
                            required>

                            <option value="">
                                Seleccione
                            </option>

                            <option value="retiro">
                                🏪 Retiro en local
                            </option>

                            <option value="delivery">
                                🚚 Delivery
                            </option>

                        </select>

                    </div>


                    {{-- DIRECCIÓN --}}

                    <div class="col-md-8">

                        <label class="form-label">

                            <strong>
                                📍 Dirección de entrega
                            </strong>

                        </label>

                        <input
                            type="text"
                            name="direccion_entrega"
                            id="direccion_entrega"
                            class="form-control"
                            placeholder="Ingrese la dirección si corresponde..."
                            value="{{ old(
                                'direccion_entrega'
                            ) }}">

                    </div>


                    {{-- OBSERVACIONES --}}

                    <div class="col-12">

                        <label class="form-label">

                            <strong>
                                📝 Observaciones
                            </strong>

                        </label>

                        <textarea
                            name="observaciones"
                            class="form-control"
                            rows="3"
                            placeholder="Observaciones del pedido...">{{ old('observaciones') }}</textarea>

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================
             PRODUCTOS
        ========================================================= --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-dark text-white">

                🛒 Productos del pedido

            </div>


            <div class="card-body">


                <div class="row align-items-end g-3">


                    {{-- PRODUCTO --}}

                    <div class="col-md-7">

                        <label class="form-label">

                            <strong>
                                Producto
                            </strong>

                        </label>

                        <select
                            id="producto"
                            class="form-select">

                            <option value="">
                                Seleccione un producto
                            </option>

                            @foreach($productos as $producto)

                                <option
                                    value="{{ $producto->id }}"
                                    data-precio="{{ $producto->precio_venta }}"
                                    data-stock="{{ $producto->stock }}">

                                    {{ $producto->nombre }}

                                    -
                                    ${{ number_format(
                                        $producto->precio_venta,
                                        2
                                    ) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- CANTIDAD --}}

                    <div class="col-md-2">

                        <label class="form-label">

                            <strong>
                                Cantidad
                            </strong>

                        </label>

                        <input
                            type="number"
                            id="cantidad"
                            class="form-control"
                            value="1"
                            min="1">

                    </div>


                    {{-- AGREGAR --}}

                    <div class="col-md-3">

                        <button
                            type="button"
                            id="agregarProducto"
                            class="btn btn-primary w-100">

                            ➕ Agregar producto

                        </button>

                    </div>

                </div>


                <hr>


                {{-- TABLA --}}

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    Producto
                                </th>

                                <th class="text-center">
                                    Cantidad
                                </th>

                                <th class="text-end">
                                    Precio
                                </th>

                                <th class="text-end">
                                    Subtotal
                                </th>

                                <th class="text-center">
                                    Acción
                                </th>

                            </tr>

                        </thead>


                        <tbody id="detallePedido">

                            <tr id="filaVacia">

                                <td
                                    colspan="5"
                                    class="text-center text-muted">

                                    Todavía no hay productos agregados.

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                {{-- TOTAL --}}

                <div class="text-end mt-4">

                    <h3>

                        Total:

                        <span class="text-success">

                            $<span id="totalPedido">
                                0.00
                            </span>

                        </span>

                    </h3>

                </div>

            </div>

        </div>



        {{-- ========================================================
             GUARDAR
        ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mb-4">

            <a
                href="{{ route('pedidos.index') }}"
                class="btn btn-secondary">

                Cancelar

            </a>


            <button
                type="submit"
                id="guardarPedido"
                class="btn btn-success">

                💾 Crear pedido

            </button>

        </div>


    </form>

</div>


@endsection


@push('scripts')

<script>

let productosPedido = [];

let total = 0;


/*
|--------------------------------------------------------------------------
| Agregar producto
|--------------------------------------------------------------------------
*/

document
    .getElementById('agregarProducto')
    .addEventListener('click', function () {

        const select =
            document.getElementById('producto');

        const cantidadInput =
            document.getElementById('cantidad');


        if (select.value === '') {

            alert('Seleccione un producto.');

            return;

        }


        const cantidad =
            parseInt(
                cantidadInput.value
            );


        if (
            !cantidad ||
            cantidad < 1
        ) {

            alert(
                'Ingrese una cantidad válida.'
            );

            return;

        }


        const opcion =
            select.options[
                select.selectedIndex
            ];


        const productoId =
            select.value;


        const nombre =
            opcion.text;


        const precio =
            parseFloat(
                opcion.dataset.precio
            );


        /*
        |--------------------------------------------------------------------------
        | Si ya existe
        |--------------------------------------------------------------------------
        */

        const existente =
            productosPedido.find(
                producto =>
                    producto.producto_id == productoId
            );


        if (existente) {

            existente.cantidad += cantidad;

            existente.subtotal =
                existente.cantidad *
                existente.precio;

        } else {

            productosPedido.push({

                producto_id:
                    productoId,

                nombre:
                    nombre,

                cantidad:
                    cantidad,

                precio:
                    precio,

                subtotal:
                    precio * cantidad

            });

        }


        renderizarProductos();


        select.selectedIndex = 0;

        cantidadInput.value = 1;

    });


/*
|--------------------------------------------------------------------------
| Mostrar productos
|--------------------------------------------------------------------------
*/

function renderizarProductos()
{

    const tbody =
        document.getElementById(
            'detallePedido'
        );


    tbody.innerHTML = '';


    if (
        productosPedido.length === 0
    ) {

        tbody.innerHTML = `

            <tr>

                <td
                    colspan="5"
                    class="text-center text-muted">

                    Todavía no hay productos agregados.

                </td>

            </tr>

        `;

        recalcularTotal();

        return;

    }


    productosPedido.forEach(
        (producto, index) => {

            tbody.innerHTML += `

                <tr>

                    <td>

                        <strong>
                            ${producto.nombre}
                        </strong>

                    </td>


                    <td class="text-center">

                        ${producto.cantidad}

                    </td>


                    <td class="text-end">

                        $${producto.precio.toFixed(2)}

                    </td>


                    <td class="text-end">

                        <strong>

                            $${producto.subtotal.toFixed(2)}

                        </strong>

                    </td>


                    <td class="text-center">

                        <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            onclick="eliminarProducto(${index})">

                            🗑️ Eliminar

                        </button>

                    </td>

                </tr>

                <input
                    type="hidden"
                    name="productos[${index}][producto_id]"
                    value="${producto.producto_id}">

                <input
                    type="hidden"
                    name="productos[${index}][cantidad]"
                    value="${producto.cantidad}">

            `;

        }
    );


    recalcularTotal();

}


/*
|--------------------------------------------------------------------------
| Eliminar producto
|--------------------------------------------------------------------------
*/

function eliminarProducto(index)
{

    productosPedido.splice(
        index,
        1
    );


    renderizarProductos();

}


/*
|--------------------------------------------------------------------------
| Calcular total
|--------------------------------------------------------------------------
*/

function recalcularTotal()
{

    total = 0;


    productosPedido.forEach(
        producto => {

            total +=
                producto.subtotal;

        }
    );


    document.getElementById(
        'totalPedido'
    ).innerText =
        total.toFixed(2);

}


/*
|--------------------------------------------------------------------------
| Validar antes de guardar
|--------------------------------------------------------------------------
*/

document
    .getElementById('formPedido')
    .addEventListener('submit', function (event) {

        const cliente =
            document.getElementById(
                'cliente'
            ).value;


        if (cliente === '') {

            event.preventDefault();

            alert(
                'Debe seleccionar un cliente.'
            );

            return;

        }


        if (
            productosPedido.length === 0
        ) {

            event.preventDefault();

            alert(
                'Debe agregar al menos un producto.'
            );

            return;

        }

    });


/*
|--------------------------------------------------------------------------
| Mostrar / ocultar dirección
|--------------------------------------------------------------------------
*/

document
    .getElementById('tipo_entrega')
    .addEventListener('change', function () {

        const direccion =
            document.getElementById(
                'direccion_entrega'
            );


        if (this.value === 'delivery') {

            direccion.required = true;

        } else {

            direccion.required = false;

        }

    });

</script>

@endpush