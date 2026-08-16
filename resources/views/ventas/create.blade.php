@extends('layouts.app')

@section('contenido')

<h2 class="mb-4">Nueva Venta</h2>

<div class="card">

    <div class="card-header">
        Registrar Venta
    </div>

    <div class="card-body">

        <div class="row mb-4">

            <div class="col-md-6">

                <label class="form-label"><strong>Fecha</strong></label>

                <input
    type="datetime-local"
    id="fecha"
    name="fecha"
    class="form-control"
    value="{{ now()->format('Y-m-d\TH:i') }}"
    required>

            </div>

            <div class="col-md-6">

                <label class="form-label"><strong>Usuario</strong></label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ auth()->user()->name }}"
                    readonly>

            </div>

        </div>
        <div class="row mb-4">

    <div class="col-md-6">

        <label class="form-label">
            <strong>Cliente</strong>
        </label>

        <select 
                id="cliente"
                class="form-select"
                name="cliente_id">

            <option value="">
                Seleccione un cliente
            </option>

            @foreach($clientes as $cliente)

                <option value="{{ $cliente->id }}">

                    {{ $cliente->nombre_completo }}

                    -

                    {{ $cliente->documento }}

                </option>

            @endforeach

        </select>

    </div>

</div>

        <form>

            <div class="row align-items-end">

                <div class="col-md-6">

                    <label class="form-label">
                        Producto
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

        </option>

    @endforeach

</select>

                </div>

                <div class="col-md-2">

                    <label class="form-label">
                        Cantidad
                    </label>

                    <input
    id="cantidad"
    type="number"
    class="form-control"
    value="1"
    min="1">

                </div>

                <div class="col-md-2">

                   <button
    type="button"
    id="agregarProducto"
    class="btn btn-primary w-100">

    Agregar

</button>

                </div>

            </div>

        </form>

        <hr>

        <table class="table table-bordered">

            <thead class="table-dark">

                <tr>

                    <th>Producto</th>

                    <th>Cantidad</th>

                    <th>Precio</th>

                    <th>Subtotal</th>

                    <th>Acción</th>

                </tr>

            </thead>

            <tbody id="detalleVenta">

<tr>

    <td colspan="4" class="text-center">

        Todavía no hay productos agregados.

    </td>

</tr>

</tbody>

        </table>

        <div class="row mt-4">

    <div class="col-md-6">

        <label class="form-label">
            <strong>Tipo de pago</strong>
        </label>

        <select
            id="tipo_pago"
            class="form-select">

            <option value="contado" selected>
                💵 Contado
            </option>

            <option value="fiado">
                📒 Fiado
            </option>

        </select>

    </div>

    <div class="col-md-6 text-end">

        <h3 class="mt-4">
            Total:
            $<span id="totalVenta">0.00</span>
        </h3>

    </div>

</div>

<div class="text-end mt-4">

    <button
        type="button"
        id="guardarVenta"
        class="btn btn-success">

        💾 Guardar Venta

    </button>

</div>

    </div>

</div>

@endsection

@push('scripts')
<script>
let productosVenta = [];
let total = 0;

document.getElementById('agregarProducto').addEventListener('click', function () {
    const cliente = document.getElementById('cliente');

if (cliente.value === '') {
    alert('Debe seleccionar un cliente antes de agregar productos.');
    return;
}

    const select = document.getElementById('producto');
    const cantidad = document.getElementById('cantidad');

    if (select.value === '') {
        alert('Seleccione un producto');
        return;
    }

    const opcion = select.options[select.selectedIndex];

    const nombre = opcion.text;
    const precio = parseFloat(opcion.dataset.precio);
    const stock = parseInt(opcion.dataset.stock);
    const cant = parseInt(cantidad.value);

    if (cant > stock) {
        alert('No hay suficiente stock.');
        return;
    }

    const subtotal = precio * cant;
let existe = productosVenta.find(
    producto => producto.producto_id == select.value
);

if (existe) {

    existe.cantidad += cant;

    existe.subtotal =
        existe.cantidad * existe.precio;


} else {

    productosVenta.push({

        producto_id: select.value,

        cantidad: cant,

        precio: precio,

        subtotal: subtotal

    });

}

    const filas = document.querySelectorAll('#detalleVenta tr');

for (let fila of filas) {

    if (fila.dataset.producto == select.value) {

        let cantidadActual = parseInt(
            fila.children[1].innerText
        );

        cantidadActual += cant;
        existe.cantidad = cantidadActual;
        existe.subtotal = cantidadActual * precio;

        fila.children[1].innerText = cantidadActual;

        let nuevoSubtotal = cantidadActual * precio;

        fila.children[3].innerText =
            '$' + nuevoSubtotal.toFixed(2);

        recalcularTotal();

        select.selectedIndex = 0;
        cantidad.value = 1;
        console.log(productosVenta);

        return;
    }

}

    const tbody = document.getElementById('detalleVenta');

    if (tbody.innerHTML.includes('Todavía no hay productos')) {
        tbody.innerHTML = '';
    }

    tbody.innerHTML += `
        <tr data-producto="${select.value}">
            <td>${nombre}</td>
            <td>${cant}</td>
            <td>$${precio.toFixed(2)}</td>
            <td>$${subtotal.toFixed(2)}</td>
            <td>
              <button 
    type="button"
    class="btn btn-danger btn-sm"
    onclick="eliminarProducto(this, ${select.value})">

    Eliminar

</button>
            </td>
        </tr>
    `;

    recalcularTotal();

    select.selectedIndex = 0;
    cantidad.value = 1;

});
function eliminarProducto(boton, productoId)
{

    boton.closest('tr').remove();


    productosVenta = productosVenta.filter(
        producto => producto.producto_id != productoId
    );


    recalcularTotal();


}

function recalcularTotal()
{

    total = 0;


    productosVenta.forEach(producto => {

        total += producto.subtotal;

    });


    document.getElementById('totalVenta').innerText =
        total.toFixed(2);

}

document.getElementById('guardarVenta')
.addEventListener('click', function(){

    const cliente = document.getElementById('cliente').value;

    const tipoPago = document.getElementById('tipo_pago').value;

    if(cliente === ''){
        alert('Seleccione un cliente');
        return;
    }

    if(productosVenta.length === 0){
        alert('Agregue productos a la venta');
        return;
    }

    fetch('/ventas', {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'X-CSRF-TOKEN':
                document.querySelector('meta[name="csrf-token"]').content

        },

        body: JSON.stringify({

    cliente_id: cliente,

    fecha: document.getElementById('fecha').value,

    productos: productosVenta,

    total: total,

    tipo_pago: tipoPago

})

    })
    .then(async response => {

        const data = await response.json();

        console.log(data);

        if (!response.ok) {
            alert(data.message ?? 'Ocurrió un error.');
            return;
        }

        alert('Venta guardada correctamente');

    });

});

</script>

@endpush