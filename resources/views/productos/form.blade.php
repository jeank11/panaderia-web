<div class="row">

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Código
        </label>

        <input type="text"
               name="codigo"
               class="form-control @error('codigo') is-invalid @enderror"
               value="{{ old('codigo', $producto->codigo ?? '') }}">

        @error('codigo')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-8 mb-3">

        <label class="form-label">
            Nombre
        </label>

        <input type="text"
               name="nombre"
               class="form-control @error('nombre') is-invalid @enderror"
               value="{{ old('nombre', $producto->nombre ?? '') }}">

        @error('nombre')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-6 mb-3">

        <label class="form-label">
            Categoría
        </label>

        <select name="categoria_id"
                class="form-select @error('categoria_id') is-invalid @enderror">


            <option value="">
                Seleccione una categoría
            </option>


            @foreach($categorias as $categoria)

                <option value="{{ $categoria->id }}"
                    {{ old('categoria_id',$producto->categoria_id ?? '') == $categoria->id ? 'selected':'' }}>

                    {{ $categoria->nombre }}

                </option>

            @endforeach


        </select>


        @error('categoria_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror


    </div>



    <div class="col-md-3 mb-3">

        <label class="form-label">
            Precio Compra
        </label>

        <input type="number"
               step="0.01"
               name="precio_compra"
               class="form-control"
               value="{{ old('precio_compra',$producto->precio_compra ?? '') }}">

    </div>



    <div class="col-md-3 mb-3">

        <label class="form-label">
            Precio Venta
        </label>

        <input type="number"
               step="0.01"
               name="precio_venta"
               class="form-control"
               value="{{ old('precio_venta',$producto->precio_venta ?? '') }}">

    </div>



    <div class="col-md-3 mb-3">

        <label class="form-label">
            Stock
        </label>

        <input type="number"
               name="stock"
               class="form-control"
               value="{{ old('stock',$producto->stock ?? '') }}">

    </div>



    <div class="col-md-3 mb-3">

        <label class="form-label">
            Stock mínimo
        </label>

        <input type="number"
               name="stock_minimo"
               class="form-control"
               value="{{ old('stock_minimo',$producto->stock_minimo ?? '') }}">

    </div>



    {{-- IMAGEN --}}

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Imagen del producto
        </label>


        <input type="file"
               name="imagen"
               class="form-control"
               accept="image/*"
               onchange="previewImagen(event)">



        @if(isset($producto) && $producto->imagen)

            <img id="imagenPreview"
                 src="{{ asset('storage/'.$producto->imagen) }}"
                 class="mt-3 rounded"
                 width="200">

        @else

            <img id="imagenPreview"
                 class="mt-3 rounded"
                 width="200"
                 style="display:none">

        @endif


    </div>


</div>



<script>

function previewImagen(event)
{

    let imagen = document.getElementById('imagenPreview');

    imagen.src = URL.createObjectURL(
        event.target.files[0]
    );

    imagen.style.display='block';

}

</script>